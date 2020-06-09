# Run Spark for Mollie on Laravel Vapor

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sandervanhooft/vaporize-spark-mollie.svg?style=flat-square)](https://packagist.org/packages/sandervanhooft/vaporize-spark-mollie)

Running Spark for Mollie requires you to make a few modifications before it fully runs on Laravel Vapor.
The default installation will break the profile photo upload (teams and users) and the invoice pdf download.
This package takes care of that. It is recommended to use this package on a fresh installation of Spark for Mollie. 
 
## Installation

You can install the package via composer:

```bash
composer require sandervanhooft/vaporize-spark-mollie
```

Next, install the required files with:

```bash
php artisan vendor:publish --provider="SanderVanHooft\VaporizeSparkMollie\VaporizeSparkMollieServiceProvider" --force
```

Finally, run the migrations. This adds the required field to the users and teams tables.

```bash
php artisan migrate
```

Optionally, use the published config file (`config/vaporize-spark-mollie.php`) to swap out the used classes with your own customized ones.
This is what's in the config file:

```php
return [
    /**
     * These custom classes override the default Spark InvoiceController classes.
     */
    'user_invoice_controller' => UserInvoiceController::class,
    'team_invoice_controller' => TeamInvoiceController::class,

    /**
     * These custom classes override the default Spark UpdateProfilePhoto and UpdateTeamPhoto interactions.
     */
    'user_update_photo_interaction' => UpdateProfilePhoto::class,
    'team_update_photo_interaction' => UpdateTeamPhoto::class,
];
```

## Changelog

Please see the [releases](https://www.github.com/sandervanhooft/vaporize-spark-mollie/releases) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email info@sandervanhooft.com instead of using the issue tracker.

## Credits

- [Sander van Hooft](https://github.com/sandervanhooft)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
