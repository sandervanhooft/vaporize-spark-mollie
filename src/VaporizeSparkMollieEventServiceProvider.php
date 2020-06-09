<?php
declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use SanderVanHooft\VaporizeSparkMollie\Events\ProfilePhotoUpdated;
use SanderVanHooft\VaporizeSparkMollie\Events\TeamPhotoUpdated;

class VaporizeSparkMollieEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProfilePhotoUpdated::class => [ ResizeProfilePhoto::class ],
        TeamPhotoUpdated::class => [ ResizeTeamPhoto::class ],
    ];
}
