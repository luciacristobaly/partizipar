<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'dateTime',
    ];
}
