<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'template_id',
        'attachment_path',
        'attachment_name',
        'status',
        'total_recipients',
        'sent_count',
        'failed_count',
        'created_by',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recipients()
    {
        return $this->hasMany(EmailCampaignRecipient::class, 'campaign_id');
    }

    public function logs()
    {
        return $this->hasMany(EmailLog::class, 'campaign_id');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_recipients == 0) {
            return 0;
        }
        $processed = $this->sent_count + $this->failed_count;
        return round(($processed / $this->total_recipients) * 100);
    }
}
