<h1 align="center"> laravel-admin-backup </h1>

<p align="center"> laravel admin backup for <a target="_blank" href="https://github.com/z-song/laravel-admin">laravel-admin</a>.</p>


## Installing

```shell
$ composer require chaijiawei/laravel-admin-backup
```

## Usage

publish assets
```shell
$ php artisan vendor:publish --provider="Chaijiawei\LaravelAdminBackup\ServiceProvider"
```

backp admin table
```shell
$ php artisan admin:backup-database
```

import data to admin table
```shell
$ php artisan admin:import-database
```

## License

MIT