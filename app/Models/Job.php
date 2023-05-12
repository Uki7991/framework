<?php

namespace App\Models;

class Job extends Model
{
    public const CREATED = 0;
    public const PROCESSING = 1;
    public const COMPLETED = 2;
    public const ERROR = 3;

    protected $table = 'jobs';

    protected array $fillable = [
        'command',
        'status',
    ];

    public function complete()
    {
        $this->update([
            'status' => self::COMPLETED,
        ]);
    }

    public function error()
    {
        $this->update([
            'status' => self::ERROR,
        ]);
    }

    public function processing()
    {
        $this->update([
            'status' => self::PROCESSING,
        ]);
    }
}