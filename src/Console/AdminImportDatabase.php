<?php

namespace Chaijiawei\LaravelAdminBackup\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminImportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:import-database
                        {--file=database/admin.sql : sql file path, use base_path(file)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import sql data to admin table';

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
        $shell = base_path('import_admin.sh');
        $sqlFile = base_path($this->option('file'));
        if(! file_exists($shell)) {
            $this->warn('php artisan vendor:publish to publish shell');
            return;
        }
        if(! file_exists($sqlFile)) {
            $this->warn( "you have no $sqlFile to import");
            return;
        }
        if(! config('admin.database.users_table')) {
            $this->warn('laravel-admin config not find');
            return;
        }
        if(DB::table(config('admin.database.users_table'))->count() > 0) {
            $isForce = $this->confirm('admin table already have data, force cover？');
            if(! $isForce) {
                $this->info('nothing to import');
                return;
            }

            //truncate admin table except log table
            $tables = collect(config('admin.database'))->filter(function($v, $k) {
                return Str::endsWith($k, 'table') &&
                        ! Str::endsWith($v, 'log');
            })->all();
            foreach($tables as $table) {
                DB::table($table)->truncate();
            }
        }

        $dir = base_path();
        system("cd $dir; bash $shell $sqlFile");
        $this->info('import success');
    }
}
