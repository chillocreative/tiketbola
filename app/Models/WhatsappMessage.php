<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    protected $fillable = [
        'phone',
        'message',
        'status',
        'response',
        'error',
        'submission_id',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
