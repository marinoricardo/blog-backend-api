<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'author_id',
        'article_id'

    ];

    // public function author(){
    //     return $this->belongsTo(User::class);
    // }

    public function article(){
        return $this->belongsTo(Article::class);
    }
}
