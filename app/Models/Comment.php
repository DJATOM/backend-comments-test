<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $with = ['replies'];

    protected $fillable = [
        'content',
        'comment_id'
    ];

    public function scopeRoot($query): void
    {
        $query->whereNull('comment_id');
    }

    public function replies(): hasMany
    {
        return $this->hasMany(Comment::class);
    }
}
