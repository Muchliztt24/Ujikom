<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'title',
        'chapter_number',
        'text_content',
    ];

    // 🔗 Chapter milik satu Work
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    // 🔗 Chapter punya banyak Image
    public function images()
    {
        return $this->hasMany(ChapterImage::class);
    }
}
