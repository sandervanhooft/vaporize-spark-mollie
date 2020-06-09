<?php

namespace SanderVanHooft\VaporizeSparkMollie\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoUpdated
{
    use SerializesModels;

    /**
     * @var \App\User
     */
    public $user;

    /**
     * @var string
     */
    public $bucket;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $contentType;

    /**
     * Create a new event instance.
     *
     * @param \App\User $user
     * @param string $bucket
     * @param string $key
     * @param string $contentType
     */
    public function __construct(User $user, string $bucket, string $key, string $contentType)
    {
        $this->user = $user;
        $this->bucket = $bucket;
        $this->key = $key;
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function file()
    {
        return Storage::get($this->key);
    }
}
