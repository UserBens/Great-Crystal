<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsFullProfileExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        $rows = [];

        $students = Student::with(['grade', 'relationship'])->where('is_active', 1)->get();

        foreach ($students as $student) {
            $father = $student->relationship->firstWhere('relation', 'father');
            $mother = $student->relationship->firstWhere('relation', 'mother');

            $gradeName = '-';
            if ($student->grade) {
                $gradeName = ucfirst($student->grade->name);
                if (!empty($student->grade->class)) {
                    $gradeName .= ' ' . $student->grade->class;
                }
            }

            $rows[] = [
                $student->name,
                $student->nisn ?? '-',
                $student->is_active ? 'Active' : 'Inactive',
                $gradeName,
                $student->gender,
                $student->religion,
                $student->place_birth,
                $student->date_birth,
                $student->nationality,
                $student->id_or_passport,
                $student->created_at,
                $student->place_of_issue,
                $student->date_exp,

                // father
                $father?->name ?? '-',
                $father?->religion ?? '-',
                $father?->place_birth ?? '-',
                $father?->date_birth ?? '-',
                $father?->nationality ?? '-',
                $father?->occupation ?? '-',
                $father?->company_name ?? '-',
                $father?->company_address ?? '-',
                $father?->home_address ?? '-',
                $father?->email ?? '-',
                $father?->telephone ?? '-',
                $father?->mobilephone ?? '-',

                // mother
                $mother?->name ?? '-',
                $mother?->religion ?? '-',
                $mother?->place_birth ?? '-',
                $mother?->date_birth ?? '-',
                $mother?->nationality ?? '-',
                $mother?->occupation ?? '-',
                $mother?->company_name ?? '-',
                $mother?->company_address ?? '-',
                $mother?->home_address ?? '-',
                $mother?->email ?? '-',
                $mother?->telephone ?? '-',
                $mother?->mobilephone ?? '-',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'NISN',
            'Status',
            'Grade',
            'Gender',
            'Religion',
            'Place Birth',
            'Date Birth',
            'Nationality',
            'ID/Passport',
            'Register At',
            'Place of Issue',
            'Expiry ID/Passport',

            'Father Name',
            'Father Religion',
            'Father Place Birth',
            'Father Date Birth',
            'Father Nationality',
            'Father Occupation',
            'Father Company Name',
            'Father Company Address',
            'Father Home Address',
            'Father Email',
            'Father Telephone',
            'Father Mobilephone',

            'Mother Name',
            'Mother Religion',
            'Mother Place Birth',
            'Mother Date Birth',
            'Mother Nationality',
            'Mother Occupation',
            'Mother Company Name',
            'Mother Company Address',
            'Mother Home Address',
            'Mother Email',
            'Mother Telephone',
            'Mother Mobilephone',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 10,
            'D' => 15,
            'E' => 10,
            'F' => 20,
            // lanjutkan untuk kolom lain kalau mau
        ];
    }
}
