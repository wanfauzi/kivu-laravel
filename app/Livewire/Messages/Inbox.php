<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Inbox extends Component
{
    public ?int $projectId = null;

    public ?int $peerId = null;

    public string $body = '';

    public function mount(): void
    {
        $requestedProject = (int) request()->query('project', 0);
        $requestedPeer = (int) request()->query('peer', 0);

        if ($requestedProject && $this->baseQuery()->whereKey($requestedProject)->exists()) {
            $project = Project::find($requestedProject);
            if ($project) {
                if (Auth::user()->role === 'student') {
                    $this->projectId = $requestedProject;
                    $this->peerId = $project->owner_id;
                    $this->markThreadRead($this->conversationKey($requestedProject, Auth::id(), $project->owner_id));
                    return;
                }

                if (Auth::user()->role === 'umkm' && $requestedPeer) {
                    $peerExists = User::whereKey($requestedPeer)->exists()
                        && Project::whereKey($requestedProject)->where('owner_id', Auth::id())->exists();
                    if ($peerExists) {
                        $this->projectId = $requestedProject;
                        $this->peerId = $requestedPeer;
                        $this->markThreadRead($this->conversationKey($requestedProject, Auth::id(), $requestedPeer));
                        return;
                    }
                }

                if (Auth::user()->role === 'umkm' && ! $requestedPeer) {
                    $other = $this->otherIdFromMessages($requestedProject);
                    if ($other) {
                        $this->projectId = $requestedProject;
                        $this->peerId = $other;
                        $this->markThreadRead($this->conversationKey($requestedProject, Auth::id(), $other));
                        return;
                    }
                }
            }
        }

        $firstKey = Message::where(function (Builder $q) {
            $q->where('sender_id', Auth::id())->orWhere('recipient_id', Auth::id());
        })
            ->orderByDesc('created_at')
            ->value('conversation_key');

        if ($firstKey) {
            [$pid, $a, $b] = $this->parseKey($firstKey);
            if ($pid) {
                $this->projectId = $pid;
                $this->peerId = (int) $a === Auth::id() ? (int) $b : (int) $a;
                $this->markThreadRead($firstKey);
            }
        } else {
            $first = $this->baseQuery()
                ->whereHas('messages')
                ->orderByDesc('updated_at')
                ->value('id');

            if ($first) {
                $this->projectId = (int) $first;
                $proj = Project::find($first);
                if ($proj) {
                    $this->peerId = Auth::user()->role === 'student' ? $proj->owner_id : null;
                    if ($this->peerId) {
                        $this->markThreadRead($this->conversationKey($first, Auth::id(), $this->peerId));
                    }
                }
            }
        }
    }

    private function baseQuery(): Builder
    {
        $user = Auth::user();
        $query = Project::query();
        if ($user->role === 'student') {
            $query->where(function (Builder $q) use ($user) {
                $q->whereHas('applications', fn (Builder $a) => $a->where('student_id', $user->id))
                    ->orWhereHas('submissions', fn (Builder $s) => $s->where('student_id', $user->id));
            });
        } else {
            $query->where('owner_id', $user->id);
        }
        return $query;
    }

    private function userHasAccessToProject(int $userId, int $projectId): bool
    {
        return (bool) Project::whereKey($projectId)
            ->where(function (Builder $q) use ($userId) {
                $q->whereHas('applications', fn (Builder $a) => $a->where('student_id', $userId))
                    ->orWhereHas('submissions', fn (Builder $s) => $s->where('student_id', $userId));
            })
            ->exists();
    }

    private function conversationKey(int $projectId, int $userA, int $userB): string
    {
        $a = min($userA, $userB);
        $b = max($userA, $userB);
        return "p{$projectId}:{$a}-{$b}";
    }

    private function parseKey(string $key): array
    {
        if (! preg_match('/^p(\d+):(\d+)-(\d+)$/', $key, $m)) {
            return [null, null, null];
        }
        return [(int) $m[1], (int) $m[2], (int) $m[3]];
    }

    private function otherIdFromMessages(int $projectId): ?int
    {
        $msg = Message::where('conversation_key', 'like', "p{$projectId}:%")
            ->where(function (Builder $q) {
                $q->where('sender_id', Auth::id())->orWhere('recipient_id', Auth::id());
            })
            ->orderByDesc('created_at')
            ->first();

        if (! $msg) {
            return null;
        }
        [$pid, $a, $b] = $this->parseKey($msg->conversation_key);
        return (int) $a === Auth::id() ? (int) $b : (int) $a;
    }

    private function markThreadRead(string $conversationKey): void
    {
        Message::where('conversation_key', $conversationKey)
            ->where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function selectThread(int $projectId, ?int $peerId = null): void
    {
        abort_unless($this->baseQuery()->whereKey($projectId)->exists(), 403);

        $user = Auth::user();
        if ($user->role === 'student') {
            $peerId = Project::whereKey($projectId)->value('owner_id');
        }

        if (! $peerId) {
            $peerId = $this->otherIdFromMessages($projectId);
            if (! $peerId) {
                $project = Project::find($projectId);
                $peerId = $project ? $project->owner_id : null;
            }
        }

        abort_unless($peerId && User::whereKey($peerId)->exists(), 403);

        if ($user->role === 'student') {
            $expected = Project::whereKey($projectId)->value('owner_id');
            abort_unless((int) $peerId === (int) $expected, 403);
        }

        $this->projectId = $projectId;
        $this->peerId = (int) $peerId;
        $this->body = '';
        $this->markThreadRead($this->conversationKey($projectId, Auth::id(), (int) $peerId));
    }

    public function selectThreadByKey(string $key): void
    {
        [$pid, $a, $b] = $this->parseKey($key);
        abort_unless($pid && $this->baseQuery()->whereKey($pid)->exists(), 403);
        abort_unless(in_array(Auth::id(), [(int) $a, (int) $b], true), 403);
        $peer = (int) $a === Auth::id() ? (int) $b : (int) $a;
        $this->projectId = $pid;
        $this->peerId = $peer;
        $this->body = '';
        $this->markThreadRead($key);
    }

    public function closeThread(): void
    {
        $this->projectId = null;
        $this->peerId = null;
    }

    public function send(): void
    {
        $this->validate([
            'body' => 'required|string|min:1|max:2000',
        ]);

        if (! $this->projectId) {
            return;
        }

        if (! $this->peerId) {
            $project = Project::find($this->projectId);
            if (! $project) {
                return;
            }
            if (Auth::user()->role === 'student') {
                $this->peerId = $project->owner_id;
            } else {
                $this->peerId = $this->otherIdFromMessages($this->projectId);
                if (! $this->peerId) {
                    $this->peerId = Application::where('project_id', $this->projectId)->orderBy('created_at')->value('student_id')
                        ?? Submission::where('project_id', $this->projectId)->value('student_id');
                }
            }
        }

        if (! $this->peerId) {
            return;
        }

        abort_unless($this->baseQuery()->whereKey($this->projectId)->exists(), 403);

        $peer = User::find($this->peerId);
        abort_unless($peer, 403);

        if (Auth::user()->role === 'student') {
            $expected = Project::whereKey($this->projectId)->value('owner_id');
            abort_unless((int) $this->peerId === (int) $expected, 403);
        } else {
            abort_unless(Project::whereKey($this->projectId)->where('owner_id', Auth::id())->exists(), 403);
        }

        $key = $this->conversationKey($this->projectId, Auth::id(), $this->peerId);

        Message::create([
            'project_id' => $this->projectId,
            'sender_id' => Auth::id(),
            'recipient_id' => $this->peerId,
            'conversation_key' => $key,
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->markThreadRead($key);
        $this->dispatch('toast', message: 'Pesan terkirim.');
    }

    public function render()
    {
        $userId = Auth::id();

        $conversationKeys = Message::where(function (Builder $q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('recipient_id', $userId);
        })
            ->select('conversation_key', DB::raw('MAX(created_at) as last_at'))
            ->groupBy('conversation_key')
            ->orderByDesc('last_at')
            ->pluck('conversation_key');

        $threads = collect();
        foreach ($conversationKeys as $key) {
            [$pid, $a, $b] = $this->parseKey($key);
            if (! $pid) {
                continue;
            }
            if (! $this->baseQuery()->whereKey($pid)->exists()) {
                continue;
            }
            $peerId = (int) $a === $userId ? (int) $b : (int) $a;
            $project = Project::with('owner:id,name')->find($pid);
            $peer = User::find($peerId);
            if (! $project || ! $peer) {
                continue;
            }
            $last = Message::where('conversation_key', $key)->latest()->first();
            $unread = Message::where('conversation_key', $key)->where('recipient_id', $userId)->whereNull('read_at')->count();

            $threads->push((object) [
                'conversation_key' => $key,
                'project' => $project,
                'id' => $project->id,
                'title' => $project->title,
                'owner' => $project->owner,
                'peer' => $peer,
                'messages' => collect([$last])->filter(),
                'unread_count' => $unread,
                'last_at' => $last?->created_at,
                'status' => $project->status,
            ]);
        }

        $activeThread = null;
        $messages = collect();

        if ($this->projectId && $this->peerId) {
            $activeKey = $this->conversationKey($this->projectId, $userId, $this->peerId);
            $activeThread = $threads->firstWhere('conversation_key', $activeKey);

            if (! $activeThread) {
                $project = $this->baseQuery()->with('owner:id,name')->whereKey($this->projectId)->first();
                $peer = User::find($this->peerId);
                if ($project && $peer) {
                    $activeThread = (object) [
                        'conversation_key' => $activeKey,
                        'project' => $project,
                        'id' => $project->id,
                        'title' => $project->title,
                        'owner' => $project->owner,
                        'peer' => $peer,
                        'messages' => collect(),
                        'unread_count' => 0,
                        'last_at' => null,
                        'status' => $project->status,
                    ];
                }
            }

            if ($activeThread) {
                $messages = Message::with('sender:id,name')
                    ->where('conversation_key', $activeKey)
                    ->orderBy('id')
                    ->get();
            }
        }

        return view('livewire.messages.inbox', [
            'threads' => $threads,
            'activeThread' => $activeThread,
            'messages' => $messages,
        ])->layout('components.layouts.dashboard');
    }
}