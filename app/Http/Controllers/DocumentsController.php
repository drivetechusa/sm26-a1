<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Scheduler\Lesson;
use App\Models\Seminar;
use App\Models\Student;
use App\Traits\PDF_forms;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    use PDF_forms;

    public function sc_activity(Student $student)
    {
        $pdf = $this->sc_activity_log($student);
        $pdf->Output();
    }

    public function print_quarterly_report(Request $request)
    {
        $pdf = $this->quarterly_income_report($request['quarter'], $request['year']);
        $pdf->Output();
    }

    public function print_workzone_report(Request $request)
    {
        $pdf = $this->workzone_report($request['start_date'], $request['end_date']);
        $pdf->Output();
    }

    public function print_yearly_report(Request $request)
    {
        $pdf = $this->yearly_income_report($request['year']);
        $pdf->Output();
    }

    public function print_yearly_zipcode_report(Request $request)
    {
        $pdf = $this->yearly_zipcode_report($request['year']);
        $pdf->Output();
    }

    public function print_account_statement(Student $student)
    {
        $pdf = $this->account_statement($student);
        $pdf->Output();
    }

    public function print_completion_certificate(Student $student)
    {
        $pdf = $this->completion_cert($student);
        $pdf->Output();
    }

    public function print_instructor_certificate(Student $student)
    {
        $pdf = $this->instructor_certificate($student);
        $pdf->Output();
    }

    public function print_coversheet(Student $student)
    {
        $pdf = $this->coversheet($student);
        $pdf->Output();
    }

    public function print_class_coversheets(Seminar $seminar)
    {
        $pdf = $this->class_coversheets($seminar);
        $pdf->Output();
    }

    public function print_contract(Student $student, Seminar $seminar = null, $data = null)
    {
        $contract = $this->selectContract($student, $seminar);

        $contract->Output();
    }

    public function print_class_contracts(Seminar $seminar)
    {
        $contracts = $this->class_contracts($seminar);
        $contracts->Output();
    }

    public function print_roster(Seminar $seminar)
    {
        $pdf = $this->roster($seminar);
        $pdf->Output();
    }

    public function print_roper_invoice(Request $request)
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();
        $lessons = Lesson::whereBetween('start_time', [$start, $end])->where('zone_id', 47)->where('complete', 1)->orderBy('start_time')->get();
        $pdf = $this->invoice($lessons);
        $pdf->Output();
    }

    public function print_tpt_report(Request $request)
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();
        $pdf = $this->tpt_report($start, $end);
        $pdf->Output();
    }

    public function print_activity_report(Request $request)
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();
        $pdf = $this->monthly_activity_report($start, $end);
        $pdf->Output();
    }

    public function print_dip_certificate(Student $student)
    {
        $pdf = $this->dip_certificate($student);
        $pdf->Output();
    }

    public function print_dip_letter(Student $student)
    {
        $pdf = $this->dip_letter($student);
        $pdf->Output();
    }

    public function print_instructor_mileage(Employee $employee)
    {
        $pdf = $this->instructor_mileage_report($employee);
        $pdf->Output();
    }

    public function print_beginner_invoice(Student $student)
    {
        $pdf = $this->beginner_invoice($student);
        $pdf->Output();
    }

    public function print_class_logs(Seminar $seminar)
    {
        $pdf = $this->class_logs($seminar);
        $pdf->Output();
    }
}
