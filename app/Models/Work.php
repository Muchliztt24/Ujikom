<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Work extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'original_author', 'description', 'cover', 'type', 'status', 'user_id'];

    protected $appends = ['display_author'];

    // Relasi ke uploader
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function readingHistories()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function getDisplayAuthorAttribute(): ?string
    {
        return $this->original_author ?: $this->user?->name;
    }
}
