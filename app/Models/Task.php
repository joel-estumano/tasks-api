<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'time',
        'user_id',
        'completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
