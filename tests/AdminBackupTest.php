<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Tester\CommandTester;
use Chaijiawei\LaravelAdminBackup\Console\AdminBackupDatabase;
use Illuminate\Support\Facades\DB;

class AdminBackupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testNoShellFile()
    {
        File::delete(base_path('backup_admin.sh'));
        Artisan::call('admin:backup-database');
        $this->assertSame('php artisan vendor:publish to publish shell', trim(Artisan::output()));
    }

    public function testNoConfig()
    {
        unset($this->app['config']['admin']);
        Artisan::call('admin:backup-database');
        $this->assertSame('laravel-admin config not find', trim(Artisan::output()));
    }

//    public function testTableIsEmpty()
//    {
//        DB::table(config('admin.database.users_table'))->truncate();
//        $consoleCommand = new AdminBackupDatabase;
//        $consoleCommand->setLaravel($this->app);
//        $testCommand = new CommandTester($consoleCommand);
//        $testCommand->setInputs(['no']);
//        $testCommand->execute([]);
//
//        $output = $testCommand->getDisplay();
//        $this->assertRegExp('/.*?nothing to backup.*?/', $output);
//    }

    public function testTableHaveData()
    {
        $this->seedUsersTable();
        Artisan::call('admin:backup-database');
        $this->assertSame('backup success', trim(Artisan::output()));
    }

    public function testParameter()
    {
        $this->seedUsersTable();
        $sqlName = 'test.sql';
        $file = base_path($sqlName);
        File::delete($file);
        Artisan::call('admin:backup-database', ["--file" => $sqlName]);
        $this->assertTrue(File::exists($file));
    }
}