# Laravel Cake 1.3 Hash

[![Build Status](https://travis-ci.org/matriphe/laravel-cake13-hash.svg?branch=master)](https://travis-ci.org/matriphe/laravel-cake13-hash)
[![Total Download](https://img.shields.io/packagist/dt/matriphe/laravel-cake13-hash.svg)](https://packagist.org/packages/matriphe/laravel-cake13-hash)
[![Latest Stable Version](https://img.shields.io/packagist/v/matriphe/laravel-cake13-hash.svg)](https://packagist.org/packages/matriphe/laravel-cake13-hash)

This package is used to replace Laravel's default hash to support legacy CakePHP 1.3 password hash method. 

Please use it with your own risk, since CakePHP 1.3 is obselete.

## Installation

Using [Composer](https://getcomposer.org/), just run this command below.

```bash
composer require matriphe/laravel-cake13-hash
```

## Configuration

After installed, open `config/app.php` and find this line.

```php
Illuminate\Hashing\HashServiceProvider::class::class
``` 

Comment or remove that line and add this line to override Laravel's hash handling.

```php
Matriphe\Md5Hash\HashServiceProvider::class
```

Publish config file to hold salt string in config using `php artisan vendor:publish` command. This command will create `config/cake.php` file.

Open your `app/config/core.php` in your old CakePHP 1.3 directory, find `Configure::write('Security.salt', 'S0m3S4lt');` and copy the salt.

## Usage

Now you can use built in hash function using this command.

```php
Hash::make('password'); // return 94c5b9c5a0d799d938fdad02162ce27651bf81eb
bcrypt('password'); // return 94c5b9c5a0d799d938fdad02162ce27651bf81eb
```
 
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.