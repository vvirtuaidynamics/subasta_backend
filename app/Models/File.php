<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class File extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'path', 'size', 'preview'];
    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return Storage::url($this->path);
    }


}
