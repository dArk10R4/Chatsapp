<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'from',
        'to',
        'read',
    ];
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to');
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'from');
    }
}
