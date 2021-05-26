<h1 align="center"> laravel-admin-backup </h1>

<p align="center"> backup tables for <a target="_blank" href="https://github.com/z-song/laravel-admin">laravel-admin</a>.</p>

[![StyleCI](https://github.styleci.io/repos/301957776/shield?branch=master)](https://github.styleci.io/repos/301957776?branch=master)
[![Build Status](https://travis-ci.org/chaijiawei/laravel-admin-backup.svg?branch=master)](https://travis-ci.org/chaijiawei/laravel-admin-backup)

## Why Write
when I test local project, run command `php artisan migration:refresh`, laravel-admin tables also reset, then data have lose.

so I need to backup and import data for laravel-admin, the plugin is for this state.

## Requirements
* PHP >= 7.0.0
* Laravel >= 5.5.0

## Installing 

```shell
composer require chaijiawei/laravel-admin-backup
```

## Usage

publish assets
```shell
php artisan vendor:publish --provider="Chaijiawei\LaravelAdminBackup\ServiceProvider"
```

backp admin table
```shell
php artisan admin:backup-database
```

import data to admin table
```shell
php artisan admin:import-database
```

## License

MIT