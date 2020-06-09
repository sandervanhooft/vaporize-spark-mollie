<?php

namespace SanderVanHooft\VaporizeSparkMollie;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use SanderVanHooft\VaporizeSparkMollie\Events\ProfilePhotoResized;
use SanderVanHooft\VaporizeSparkMollie\Events\ProfilePhotoUpdated;

class ResizeProfilePhoto implements ShouldQueue
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
     * @param \SanderVanHooft\VaporizeSparkMollie\Events\ProfilePhotoUpdated $event
     * @return void
     */
    public function handle(ProfilePhotoUpdated $event)
    {
        $oldKey = $event->key;
        $user = $event->user;
        $oldUrl = $user->photo_url;

        $targetKey = str_replace('profiles/original/', 'profiles/300x300/', $oldKey);

        Storage::put(
            $targetKey,
            Image::make($event->file())->fit(300)->encode(null, 85)
        );

        Storage::setVisibility($targetKey, 'public');

        $user->forceFill([
            'photo_url' => Storage::url($targetKey),
            'photo_key' => $targetKey,
        ])->save();

        event(new ProfilePhotoResized($user, $oldKey, $oldUrl));
    }
}
