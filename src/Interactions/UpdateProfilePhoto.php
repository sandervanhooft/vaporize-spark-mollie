<?php
declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie\Interactions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Interactions\Settings\Profile\UpdateProfilePhoto as Base;
use SanderVanHooft\VaporizeSparkMollie\Events\ProfilePhotoUpdated;
use SanderVanHooft\VaporizeSparkMollie\Rules\StorageFile as StorageFileRule;

class UpdateProfilePhoto extends Base
{
    /**
     * {@inheritdoc}
     */
    public function validator($user, array $data)
    {
        return Validator::make($data, [
            'bucket' => ['required', 'string'],
            'key' => [
                'required',
                'string',
                new StorageFileRule(5120, [
                    'image/jpg', 'image/jpeg', 'image/png',
                ]),
            ],
            'content_type' => ['required', 'string'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($user, array $data)
    {
        $targetKey = str_replace('tmp/', 'profiles/original/', $data['key']);

        Storage::copy($data['key'], $targetKey);
        Storage::setVisibility($targetKey, 'public');

        $oldPhotoKey = $user->photo_key;

        $user->forceFill([
            'photo_url' => Storage::url($targetKey),
            'photo_bucket' => $data['bucket'],
            'photo_key' => $targetKey,
            'photo_content_type' => $data['content_type'],
        ])->save();

        event(new ProfilePhotoUpdated($user, $data['bucket'], $targetKey, $data['content_type']));

        try {
            Storage::delete($oldPhotoKey);
        } catch (\Exception $e) {
            //
        }
    }
}
