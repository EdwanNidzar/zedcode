<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handbook extends Model
{
    protected $fillable = [
        'title', 'content', 'attachment_path', 'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
