<?php

namespace SanderVanHooft\VaporizeSparkMollie;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use SanderVanHooft\VaporizeSparkMollie\Events\TeamPhotoResized;
use SanderVanHooft\VaporizeSparkMollie\Events\TeamPhotoUpdated;

class ResizeTeamPhoto implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \SanderVanHooft\VaporizeSparkMollie\Events\TeamPhotoUpdated $event
     * @return void
     */
    public function handle(TeamPhotoUpdated $event)
    {
        $oldKey = $event->key;
        $team = $event->team;
        $oldUrl = $team->photo_url;

        $targetKey = str_replace('teams/original/', 'teams/300x300/', $oldKey);

        Storage::put(
            $targetKey,
            Image::make($event->file())->fit(300)->encode(null, 85)
        );

        Storage::setVisibility($targetKey, 'public');

        $team->forceFill([
            'photo_url' => Storage::url($targetKey),
            'photo_key' => $targetKey,
        ])->save();

        event(new TeamPhotoResized($event->team, $oldKey, $oldUrl));
    }
}
