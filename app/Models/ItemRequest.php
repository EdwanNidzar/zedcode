<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    protected $fillable = [
        'user_id', 'item_name', 'quantity', 'reason', 'admin_notes', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
