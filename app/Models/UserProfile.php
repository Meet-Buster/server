<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['avatar'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function getAvatarAttribute($value)
    {
        return asset('storage/' . $value);
    }
}