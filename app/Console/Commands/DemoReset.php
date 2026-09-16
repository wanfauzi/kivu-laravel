<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DemoReset extends Command
{
    protected $signature = 'demo:reset {--force : Jalankan tanpa konfirmasi}';

    protected $description = 'Reset database dan muat ulang data demo KIVU (migrate:fresh --seed)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Semua data akan dihapus dan diganti data demo. Lanjutkan?')) {
            $this->warn('Dibatalkan.');

            return Command::SUCCESS;
        }

        $this->info('Mereload database + data demo...');

        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        $this->output->write(Artisan::output());

        $this->newLine();
        $this->info('Selesai. Akun demo (password: password):');
        $this->table(['Role', 'Email'], [
            ['Admin', 'admin@kivu.id'],
            ['UMKM', 'umkm@kivu.id / batik@kivu.id / keripik@kivu.id'],
            ['Mahasiswa', 'talent@kivu.id / sinta@univ.ac.id / adi@univ.ac.id'],
            ['Mahasiswa', 'dewi@gmail.com (terverifikasi)'],
            ['Mahasiswa', 'riko@gmail.com / fitri@gmail.com (pending KTM)'],
        ]);

        if (! file_exists(public_path('storage'))) {
            $this->warn('Symlink storage belum ada — jalankan: php artisan storage:link');
        }

        return Command::SUCCESS;
    }
}
