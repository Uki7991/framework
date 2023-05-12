<?php

namespace Src\App\Models;

use App\Models\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'name',
        'file_path',
        'file_original_name',
        'retry_id',
        'result',
    ];
}