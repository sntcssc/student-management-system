<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FeeExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Programme Name',
            'Batch',
            'Application No.',
            'Roll No.',
            'Student ID',
            'Section',
            'First Name',
            'Last Name',
            'Full Name',
            'DOB',
            'Email',
            'Mobile No.',
            'WhatsApp No.',
            'Gender',
            'Category',
            'Transaction Type',
            'Direction',
            'Amount',
            'Fee Month',
            'Description',
            'Payment Method',
            'Reference No.',
            'Attachment Path',
            'Note',
            'Performed By',
            'Remarks',
            'Status',
            'Transaction Date',
            'Created At',
        ];
    }

    public function map($result): array
    {
        $base_url = 'https://admission.sntcssc.in/public/storage/';
        
        return [
            $result->id,
            $result->programme_name ?? 'NA',
            $result->batch ?? 'NA',
            $result->application_number ?? 'NA',
            $result->roll_no ?? 'NA',
            $result->student_id ?? 'NA',
            $result->section ?? 'NA',
            Str::upper($result->first_name ?? 'NA'),
            Str::upper($result->last_name ?? 'NA'),
            Str::upper($result->first_name . ' ' . $result->last_name),
            $result->dob ? Carbon::parse($result->dob)->format('Y-m-d') : 'NA',
            $result->email ?? 'NA',
            $result->mobile_no ?? 'NA',
            $result->whatsapp_no ?? 'NA',
            $result->gender ?? 'NA',
            $result->category ?? 'NA',
            $result->transaction_type ?? 'NA',
            $result->direction ?? 'NA',
            $result->amount ?? 0,
            $result->fee_month ? Carbon::parse($result->fee_month)->format('F Y') : 'N/A',
            $result->description ?? 'NA',
            $result->payment_method ?? 'NA',
            "'".$result->reference_no ?? 'NA',
            $base_url . $result->attachment_path,
            $result->note ?? 'NA',
            $result->performed_by ?? 'NA',
            $result->remarks ?? 'NA',
            $result->status ?? 'Pending',
            $result->transaction_date ? $result->transaction_date->format('Y-m-d') : 'NA',
            $result->created_at ? $result->created_at->format('Y-m-d H:i:s') : 'NA',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50']
                ]
            ],
            'A1:AD' . ($this->results->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]
        ];
    }
}