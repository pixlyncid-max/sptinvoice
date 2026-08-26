<?php

namespace App\Imports;

use App\Models\EmailContact;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmailContactImport implements ToModel, WithHeadingRow
{
    private $importedCount = 0;
    private $skippedCount = 0;

    public function model(array $row)
    {
        $name = trim($row['nama'] ?? $row['name'] ?? '');
        $email = strtolower(trim($row['email'] ?? ''));
        $company = trim($row['perusahaan'] ?? $row['company'] ?? '');
        $subscribedRaw = strtolower(trim($row['subscribed_yatidak'] ?? $row['subscribed'] ?? $row['is_subscribed'] ?? 'ya'));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->skippedCount++;
            return null;
        }

        if (empty($name)) {
            $name = explode('@', $email)[0];
        }

        $isSubscribed = !in_array($subscribedRaw, ['tidak', 'no', 'false', '0', 'unsubscribed']);

        // Check for existing contact by email
        $existing = EmailContact::where('email', $email)->first();
        if ($existing) {
            $this->skippedCount++;
            return null;
        }

        $this->importedCount++;

        return new EmailContact([
            'name' => $name,
            'email' => $email,
            'company' => !empty($company) ? $company : null,
            'is_subscribed' => $isSubscribed,
            'unsubscribe_token' => Str::random(32),
        ]);
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
