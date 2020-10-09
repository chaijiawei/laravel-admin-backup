<?php

namespace Chaijiawei\LaravelAdminBackup\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AdminBackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:backup-database
                        {--file=database/admin.sql : sql file path, use base_path(file)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup admin table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shell = base_path('backup_admin.sh');
        if (! file_exists($shell)) {
            $this->warn('php artisan vendor:publish to publish shell');

            return;
        }
        if (! config('admin.database.users_table')) {
            $this->warn('laravel-admin config not find');

            return;
        }
        if (DB::table(config('admin.database.users_table'))->count() <= 0) {
            $isForce = $this->confirm('admin table is empty, are you backup？');
            if (! $isForce) {
                $this->info('nothing to backup');

                return;
            }
        }

        $sqlFile = base_path($this->option('file'));
        $dir = base_path();
        system("cd $dir; bash $shell $sqlFile");
        $this->info('backup success');
    }
}
