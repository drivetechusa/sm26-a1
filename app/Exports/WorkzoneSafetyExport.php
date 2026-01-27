<?php

namespace App\Exports;

use App\Models\Seminar;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class WorkzoneSafetyExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    private $fileName = 'WorkzoneSafetyExport.xlsx';
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct($start,$end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $collection = new Collection();
        $seminars = Seminar::whereBetween('date', [$this->start, $this->end])->get();

        foreach ($seminars as $seminar) {
            $students = $seminar->students;
            foreach ($students as $student) {
                $collection->push([
                    'date' => $seminar->date,
                    'school_name' => Str::upper(config('app.school_name')),
                    'employee_name' => $seminar->employee->firstname . ' ' . $seminar->employee->lastname,
                    'student_name' => $student->firstname . ' ' . $student->lastname,
                    'permit_number' => $student->permit_number ?: ''
                ]);
            }
        }
        //dd($collection);
        return $collection;
    }

    public function headings(): array
    {
        return [
            'Date',
            'School Name',
            'Instructor Name',
            'Student Name',
            'Permit Number',
        ];
    }

    public function columnFormats()
    {
        return  [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function map($row): array
    {
        return [
            Date::dateTimeToExcel($row['date']->startOfDay() ),
            $row['school_name'],
            $row['employee_name'],
            $row['student_name'],
            $row['permit_number'],
        ];
    }
}

