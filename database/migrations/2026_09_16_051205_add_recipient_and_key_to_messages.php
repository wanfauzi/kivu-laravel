<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('recipient_id')->nullable()->after('sender_id')->constrained('users')->nullOnDelete();
            $table->string('conversation_key', 64)->nullable()->after('recipient_id');
            $table->index(['conversation_key', 'created_at']);
        });

        $projects = DB::table('projects')->pluck('owner_id', 'id');

        foreach (DB::table('messages')->get() as $msg) {
            $projectOwner = $projects[$msg->project_id] ?? null;
            if (! $projectOwner) {
                continue;
            }

            if ((int) $msg->sender_id === (int) $projectOwner) {
                $studentId = DB::table('applications')
                    ->where('project_id', $msg->project_id)
                    ->orderBy('created_at')
                    ->value('student_id')
                    ?? DB::table('submissions')
                        ->where('project_id', $msg->project_id)
                        ->value('student_id');

                $recipient = $studentId ?? $msg->sender_id;
            } else {
                $recipient = $projectOwner;
            }

            $a = min((int) $msg->sender_id, (int) $recipient);
            $b = max((int) $msg->sender_id, (int) $recipient);
            $key = "p{$msg->project_id}:{$a}-{$b}";

            DB::table('messages')->where('id', $msg->id)->update([
                'recipient_id' => $recipient,
                'conversation_key' => $key,
            ]);
        }

        Schema::table('messages', function (Blueprint $table) {
            $table->unique(['conversation_key', 'created_at', 'id']);
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            try {
                $table->dropUnique(['conversation_key', 'created_at', 'id']);
            } catch (\Throwable $e) {
            }
            $table->dropColumn(['recipient_id', 'conversation_key']);
        });
    }
};