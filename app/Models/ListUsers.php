<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListUsers extends Model
{
    use HasFactory;
    public $fillable = [
        'title',
        'creator',
        'emails_list',
    ];
}
