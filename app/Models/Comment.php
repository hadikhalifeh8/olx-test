<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    
    protected $guarded=[];

    


    public function user_rln()
    {
        return $this->belongsTo(User::class);
    }

    public function task_rln()
    {
        return $this->belongsTo(Task::class);
    }
}
