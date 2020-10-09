<?php

namespace Chaijiawei\LaravelAdminBackup;

use Chaijiawei\LaravelAdminBackup\Console\AdminBackupDatabase;
use Chaijiawei\LaravelAdminBackup\Console\AdminImportDatabase;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/backup_admin.sh' => base_path('backup_admin.sh'),
            __DIR__.'/import_admin.sh' => base_path('import_admin.sh'),
        ]);

        $this->commands([
            AdminBackupDatabase::class,
            AdminImportDatabase::class,
        ]);
    }
}
