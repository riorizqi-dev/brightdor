<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationRsvp extends Model
{
    protected $fillable = [
        'invitation_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'attendance',
        'guest_count',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'guest_count' => 'integer',
        ];
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
