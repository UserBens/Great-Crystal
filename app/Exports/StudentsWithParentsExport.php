<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


// class StudentsWithParentsExport implements FromArray, WithHeadings
// {
//     public function array(): array
//     {
//         $rows = [];

//         $students = Student::with(['grade', 'relationship'])
//             ->where('is_active', 1)
//             ->get();

//         $gradeOrder = [
//             'toddler' => 1,
//             'nursery' => 2,
//             'kindergarten' => 3,
//             'primary' => 4,
//             'secondary' => 5,
//             'IGCSE' => 6,
//         ];

//         $students = $students->sortBy(function ($student) use ($gradeOrder) {
//             $name = strtolower($student->grade->name ?? '');
//             $order = $gradeOrder[$name] ?? 999;

//             $class = $student->grade->class ?? 0;
//             if (!is_numeric($class)) {
//                 $class = 0;
//             }

//             return $order * 100 + (int) $class;
//         });


//         foreach ($students as $student) {
//             $parents = $student->relationship;

//             $parent1 = $parents[0] ?? null;
//             $parent2 = $parents[1] ?? null;

//             $rows[] = [
//                 'student_name' => $student->name,
//                 'grade' => $student->grade
//                     ? $student->grade->name . ' ' . $student->grade->class
//                     : '-',
//                 'parent1_name' => $parent1?->name ?? '-',
//                 'parent1_email' => $parent1?->email ?? '-',
//                 'parent2_name' => $parent2?->name ?? '-',
//                 'parent2_email' => $parent2?->email ?? '-',
//             ];
//         }

//         return $rows;
//     }

//     public function headings(): array
//     {
//         return [
//             'Student Name',
//             'Grade',
//             'Father Name',
//             'Father Email',
//             'Mother Name',
//             'Mother Email',
//         ];
//     }
// }


class StudentsWithParentsExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        $rows = [];

        $students = Student::with(['grade', 'relationship'])
            ->where('is_active', 1)
            ->get();

        $gradeOrder = [
            'toddler' => 1,
            'nursery' => 2,
            'kindergarten' => 3,
            'primary' => 4,
            'secondary' => 5,
            'igcse' => 6,
        ];

        $students = $students->sortBy(function ($student) use ($gradeOrder) {
            $name = strtolower($student->grade->name ?? '');
            $order = $gradeOrder[$name] ?? 999;

            $class = $student->grade->class ?? 0;
            if (!is_numeric($class)) {
                $class = 0;
            }

            return $order * 100 + (int) $class;
        });

        foreach ($students as $student) {
            $parents = $student->relationship;

            $parent1 = $parents[0] ?? null;
            $parent2 = $parents[1] ?? null;

            $gradeName = '-';
            if ($student->grade) {
                $gradeName = ucfirst($student->grade->name);
                if (!empty($student->grade->class)) {
                    $gradeName .= ' ' . $student->grade->class;
                }
            }

            $rows[] = [
                $student->name,
                $gradeName,
                $parent1?->name ?? '-',
                $parent1?->email ?? '-',
                $parent2?->name ?? '-',
                $parent2?->email ?? '-',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Grade',
            'Father Name',
            'Father Email',
            'Mother Name',
            'Mother Email',
        ];
    }

    // memberi style pada header
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // baris header
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    // set lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 25,
            'D' => 30,
            'E' => 25,
            'F' => 30,
        ];
    }
}
