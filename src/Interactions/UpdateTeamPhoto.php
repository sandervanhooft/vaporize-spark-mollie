<?php
declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie\Interactions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Interactions\Settings\Teams\UpdateTeamPhoto as Base;
use SanderVanHooft\VaporizeSparkMollie\Events\TeamPhotoUpdated;
use SanderVanHooft\VaporizeSparkMollie\Rules\StorageFile as StorageFileRule;

class UpdateTeamPhoto extends Base
{
    /**
     * {@inheritdoc}
     */
    public function validator($team, array $data)
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
    public function handle($team, array $data)
    {
        $targetKey = str_replace('tmp/', 'teams/original/', $data['key']);

        Storage::copy($data['key'], $targetKey);
        Storage::setVisibility($targetKey, 'public');

        $oldPhotoKey = $team->photo_key;

        $team->forceFill([
            'photo_url' => Storage::url($targetKey),
            'photo_bucket' => $data['bucket'],
            'photo_key' => $targetKey,
            'photo_content_type' => $data['content_type'],
        ])->save();

        event(new TeamPhotoUpdated($team, $data['bucket'], $targetKey, $data['content_type']));

        try {
            Storage::delete($oldPhotoKey);
        } catch (\Exception $e) {
            //
        }
    }
}
