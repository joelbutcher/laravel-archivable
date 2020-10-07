# Laravel Archivable

<p align="center">
<a href="https://github.com/joelbutcher/laravel-archivable/actions"><img src="https://github.com/joelbutcher/laravel-archivable/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/joelbutcher/laravel-archivable"><img src="https://img.shields.io/packagist/dt/joelbutcher/laravel-archivable" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/joelbutcher/laravel-archivable"><img src="https://img.shields.io/packagist/v/joelbutcher/laravel-archivable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/joelbutcher/laravel-archivable"><img src="https://img.shields.io/packagist/l/joelbutcher/laravel-archivable" alt="License"></a>
</p>

A simple package for making Laravel Eloquent models 'archivable'. This package registers three macros. One for Laravel's `\Illuminate\Database\Schema\Blueprint` class for writing migrations with the `archivedAt` directive. Two more macros are registered for Laravel's `\Illuminate\Database\Query\Builder` class, allowing calls to `archive` and `unArchive` between Eloquent relationships. 

## Installation

You can install the package via composer:

```bash
composer require joelbutcher/laravel-archivable
```

## Usage

Typically, the trait works similarly to Laravels `SoftDeletes` trait. Therefore, any model that has an `archived_at` column in it's table schema can utilise the `\LaravelArchivable\Archivable` trait.
 
``` php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class ArchivablePost extends Model {

    use Archivable;
    ...
}
```

### Testing

Currently, a test suite doesn't exist for this package. However, I will be writing tests in line with Laravel's testing schemes for traits. If you wish to contribute any unit tests of your own, please refer to the [contribution guides](#-contributing) below 

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email joel@joelbutcher.co.uk instead of using the issue tracker.

## Credits

- [Joel Butcher](https://github.com/joelbutcher)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
