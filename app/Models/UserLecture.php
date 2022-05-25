<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User_Lecture extends Model
{
    use HasFactory;
    public $fillable = [
        'lecture_id',
        'user_id',
        'isTeacher'
    ];

    public function enrollLecture(): HasOne {
        return $this->hasOne(Lecture::class);
    }
    public function userEnroll(): HasOne {
        return $this->hasOne(User::class);
    }
}
