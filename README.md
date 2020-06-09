# Run Spark for Mollie on Laravel Vapor

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sandervanhooft/vaporize-spark-mollie.svg?style=flat-square)](https://packagist.org/packages/sandervanhooft/vaporize-spark-mollie)

Running Spark for Mollie requires you to make a few modifications before it fully runs on Laravel Vapor.

The default installation Spark will break the profile photo upload (teams and users) and the invoice pdf download.
This package takes care of that. 
 
## Installation
It is recommended to use this package on a fresh installation of Spark for Mollie.

You can install the package via composer:

```bash
composer require "sandervanhooft/vaporize-spark-mollie:^1.0"
```

Next, install the required files with:

```bash
php artisan vendor:publish --provider="SanderVanHooft\VaporizeSparkMollie\VaporizeSparkMollieServiceProvider" --force
```

Run the migrations. This adds the required field to the users and teams tables.

```bash
php artisan migrate
```

### Important!
Ensure you have wired up the Vapor NPM package as documented [here](https://docs.vapor.build/1.0/resources/storage.html#installing-the-vapor-npm-package).

### Configuration (optional)
You can use the published config file (`config/vaporize-spark-mollie.php`) to swap out the used classes with your own customized ones.

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

This package was inspired by [this thread on Laracasts.com](https://laracasts.com/discuss/channels/spark/spark-profile-photos-on-vapor), and [this great blog post](https://sandulat.com/validating-laravel-vapor-uploads/) by Sandulat.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
