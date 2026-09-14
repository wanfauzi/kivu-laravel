<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process as SymfonyProcess;

class DbBackup extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Backup the database using mysqldump';

    public function handle(): int
    {
        $database = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $filename = $database . '-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('backups/' . $filename);

        $this->line("Membuat backup database...");

        $command = [
            'mysqldump',
            '--host=' . $host,
            '--port=' . $port,
            '--user=' . $username,
            '--password=' . $password,
            '--single-transaction',
            '--routines',
            '--triggers',
            $database,
        ];

        $process = SymfonyProcess::fromShellCommandLine(implode(' ', $command));
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Gagal membuat backup: ' . $process->getErrorOutput());

            return Command::FAILURE;
        }

        file_put_contents($path, $process->getOutput());

        $size = filesize($path);
        $sizeFormatted = $this->formatBytes($size);

        $this->newLine();
        $this->info("Backup berhasil!");
        $this->table(['File', 'Ukuran', 'Lokasi'], [[$filename, $sizeFormatted, $path]]);

        return Command::SUCCESS;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
