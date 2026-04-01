<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SendoraSetting extends Model
{
    protected $fillable = [
        'api_url',
        'api_token',
        'sender_number',
        'is_active',
        'timeout',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'timeout' => 'integer',
    ];

    protected $hidden = ['api_token'];

    public function setApiTokenAttribute($value)
    {
        $this->attributes['api_token'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getApiTokenAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function current(): ?self
    {
        return static::first();
    }
}
