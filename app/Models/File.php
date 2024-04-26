<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'upload_path',
        'original_name',
        'extension',
        'name'
    ];

    public function serializeTime(): string
    {
        return Carbon::instance($this->created_at)->translatedFormat('l, d F Y H:i');
    }
}
