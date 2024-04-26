<?php

namespace App\Repositories;

use App\Models\File;
use App\Support\Repositories\BaseRepository;

class FileRepository extends BaseRepository
{
    protected $model = File::class;
}
