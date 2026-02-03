<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'chapter_id',
        'image_url',
        'page_number',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
