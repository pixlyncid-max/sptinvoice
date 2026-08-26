<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'is_subscribed',
        'unsubscribe_token',
    ];

    protected $casts = [
        'is_subscribed' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($contact) {
            if (empty($contact->unsubscribe_token)) {
                $contact->unsubscribe_token = Str::random(32);
            }
        });
    }

    public function campaignRecipients()
    {
        return $this->hasMany(EmailCampaignRecipient::class, 'contact_id');
    }

    public function getUnsubscribeUrlAttribute()
    {
        return route('email-marketing.unsubscribe', ['token' => $this->unsubscribe_token ?: 'default']);
    }
}
