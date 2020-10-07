<?php

use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\DB;

class TestCase extends BaseTestCase
{
    protected $app;
    protected $env;
    protected $adminConfig;

    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->register('Chaijiawei\LaravelAdminBackup\ServiceProvider');

        $this->app = $app;
    }

    protected function setUp()
    {
        parent::setUp();

        $this->createApplication();

        $this->adminConfig = $adminConfig = require __DIR__.'/config/admin.php';
        $this->app['config']->set('database.default', 'mysql');
        $this->app['config']->set('database.connections.mysql.host', 'localhost');
        $this->app['config']->set('database.connections.mysql.database', 'laravel_admin_test');
        $this->env = __DIR__ . '/.env.test';
        $this->app['config']->set('database.connections.mysql.username', 'root');
        $this->app['config']->set('database.connections.mysql.password', '');
        if(env('IS_HOMESTEAD') == 1) {
            $this->app['config']->set('database.connections.mysql.username', 'homestead');
            $this->app['config']->set('database.connections.mysql.password', 'secret');
            $this->env = __DIR__ . '/.env.homestead';
        }
        $this->app['config']->set('admin', $adminConfig);

        Artisan::call('vendor:publish', [
                '--provider' => 'Chaijiawei\LaravelAdminBackup\ServiceProvider',
                '--force' => true,
            ]
        );

        $this->copyEnv();
        $this->migrateTestTables();
    }

    protected function migrateTestTables()
    {
        require_once __DIR__ . "/migrations/create_test_tables.php";
        (new CreateTestTables())->up();
    }

    protected function copyEnv()
    {
        $target = base_path('.env');
        if(File::exists($target)) {
            File::delete($target);
        }
        File::copy($this->env, $target);
    }

    protected function tearDown()
    {
        $this->app['config']->set('admin', $this->adminConfig);
        (new CreateTestTables())->down();

        parent::tearDown();
    }

    protected function seedUsersTable()
    {
        $faker = FakerFactory::create();
        DB::table(config('admin.database.users_table'))->truncate();
        DB::table(config('admin.database.users_table'))->insert([
            'username' => $faker->unique()->name,
            'password' => $faker->password,
            'name'     => $faker->name,
        ]);
    }
}