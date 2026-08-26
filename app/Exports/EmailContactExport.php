<?php

namespace App\Exports;

use App\Models\EmailContact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmailContactExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return EmailContact::latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Perusahaan',
            'Status Berlangganan',
            'Tanggal Dibuat',
        ];
    }

    public function map($contact): array
    {
        return [
            $contact->id,
            $contact->name,
            $contact->email,
            $contact->company ?: '-',
            $contact->is_subscribed ? 'Subscribed' : 'Unsubscribed',
            $contact->created_at ? $contact->created_at->format('Y-m-d H:i:s') : '-',
        ];
    }
}
