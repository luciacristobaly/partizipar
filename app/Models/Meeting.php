<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'String';
    public $fillable = [
        'id', 
        'title',
        'photoName',
        'body',
        'lectureId',
        'dateTime',
    ];
}
