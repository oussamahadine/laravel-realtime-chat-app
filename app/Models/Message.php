<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    public $fillable = ['user_from', 'user_to', 'message'];

    public function user_from() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_from');
    }

    public function user_to() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_to');
    }
}
