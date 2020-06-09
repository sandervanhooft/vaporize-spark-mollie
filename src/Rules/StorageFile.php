<?php

declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class StorageFile implements Rule
{
    /**
     * Max filesize in bytes.
     *
     * @var int
     */
    public int $maxSize;

    /** @var string[] */
    public array $mimes;

    /** @var string */
    protected $message = 'Invalid file.';

    /**
     * @param int $maxSize
     * @param string[] $mimes
     */
    public function __construct(int $maxSize, array $mimes)
    {
        $this->maxSize = $maxSize * 1024; // convert kb to bytes
        $this->mimes = $mimes;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (! Str::startsWith($value, 'tmp/')) {
            return false;
        }

        if (! Storage::exists($value)) {
            return false;
        }

        if (Storage::size($value) > $this->maxSize) {
            $this->message = 'File size exceeds ' . $this->maxSize . 'kB.';

            return false;
        }

        if (!in_array(Storage::mimeType($value), $this->mimes)) {
            $this->message = 'Wrong file type.';

            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message;
    }
}
