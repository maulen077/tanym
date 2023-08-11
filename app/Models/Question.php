<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
      'cat_id',
      'text',
      'image',
      'video',
      'type'
    ];

    public function matches()
    {
        return $this->hasMany(Matches::class, 'question_id');
    }
}
