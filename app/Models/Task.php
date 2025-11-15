<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';
    
    protected $guarded=[];


    public function user_rln()
    {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments_rln()
    {
        return $this->hasMany(Comment::class);
    }



}  