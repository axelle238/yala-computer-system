<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeArticle extends Model
{
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Alias untuk relasi author (Indonesian).
     */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
