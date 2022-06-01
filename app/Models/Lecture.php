<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lecture extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'String';

    public $fillable = [
        'id',
        'title',
        'photoName'
    ];
    
    public function enroll(): BelongsToMany {
        return $this->belongsToMany(UserLecture::class);
    }


}
