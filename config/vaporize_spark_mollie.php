<?php
declare(strict_types=1);

use SanderVanHooft\VaporizeSparkMollie\Http\Controllers\TeamInvoiceController;
use SanderVanHooft\VaporizeSparkMollie\Http\Controllers\UserInvoiceController;
use SanderVanHooft\VaporizeSparkMollie\Interactions\UpdateProfilePhoto;
use SanderVanHooft\VaporizeSparkMollie\Interactions\UpdateTeamPhoto;

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
