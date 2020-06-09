<?php
declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie;

use Illuminate\Support\ServiceProvider;

class VaporizeSparkMollieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishables();
        $this->overrideInvoiceControllers();
        $this->overrideProfilePhotoUpload();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/vaporize_spark_mollie.php',
            'vaporize_spark_mollie'
        );
    }

    /**
     * Register anything that can be published from the console.
     *
     * @return void
     */
    protected function registerPublishables()
    {
        $this->publishes([
            __DIR__.'/../config/vaporize_spark_mollie.php' => config_path('vaporize_spark_mollie.php'),
        ], 'config');

        if (! class_exists('AddVaporizedPhotoUploadFields')) {
            $this->publishes([
                __DIR__.'/../database/migrations/add_vaporized_photo_upload_fields.php.stub' =>
                    database_path(
                        'migrations/'.date('Y_m_d_His', time()).'_add_vaporized_photo_upload_fields.php'
                    ),
            ], 'migrations');
        }

        $updateProfilePhotoPath = 'js/spark-components/settings/profile/update-profile-photo.js';
        $updateTeamPhotoPath = 'js/spark-components/settings/teams/update-team-photo.js';

        $this->publishes([
            __DIR__.'/../resources/'.$updateProfilePhotoPath => resource_path($updateProfilePhotoPath),
            __DIR__.'/../resources/'.$updateTeamPhotoPath => resource_path($updateTeamPhotoPath),
        ], 'js');

        $this->publishes([
            __DIR__.'/../install-stubs/app/Policies/UserPolicy.php.stub' =>
                  app_path('Policies/UserPolicy.php'),
        ], 'policies');
    }

    /**
     * Override the invoice controller.
     *
     * @return void
     */
    protected function overrideInvoiceControllers() {
        $this->app->bind(
            \Laravel\Spark\Http\Controllers\Settings\Billing\InvoiceController::class,
            config('vaporize_spark_mollie.user_invoice_controller')
        );

        $this->app->bind(
            \Laravel\Spark\Http\Controllers\Settings\Teams\Billing\InvoiceController::class,
            config('vaporize_spark_mollie.team_invoice_controller')
        );
    }

    /**
     * Override the ProfilePhoto controller's upload features.
     *
     * @return void
     */
    protected function overrideProfilePhotoUpload()
    {
        $this->app->bind(
            \Laravel\Spark\Contracts\Interactions\Settings\Profile\UpdateProfilePhoto::class,
            config('vaporize_spark_mollie.user_update_photo_interaction')
        );

        $this->app->bind(
            \Laravel\Spark\Contracts\Interactions\Settings\Teams\UpdateTeamPhoto::class,
            config('vaporize_spark_mollie.team_update_photo_interaction')
        );
    }
}
