<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Chaijiawei\LaravelAdminBackup\Console\AdminImportDatabase;
use Symfony\Component\Console\Tester\CommandTester;
use Illuminate\Support\Facades\DB;

class AdminImportTest extends TestCase
{
    public function testNoShellFile()
    {
        File::delete(base_path('import_admin.sh'));
        Artisan::call('admin:import-database');
        $this->assertSame('php artisan vendor:publish to publish shell', trim(Artisan::output()));
    }

    public function testNoSqlFile()
    {
        $sqlFile = base_path('database/admin.sql');
        File::delete($sqlFile);
        Artisan::call('admin:import-database');
        $this->assertSame("you have no $sqlFile to import", trim(Artisan::output()));
    }

    public function testNoConfig()
    {
        //first backup
        $this->seedUsersTable();
        Artisan::call('admin:backup-database');

        unset($this->app['config']['admin']);
        Artisan::call('admin:import-database');
        $this->assertSame('laravel-admin config not find', trim(Artisan::output()));
    }

//    public function testTableHaveData()
//    {
//        $this->seedUsersTable();
//        $consoleCommand = new AdminImportDatabase;
//        $consoleCommand->setLaravel($this->app);
//        $testCommand = new CommandTester($consoleCommand);
//
//        //not to force import, and exit
//        $testCommand->setInputs(['no']);
//        $testCommand->execute([]);
//        $output = $testCommand->getDisplay();
//        $this->assertRegExp('/.*?nothing to import.*?/', $output);
//
//        //force import
//        $testCommand->setInputs(['yes']);
//        $testCommand->execute([]);
//        $output = $testCommand->getDisplay();
//        $this->assertRegExp('/.*?import success.*?/', $output);
//    }

    public function testParameter()
    {
        $sqlName = 'test.sql';
        $file = base_path($sqlName);
        File::delete($file);

        //first backup
        $this->seedUsersTable();
        Artisan::call('admin:backup-database', ["--file" => $sqlName]);
        $this->assertSame('backup success', trim(Artisan::output()));

        //clear table
        DB::table(config('admin.database.users_table'))->truncate();

        //import
        Artisan::call('admin:import-database', ["--file" => $sqlName]);
        $this->assertSame('import success', trim(Artisan::output()));
    }

    public function testImport()
    {
        //backup
        $this->seedUsersTable();
        Artisan::call('admin:backup-database');
        $this->assertSame('backup success', trim(Artisan::output()));

        //import
        DB::table(config('admin.database.users_table'))->truncate();
        Artisan::call('admin:import-database');
        $this->assertSame('import success', trim(Artisan::output()));
    }
}