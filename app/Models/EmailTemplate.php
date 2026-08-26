<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function campaigns()
    {
        return $this->hasMany(EmailCampaign::class, 'template_id');
    }

    /**
     * Render template with replaced variables
     */
    public function render(array $data = []): array
    {
        $subject = $this->subject ?? '';
        $body = $this->body ?? '';

        $variables = [
            '{{name}}' => $data['name'] ?? '',
            '{{email}}' => $data['email'] ?? '',
            '{{company}}' => $data['company'] ?? '',
            '{{unsubscribe_url}}' => $data['unsubscribe_url'] ?? '#',
        ];

        $renderedSubject = str_replace(array_keys($variables), array_values($variables), $subject);
        $renderedBody = str_replace(array_keys($variables), array_values($variables), $body);

        return [
            'subject' => $renderedSubject,
            'body' => $renderedBody,
        ];
    }
}
