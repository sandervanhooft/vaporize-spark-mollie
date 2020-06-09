<?php

namespace SanderVanHooft\VaporizeSparkMollie\Events;

use App\Team;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TeamPhotoResized
{
    use SerializesModels;

    /**
     * @var \App\Team
     */
    public $team;

    /**
     * @var string
     */
    public $oldKey;

    /**
     * @var string
     */
    public $oldUrl;

    /**
     * Create a new event instance.
     *
     * @param \App\Team $team
     * @param string $oldKey
     * @param string $oldUrl
     */
    public function __construct(Team $team, string $oldKey, string $oldUrl)
    {
        $this->team = $team;
        $this->oldKey = $oldKey;
        $this->oldUrl = $oldUrl;
    }

    /**
     * @return string
     */
    public function oldFile()
    {
        return Storage::get($this->oldKey);
    }
}
