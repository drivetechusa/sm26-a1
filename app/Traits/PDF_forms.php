<?php

namespace App\Traits;

use App\Enums\StudentTypes;
use App\Models\Charge;
use App\Models\Classroom;
use App\Models\Employee;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Seminar;
use App\Models\Student;
use App\Models\TptTest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Mpdf\Config as MConfig;
use NumberFormatter;

trait PDF_forms
{
    protected $fontDirs;
    protected $fontData;

    public function sc_activity_log(Student $student)
    {
        $pdf = self::create_pdf_file('L');
        $pdf->setSourceFile(public_path('/forms/di-42.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');

        $lineHeight = 42;

        $pdf->WriteText(57, $lineHeight, config('app.school_name'));
        $pdf->WriteText(175, $lineHeight, $student->contract_name);

        $lineHeight = 52;

        $pdf->WriteText(55, $lineHeight, $student->permit_number ?? '');
        $pdf->WriteText(170, $lineHeight, $student->dob->format('m/d/Y') ?? 'Permit #');

        $lineHeight = 97;
        //  Lesson Data
        foreach ($student->ascLessons as $lesson)
        {
            $pdf->WriteText(19, $lineHeight, $lesson->start_time->format('m/d/Y') ?? '');
            $pdf->WriteText(44, $lineHeight, $lesson->start_time->format('h:i a') ?? '');
            $pdf->WriteText(70, $lineHeight, $lesson->end_time->format('h:i a') ?? '');
            $pdf->WriteText(96, $lineHeight, strval($lesson->total_time) . ' hrs' ?? '');
            $pdf->WriteText(120, $lineHeight, strval($lesson->begin_mileage) ?: 'N/A');
            $pdf->WriteText(144, $lineHeight, strval($lesson->end_mileage) ?: 'N/A');
            $pdf->WriteText(168, $lineHeight, $lesson->vehicle->tag_number ?? 'N/A');
            $lineHeight += 11;
        }

        return $pdf;
    }

    public function class_logs(Seminar $seminar)
    {
        $students = $seminar->students;
        $pdf = self::create_pdf_file('L');
        $pdf->setSourceFile(public_path('/forms/di-42.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->SetFont('freesans', 'bi', '11');
        foreach ($students as $student)
        {
            $pdf->addPage();
            $pdf->useTemplate($template);
            $lineHeight = 42;

            $pdf->WriteText(57, $lineHeight, config('app.school_name'));
            $pdf->WriteText(175, $lineHeight, $student->contract_name);

            $lineHeight = 52;

            $pdf->WriteText(55, $lineHeight, $student->permit_number ?? '');
            $pdf->WriteText(170, $lineHeight, $student->dob->format('m/d/Y') ?? 'Permit #');

            $lineHeight = 97;
            $pdf->WriteText(19, $lineHeight, $seminar->date->format('m/d/Y') ?? '');
            $pdf->WriteText(44, $lineHeight, config('app.classroom_start_time'));
            $pdf->WriteText(70, $lineHeight, config('app.classroom_end_time'));
            $pdf->WriteText(96, $lineHeight, '8 hrs');
            $pdf->WriteText(120, $lineHeight, 'N/A');
            $pdf->WriteText(144, $lineHeight, 'N/A');
            $pdf->WriteText(168, $lineHeight, 'N/A');
        }

        return $pdf;
    }

    public function scheduling_instructions(Student $student)
    {
        $pdf = self::create_pdf_file('P');
        $pdf->setSourceFile(public_path('/forms/lads_scheduling_instructions.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');

        $pdf->WriteText(58, 41, $student->username);
        $pdf->WriteText(57, 56, $student->firstname . substr($student->phone, -4));

        return $pdf;
    }

    public function payment_instructions(Student $student)
    {
        $pdf = self::create_pdf_file();
        $pdf->setSourceFile(public_path('/forms/lads_online_payment_instructions.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);

        $pdf->SetFont('freesans', 'bi', '10');

        $pdf->WriteText(47, 70, $student->display_name ?: '');
        $pdf->WriteText(47, 86.4, $student->username ?: '');
        $pdf->WriteText(47, 95, optional($student->dob)->format('m/d/Y') ?: '');



        return $pdf;
    }

    public function account_statement(Student $student)
    {
        $charges = $student->charges;
        $payments = $student->payments;
        $transactions = $charges->merge($payments);
        $transactions->sortBy('created_at');

        $pdf = new Mpdf(['default_font' => 'sans-serif', 'default_font_size' => '11', 'default_font_style' => 'b']);
        $pdf->setSourceFile(public_path('/forms/account_statement.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');

        $lineHeight = 115;
        $running_balance = 0;
        foreach ($transactions as $trans)
        {
            if ($trans instanceof Charge)
            {
                $pdf->writeText(15, $lineHeight, carbon($trans->entered)->format('m/d/Y'));

                $remarks = match ($trans->reason) {
                    'Course A' => 'Basic (8 Hrs Class + 6 Hrs BTW)',
                    'Course A Enrollment' => 'Basic (8 Hrs Class + 6 Hrs BTW)',
                    'Course B' => 'Plus (8 Hrs Class + 8 Hrs BTW)',
                    'Course B Enrollment' => 'Plus (8 Hrs Class + 8 Hrs BTW)',
                    'Course C' => 'Premium (8 Hrs Class + 10 Hrs BTW)',
                    'Course C Enrollment' => 'Premium (8 Hrs Class + 10 Hrs BTW)',
                    'BTW Only - 2 hours' => $trans->reason,
                    'BTW Only - 4 hours' => $trans->reason,
                    'BTW Only - 6 hours' => $trans->reason,
                    'Road Test' => $trans->reason,
                    'Road Test Enrollment' => $trans->reason,
                    'Skills Test Enrollment' => $trans->reason,
                    'Point Reduction' => $trans->reason,
                    'Mature Operator Enrollment' => $trans->reason,
                    'Insurance Reduction' => $trans->reason,
                    'DIP Driver Improvement' => $trans->reason,
                    'NSF Fee' => $trans->reason,
                    'Document Fee' => $trans->reason,
                    default => $trans->reason,
                };

                $pdf->writeText(42, $lineHeight, $remarks);
                $pdf->writeText(115, $lineHeight, money($trans->amount));
                $running_balance += $trans->amount;
                $pdf->writeText(168, $lineHeight, money($running_balance));
            } elseif ($trans instanceof Payment)
            {
                $pdf->writeText(15, $lineHeight, carbon($trans->date)->format('m/d/Y'));
                $pdf->writeText(42, $lineHeight, $trans->remarks);
                $pdf->writeText(142, $lineHeight, money($trans->amount));
                $running_balance -= $trans->amount;
                $pdf->writeText(168, $lineHeight, money($running_balance));
            }
            $lineHeight += 6.5;
        }

        // Student Bill To
        $pdf->writeText(15, 72, $student->contract_name);
        $pdf->writeText(15, 77, $student->street);
        $pdf->writeText(15, 82, $student->csz ?? '');

        // Date
        $pdf->writeText(171, 54, Carbon::now()->format('m/d/Y'));

        // Customer ID
        $pdf->writeText(171, 73, $student->username);

        //$pdf->setFont('sans-serif', 'B', 11);
        $pdf->writeText(171, 236, money($student->balance));

        // Logo
        if (config('app.logo'))
        {
            $pdf->Image(asset('images/logos/' . config('app.logo')), 15, 10, 0, 25, 'svg');
        } else {
            $pdf->WriteText(15, 35, config('app.school_name'));
        }

        $pdf->WriteText(15, 40, config('app.school_street') ?? '');
        $pdf->WriteText(15, 45, config('app.school_csz') ?? '');
        $pdf->WriteText(15, 50, config('app.school_phone') ?? '');
        $pdf->WriteText(15, 55, config('app.school_email') ?? '');


        return $pdf;
    }

    public function class_contracts(Seminar $seminar)
    {
        $students = $seminar->students;
        $pdf = self::create_pdf_file('P');
        $pdf->setSourceFile(public_path('templates/beginner_contract_reconfigured.pdf'));
        $template = $pdf->ImportPage(1);
        $page2 = $pdf->ImportPage(2);

        foreach ($students as $student)
        {
            switch ($student->type)
            {
                case 'DIP':
                case 'Point Reduction':
                    $pdf->AddPage();
                    $pdf->useTemplate($template);

                    $pdf->SetFont('freesans', 'bi', '9');

                    self::add_prices_to_contract($pdf);

                    $pdf->WriteText(46, 35.5, Carbon::now()->format('M d, Y'));
                    if ($seminar->title) {
                        $pdf->WriteText(135, 35.5, $seminar->title);
                    } else {
                        $pdf->WriteText(135, 35.5, $seminar->date->format('M d, Y '));
                    }
                    $pdf->WriteText(53, 43.5, $seminar->classroom->contract_address);

                    $pdf->WriteText(178, 55, $student->username ?: '');
                    $pdf->WriteText(30, 66, $student->contract_name);

                    $pdf->WriteText(140, 66, $student->display_birthdate ?: 'DOB');
                    $pdf->WriteText(35, 81, $student->contract_address);

                    $pdf->WriteText(135, 94, phone($student->student_phone));


                    $pdf->WriteText(66, 116.8, $student->parent_name ?: '');
                    $pdf->WriteText(153, 117, phone($student->phone));
                    $pdf->WriteText(52, 136, $student->parent_name_alternate ?: '');
                    $pdf->WriteText(153, 136, phone($student->secondary_phone));
                    $pdf->WriteText(63, 122, $student->email ?: '');

                    $pdf->WriteText(55, 160.5, $student->permit_number ?: '');
                    $pdf->WriteText(165, 160.5, $student->display_issue_date);

                    $pdf->SetFontSize(13);
                    $pdf->WriteText(15.5, 173, 'XX');

                    break;
                case 'Insurance Discount':

                    break;
                default:
                    $pdf->AddPage();
                    self::process_signature_image($student);


                    $pdf->useTemplate($template);
                    $pdf->SetFont('freesans', 'bi', '9');

                    $pdf->WriteText(46, 53.5, Carbon::now()->format('M d, Y'));
                    if ($seminar->title) {
                        $pdf->WriteText(130, 53.5, $seminar->title);
                    } else {
                        $pdf->WriteText(174, 53.5, $seminar->date->format('M d, Y '));
                    }

                    $pdf->WriteText(178, 15, $student->username ?: '');
                    $pdf->WriteText(30, 77.5, $student->contract_name);
                    $pdf->WriteText(150, 77.5, $student->goes_by ?: '');
                    $pdf->WriteText(32, 89, $student->gender ?: '');
                    $pdf->WriteText(176, 89, $student->display_birthdate . " ($student->age)");
                    $pdf->WriteText(35, 99.5, $student->contract_address);
                    $pdf->WriteText(32, 110, $student->email_student ?: '');
                    //$pdf->WriteText(41, 120, $student->high_school ?: '');
                    $pdf->WriteText(150, 120, $student->neighborhood ?: '');
                    $pdf->WriteText(50, 130, phone($student->student_phone));

                    $pdf->WriteText(40, 152.5, $student->parent_name ?: '');
                    $pdf->WriteText(30, 159, phone($student->phone));
                    $pdf->WriteText(141, 152.5, $student->parent_name_alternate ?: '');
                    $pdf->WriteText(143, 168, $student->parent_email_alternate ?: '');
                    $pdf->WriteText(136, 159, phone($student->secondary_phone));
                    $pdf->WriteText(42, 168, $student->email ?: '');


                    $pdf->WriteText(53, 199, $student->permit_number ?: '');
                    $pdf->WriteText(121, 199, $student->display_issue_date ?: '');

                    $pdf->WriteText(184, 199, $student->eligible_date);


                    //$pdf->SetFont('dejavusans');

                    $pdf->SetXY(16, 247);
                    $pdf->WriteCell(184, 0, config("safeds.student_type_labels.$student->type"), 0, 0, 'C');
                    $pdf->SetXY(16, 252);
                    $pdf->WriteCell(184, 0, 'Tuition: ' . money($student->charges()->latest()->first()->amount), 0, 0, 'C');
                    $pdf->SetXY(16, 257);
                    $pdf->WriteCell(184, 0, 'Balance: ' . money($student->balance), 0, 0, 'C');


                    //$pdf->SetFont('dejavusans', 'b');

                    $pdf->AddPage();
                    $pdf->useTemplate($page2);
                    $pdf->SetFont('freesans', 'bi', '9');
//        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
//        {
//            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 130, 235, 60, 20);
//        }
//
//        $pdf->WriteText(120, 259, $student->printed_signature ?: '');
                    //$pdf->WriteText(36, 259, $student->contract_name);
                    $pdf->WriteText(28, 263, now()->format('m/d/Y'));
                    $pdf->WriteText(112, 263, now()->format('m/d/Y'));

                    break;
            }
        }
        return $pdf;
    }

    public function completion_cert(Student $student)
    {
        $pdf = self::create_pdf_file('L', 'Letter');
        $pdf->setSourceFile(public_path('forms/completion_certificate.pdf'));
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);
        $pdf->setXY(52, 85);
        $pdf->AutosizeText($student->contract_name, 172, 'tangerine', 'B');
        $pdf->setFont('sans-serif', 'B', 13);
        $pdf->writeText(130, 128, strval($student->drive_time_completed) ?: '');
        $pdf->writeText(40, 168, optional($student->date_completed)->format('m/d/Y') ?: '');
        $pdf->setFont('sans-serif', 'B', 10);
        if ($student->instructor)
        {
            $pdf->writeText(102, 169.25, optional($student->instructor)->name ?: '');
            $pdf->setFont('dancingscript', 'B', 26);
            $pdf->writeText(89, 161, optional($student->instructor)->name ?: '');
        } else {
            $pdf->writeText(102, 169.25, config('app.certificate_name') ?: '');
            $pdf->setFont('dancingscript', 'B', 26);
            $pdf->writeText(89, 161, config('app.certificate_name') ?: '');
        }
        $pdf->setFont('sans-serif', 'B', 14);
        // Logo
        if (config('app.logo'))
        {
            $pdf->Image(asset('images/logos/' . config('app.logo')), 170, 145, 0, 20, 'svg');
        } else {
            $pdf->WriteText(170, 170, config('app.school_name'));
        }
        $pdf->setFont('sans-serif', 'R', 8);
        $pdf->WriteText(170, 175, config('app.school_street') ?? '');
        $pdf->WriteText(170, 179, config('app.school_csz') ?? '');
        $pdf->WriteText(170, 183, config('app.school_phone') ?? '');
        $pdf->WriteText(170, 187, config('app.school_email') ?? '');


        return $pdf;


    }

//    public function kia_cert(Student $student)
//    {
//        $pdf = self::create_pdf_file('L', 'Letter');
//        $pdf->setSourceFile(public_path('forms/gift_certificate.pdf'));
//        $template = $pdf->importPage(1);
//        $pdf->useTemplate($template);
//        $pdf->setXY(85, 89);
//        $pdf->AutosizeText($student->contract_name, 113, 'freesans', 'B');
//
//        $pdf->SetFont('freesans', 'B', 14);
//        $lineHeight = 130;
//        $pdf->WriteText(121, $lineHeight, $student->date_completed->format('d'));
//        $pdf->WriteText(146, $lineHeight, $student->date_completed->format('F'));
//        $pdf->WriteText(184, $lineHeight, $student->date_completed->format('y'));
//
//        return $pdf;
//    }

    public function coversheet(Student $student)
    {
        $pdf = self::create_pdf_file('L');
        $pdf->setSourceFile(public_path('/templates/coversheet.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);

        $pdf->SetFont('dejasans', 'b', '16');

        //$pdf->SetXY(48, 9);
        $pdf->WriteText(50,10, $student->full_name);
        $pdf->SetFont('dejasans', 'b', '12');
        $pdf->WriteText(50, 18, "DOB: " . $student->display_birthdate);
        $pdf->WriteText(50,26, "Gender: " . $student->gender);
        $pdf->SetXY(230, 10);
        $pdf->WriteCell(20,0,$student->status,0,0,'r');
        $pdf->WriteText(230, 17, $student->display_completion_date);

        $pdf->WriteText(245, 26, money($student->balance));
        $pdf->setXY(205, 2);


        $pdf->SetFont('dejasans', '', 10);

        $pdf->WriteText(218, 33.3, $student->enroller->name);

        $pdf->WriteText(7, 39, $student->street . ' ' . $student->street1);
        $pdf->WriteText(7, 43, $student->csz ?: '');

        $pdf->WriteText(74, 39, phone($student->student_phone));
        $pdf->WriteText(74, 44.5, phone($student->phone));

        $pdf->WriteText(142, 39, $student->email_student ?: 'student email');
        $pdf->WriteText(142, 44.5, $student->email ?: 'second email');

        //Charges
        $lineHeight = 62;
        foreach($student->charges as $charge)
        {
            $pdf->WriteText(7, $lineHeight, dateForHumans($charge->entered));
            $pdf->WriteText(33, $lineHeight, money($charge->amount));
            $pdf->WriteText(60, $lineHeight, $charge->reason);
            $lineHeight += 5;
        }

        // Payments
        $lineHeight = 62;
        foreach($student->payments as $payment)
        {
            $pdf->WriteText(107, $lineHeight, dateForHumans($payment->date));
            $pdf->WriteText(128, $lineHeight, money($payment->amount));
            $pdf->WriteText(148, $lineHeight, $payment->type);
            $pdf->WriteText(175, $lineHeight, $payment->remarks);
            $lineHeight += 5;
        }

        // Notes
        $lineHeight = 95;
        $notes = $student->notes()->orderBy('created', 'desc')->limit(5)->get();
        foreach($notes as $note)
        {
            $pdf->WriteText('7', $lineHeight, dateForHumans($note->created));
            $pdf->WriteText(30, $lineHeight, $note->note);
            $lineHeight += 4;
        }

        // Classes
        $lineHeight = 123;
        foreach($student->seminars as $seminar)
        {
            $pdf->WriteText(7, $lineHeight, dateForHumans($seminar->date));
            $pdf->WriteText(30, $lineHeight, $seminar->class_type);
            $pdf->WriteText(60, $lineHeight, $seminar->employee->name);
            $pdf->WriteText(100, $lineHeight, $seminar->pivot->level ?: '');
            $pdf->WriteText(160, $lineHeight, "Discount: " . $seminar->pivot->discount ?: '');

            $lineHeight += 5;
        }

        // Lessons
        $lineHeight = 153;
        foreach($student->lessons as $lesson)
        {
            $pdf->WriteText(7, $lineHeight, $lesson->start_time->format('m/d/y'));
            $pdf->WriteText(28, $lineHeight, $lesson->type);
            $pdf->WriteText(60, $lineHeight, $lesson->start_time->format('h:i a'));
            $pdf->WriteText(102, $lineHeight, $lesson->end_time->format('h:i a'));
            $pdf->WriteText(157, $lineHeight, strval($lesson->mileage));
            $pdf->WriteText(177, $lineHeight, $lesson->employee->name);
            $lineHeight += 5;
        }

        return $pdf;
    }

    public function class_coversheets(Seminar $seminar)
    {
        $students = $seminar->students;
        $pdf = self::create_pdf_file('L');
        $pdf->setSourceFile(public_path('/templates/coversheet.pdf'));
        $template = $pdf->ImportPage(1);
//        $pdf->useTemplate($template);
//
//        $pdf->SetFont('dejasans', 'b', '16');

        foreach ($students as $student)
        {
            $pdf->AddPage();
            $pdf->useTemplate($template);
            $pdf->SetFont('dejasans', 'b', '16');
            //$pdf->SetXY(48, 9);
            $pdf->WriteText(50,10, $student->full_name);
            $pdf->SetFont('dejasans', 'b', '12');
            $pdf->WriteText(50, 18, "DOB: " . $student->display_birthdate);
            $pdf->WriteText(50,26, "Gender: " . $student->gender);
            $pdf->SetXY(230, 10);
            $pdf->WriteCell(20,0,$student->status,0,0,'r');
            $pdf->WriteText(230, 17, $student->display_completion_date);

            $pdf->WriteText(245, 26, money($student->balance));
            $pdf->setXY(205, 2);


            $pdf->SetFont('dejasans', '', 10);

            $pdf->WriteText(218, 33.3, $student->enroller->name);

            $pdf->WriteText(7, 39, $student->street . ' ' . $student->street1);
            $pdf->WriteText(7, 43, $student->csz ?: '');

            $pdf->WriteText(74, 39, phone($student->student_phone));
            $pdf->WriteText(74, 44.5, phone($student->phone));

            $pdf->WriteText(142, 39, $student->email_student ?: 'student email');
            $pdf->WriteText(142, 44.5, $student->email ?: 'second email');

            //Charges
            $lineHeight = 62;
            foreach($student->charges as $charge)
            {
                $pdf->WriteText(7, $lineHeight, dateForHumans($charge->entered));
                $pdf->WriteText(33, $lineHeight, money($charge->amount));
                $pdf->WriteText(60, $lineHeight, $charge->reason);
                $lineHeight += 5;
            }

            // Payments
            $lineHeight = 62;
            foreach($student->payments as $payment)
            {
                $pdf->WriteText(107, $lineHeight, dateForHumans($payment->date));
                $pdf->WriteText(128, $lineHeight, money($payment->amount));
                $pdf->WriteText(148, $lineHeight, $payment->type);
                $pdf->WriteText(175, $lineHeight, $payment->remarks);
                $lineHeight += 5;
            }

            // Notes
            $lineHeight = 95;
            $notes = $student->notes()->orderBy('created', 'desc')->limit(5)->get();
            foreach($notes as $note)
            {
                $pdf->WriteText('7', $lineHeight, dateForHumans($note->created));
                $pdf->WriteText(30, $lineHeight, $note->note);
                $lineHeight += 4;
            }

            // Classes
            $lineHeight = 123;
            foreach($student->seminars as $seminar)
            {
                $pdf->WriteText(7, $lineHeight, dateForHumans($seminar->date));
                $pdf->WriteText(30, $lineHeight, $seminar->class_type);
                $pdf->WriteText(60, $lineHeight, $seminar->employee->name);
                $pdf->WriteText(100, $lineHeight, $seminar->pivot->level ?: '');
                $pdf->WriteText(160, $lineHeight, "Discount: " . $seminar->pivot->discount ?: '');

                $lineHeight += 5;
            }

            // Lessons
            $lineHeight = 153;
            foreach($student->lessons as $lesson)
            {
                $pdf->WriteText(7, $lineHeight, $lesson->start_time->format('m/d/y'));
                $pdf->WriteText(28, $lineHeight, $lesson->type);
                $pdf->WriteText(60, $lineHeight, $lesson->start_time->format('h:i a'));
                $pdf->WriteText(102, $lineHeight, $lesson->end_time->format('h:i a'));
                $pdf->WriteText(157, $lineHeight, strval($lesson->mileage));
                $pdf->WriteText(177, $lineHeight, $lesson->employee->name);
                $lineHeight += 5;
            }

        }
        return $pdf;
    }

    public function beginner_contract(Student $student, Seminar $seminar, $data = null) : Mpdf
    {
        self::process_signature_image($student);

        $pdf = new Mpdf(['default_font' => 'sans-serif', 'default_font_size' => '10', 'default_font_style' => 'b']);
        $pdf->setSourceFile(public_path('/templates/beginner_contract_reconfigured.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(46, 53.5, Carbon::now()->format('M d, Y'));
        if ($seminar->title) {
            $pdf->WriteText(130, 53.5, $seminar->title);
        } else {
            $pdf->WriteText(174, 53.5, $seminar->date->format('M d, Y '));
        }

        $pdf->WriteText(178, 15, $student->username ?: '');
        $pdf->WriteText(30, 77.5, $student->contract_name);
        $pdf->WriteText(150, 77.5, $student->goes_by ?: '');
        $pdf->WriteText(32, 89, $student->gender ?: '');
        $pdf->WriteText(176, 89, $student->display_birthdate . " ($student->age)");
        $pdf->WriteText(35, 99.5, $student->contract_address);
        $pdf->WriteText(32, 110, $student->email_student ?: '');
        //$pdf->WriteText(41, 120, $student->high_school ?: '');
        $pdf->WriteText(150, 120, $student->neighborhood ?: '');
        $pdf->WriteText(50, 130, phone($student->student_phone));

        $pdf->WriteText(40, 152.5, $student->parent_name ?: '');
        $pdf->WriteText(30, 159, phone($student->phone));
        $pdf->WriteText(141, 152.5, $student->parent_name_alternate ?: '');
        $pdf->WriteText(143, 168, $student->parent_email_alternate ?: '');
        $pdf->WriteText(136, 159, phone($student->secondary_phone));
        $pdf->WriteText(42, 168, $student->email ?: '');


        $pdf->WriteText(53, 199, $student->permit_number ?: '');
        $pdf->WriteText(121, 199, $student->display_issue_date ?: '');

        $pdf->WriteText(184, 199, $student->eligible_date);


        //$pdf->SetFont('dejavusans');

        $pdf->SetXY(16, 247);
        $pdf->WriteCell(184, 0, $student->type->extendedLabel(), 0, 0, 'C');
        $pdf->SetXY(16, 252);
        $pdf->WriteCell(184, 0, 'Tuition: ' . money($student->charges()->latest()->first()->amount), 0, 0, 'C');
        $pdf->SetXY(16, 257);
        $pdf->WriteCell(184, 0, 'Balance: ' . money($student->balance), 0, 0, 'C');


        //$pdf->SetFont('dejavusans', 'b');

        $pdf->AddPage();
        $template = $pdf->ImportPage(2);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');
//        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
//        {
//            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 130, 235, 60, 20);
//        }
//
//        $pdf->WriteText(120, 259, $student->printed_signature ?: '');
        //$pdf->WriteText(36, 259, $student->contract_name);
        $pdf->WriteText(28, 263, now()->format('m/d/Y'));
        $pdf->WriteText(112, 263, now()->format('m/d/Y'));

        return $pdf;
    }



    public function instructor_course_contract(Student $student, $data) : Mpdf
    {
        self::process_signature_image($student);

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile('forms/instructor.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(30, 50, $student->contract_name);
        $pdf->WriteText(175, 50, $student->goes_by ?: '');
        $pdf->WriteText(33, 60, $student->contract_address);
        $pdf->WriteText(181, 60, $student->display_birthdate);
        $pdf->WriteText(30, 69, phone($student->student_phone));
        $pdf->WriteText(104, 69, $student->email_student ?: 'n/a');
        $pdf->WriteText(50, 98.5, $student->permit_number ?: 'n/a');
        $pdf->WriteText(175, 98.5, $student->display_issue_date ?: 'n/a');
        $pdf->WriteText(175, 123.5, Classroom::find(38)->instructor_price);

        $pdf->addPage();
        $template2 = $pdf->ImportPage(2);
        $pdf->useTemplate($template2);

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 70, 115, 60, 20);
        }
        $pdf->WriteText(42, 129, $student->printed_signature ?: '');
        $pdf->WriteText(28, 134, now()->format('m/d/Y'));
//        if ($data['driving_school_contact'])
//        {
//            $pdf->WriteText(65, 155, $data['driving_school_contact'] ?: '');
//        }
//        if ($data['driving_school_phone'])
//        {
//            $pdf->WriteText(155, 155, phone($data['driving_school_phone']));
//        }



        return $pdf;
    }

    public function point_reduction_contract(Student $student, Seminar $seminar, $data): Mpdf
    {
        self::process_signature_image($student);

        $pdf = new Mpdf(['default_font' => 'sans-serif', 'default_font_size' => '10', 'default_font_style' => 'b']);
        $pdf->setSourceFile('templates/point_reduction_contract.pdf');
        $template = $pdf->ImportPage(1);
        $page2 = $pdf->ImportPage(2);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');

        $pdf->WriteText(44, 53.5, today()->format('m/d/Y'));
        $pdf->WriteText(178, 53.5, $seminar->date->format('m/d/Y'));
        $pdf->WriteText(27, 84, $student->contract_name);
        $pdf->WriteText(123, 84, $student->goes_by ?: '');
        $pdf->WriteText(180, 84, $student->dob->format('m/d/Y'));
        $pdf->WriteText(30, 94.5, $student->contract_address);
        $pdf->WriteText(105, 105, phone($student->student_phone));
        $pdf->WriteText(55, 115.5, $student->permit_number ?: '');
        $pdf->WriteText(40, 125, $student->email_student ?: '');
        $pdf->WriteText(170, 125, $student->gender ?: '');

        $pdf->setFont('freesans', 'bi', '9');
        $pdf->SetXY(16, 176);
        $pdf->WriteCell(184, 0, config("safeds.student_type_labels.$student->type"), 0, 0, 'C');
        $pdf->SetXY(16, 182);
        $pdf->WriteCell(184, 0, 'Tuition: ' . money($student->charges()->latest()->first()->amount), 0, 0, 'C');
        $pdf->SetXY(16, 187);
        $pdf->WriteCell(184, 0, 'Balance: ' . money($student->balance), 0, 0, 'C');

        $pdf->AddPage();
        $pdf->useTemplate($page2);

        return $pdf;
    }

    public function insurance_reduction_contract(Student $student, Seminar $seminar, $data): Mpdf
    {
        self::process_signature_image($student);

        $pdf = new Mpdf(['default_font' => 'sans-serif', 'default_font_size' => '10', 'default_font_style' => 'b']);
        $pdf->setSourceFile('forms/contracts/student_packet.pdf');
        $template = $pdf->ImportPage(3);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(69, 53.5, $student->contract_name);
        $pdf->WriteText(149, 53.5, $student->dob->format('m/d/Y'));
        $pdf->WriteText(72, 58, $student->contract_address);
        $pdf->WriteText(26, 62.25, phone($student->student_phone));
        $pdf->WriteText(155, 62.25, $student->permit_number ?: '');

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 55, 245, 60, 20);
        }
        $pdf->WriteText(37, 266, $student->printed_signature ?: '');
        $pdf->WriteText(25, 270.5, now()->format('m/d/Y'));


//        switch ($data['processing'])
//        {
//            case 'immediate_processing':
//                if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
//                {
//                    $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 87, 70, 60, 20);
//                }
//                $pdf->WriteText(180, 79, now()->format('m/d/Y'));
//                break;
//            case 'hold_processing':
//                if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
//                {
//                    $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 87, 77, 60, 20);
//                }
//                $pdf->WriteText(180, 85, now()->format('m/d/Y'));
//                break;
//            case 'no_processing':
//                if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
//                {
//                    $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 87, 83, 60, 20);
//                }
//                $pdf->WriteText(180, 92, now()->format('m/d/Y'));
//                break;
//        }

        return $pdf;
    }

    public function hand_controls_contract(Student $student, $data): Mpdf
    {
        self::process_signature_image($student);

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile('forms/hand_controls_contract.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');


        $pdf->WriteText(30, 50, $student->contract_name);
        $pdf->WriteText(175, 50, $student->goes_by ?: '');
        $pdf->WriteText(33, 60, $student->contract_address);
        $pdf->WriteText(181, 60, $student->dob->format('m/d/Y'));
        $pdf->WriteText(30, 69, phone($student->student_phone));
        $pdf->WriteText(104, 69, $student->email_student ?: 'n/a');
        $pdf->WriteText(57, 98.5, $student->permit_number ?: 'n/a');
        $pdf->WriteText(135, 98.5, $student->display_issue_date ?: 'n/a');
        $pdf->WriteText(165, 124, Classroom::find(38)->hand_controls_price);
        $pdf->WriteText(128, 257.5, Classroom::find(38)->hand_controls_price);

        $pdf->addPage();
        $template2 = $pdf->ImportPage(2);
        $pdf->useTemplate($template2);

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 70, 115, 60, 20);
        }
        $pdf->WriteText(42, 129, $student->printed_signature ?: '');
        $pdf->WriteText(28, 134, now()->format('m/d/Y'));

        $pdf->WriteText(65, 155, $student->parent_name ?: '');
        $pdf->WriteText(155, 155, phone($student->phone));

        return $pdf;
    }

    public function med_form(Student $student, $data = null): Mpdf
    {
        $pdf = new Mpdf(['default_font' => 'sans-serif', 'default_font_size' => '10']);
        $pdf->setSourceFile('templates/med_form.pdf');
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);

        $pdf->setFont('sans-serif', 'B', 10);
        $pdf->writeText(50, 37.5, $student->contract_name);

//        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
//        {
//            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 68, 200, 60, 20);
//        }
        //$pdf->WriteText(42, 218, $student->printed_signature ?: '');
        $pdf->WriteText(30, 223.75, Carbon::now()->format('m/d/Y'));

        $pdf->WriteText(65, 181, $student->parent_name ?: '');
        $pdf->WriteText(147, 181, phone($student->phone));


        return $pdf;
    }

    public function btw_contract(Student $student, $data): Mpdf
    {
        self::process_signature_image($student);

        $classroom = Classroom::find(1);
//        $level = number_format($data['program_level']);
//        $level <= 4 ? $rate = $classroom->lxl_price : $rate = $classroom->lxl_discount_price;
//        $data['add_road_test'] == 'true' ? $cost = $level * $rate + $classroom->road_test_price : $cost = $level * $rate;
        //$pdf = new Mpdf(['default_font' => 'sans-serif', 'default_font_size' => '10', 'default_font_style' => 'b']);
        $pdf = self::create_pdf_file();
        $pdf->setSourceFile(public_path('/templates/btw_contract.pdf'));
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(184, 10, strval($student->id));
        $pdf->WriteText(184, 53.5, dateForHumans(Carbon::now()));

        $pdf->WriteText(29, 83.5, $student->display_name);
        $pdf->WriteText(115, 83.5, $student->goes_by ?: '');
        $pdf->WriteText(178, 83.5, $student->display_birthdate . " ($student->age)");

        $pdf->WriteText(30, 94, $student->contract_address);
        $pdf->WriteText(160, 94, $student->gender);
//        if (array_key_exists('college', $data)) {
//            $pdf->WriteText(41, 104, $data['college'] ?: 'College');
//        }
        $pdf->WriteText(135, 104, $student->neighborhood ?: '');
//        if (array_key_exists('home_phone', $data)) {
//            $pdf->WriteText(39, 115.25, phone($data['home_phone']));
//        }
        $pdf->WriteText(101, 115.25, phone($student->student_phone));
//        if (array_key_exists('other_phone', $data)) {
//            $pdf->WriteText(160, 115.25, phone($data['other_phone']));
//        }
        $pdf->WriteText(52, 125.6, $student->email_student ?: '');
        $pdf->WriteText(55, 155, $student->parent_name ?: '');
        $pdf->WriteText(55, 160.5, $student->parent_name_alternate ?: '');
//        if (array_key_exists('relationship', $data)) {
//            $pdf->WriteText(118, 155, $data['relationship'] ?: '');
//        }
//        if(array_key_exists('relationship_1', $data)) {
//            $pdf->WriteText(118, 160.5, $data['relationship_1'] ?: '');
//        }
        $pdf->WriteText(167, 155, phone($student->phone));
        $pdf->WriteText(167, 160.5, phone($student->secondary_phone));

        $pdf->WriteText(51, 191, $student->permit_number ?: '');
        $pdf->WriteText(118, 191, $student->display_issue_date);
        $pdf->WriteText(178, 191, $student->eligible_date ?: '');


//        $pdf->WriteText(80, 196.5, strval($data['program_level']) . ' Hours' ?: '');
//        if ($data['add_road_test'] == 'true') {
//            $pdf->WriteText(93, 196.5, '+ Skills (Road) Test');
//        }
        //$pdf->WriteText(113, 201, '$ ' . number_format($cost, 2));
        //$pdf->WriteText(113, 206.5, '$ ' . number_format($cost - $student->balance, 2));


        $template2 = $pdf->ImportPage(2);
        $pdf->addPage();
        $pdf->useTemplate($template2);

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 50 , 220, 60, 20);
        }

        //$pdf->WriteText(120, 259, $student->printed_signature ?: '');

        $pdf->WriteText(40, 246.25, $student->display_name ?: '');
        $pdf->WriteText(28, 250.5, dateForHumans(Carbon::now()));

        return $pdf;
    }

    public function driver_evaluation_contract(Student $student, $data): Mpdf
    {
        self::process_signature_image($student);

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile('forms/evaluation.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(30, 50, $student->contract_name);
        $pdf->WriteText(175, 50, $student->goes_by ?: '');
        $pdf->WriteText(33, 60, $student->contract_address);
        $pdf->WriteText(181, 60, $student->display_birthdate);
        $pdf->WriteText(30, 69, phone($student->student_phone));
        $pdf->WriteText(104, 69, $student->email_student ?: 'n/a');
        $pdf->WriteText(57, 98.5, $student->permit_number ?: 'n/a');
        $pdf->WriteText(135, 98.5, $student->display_issue_date ?: 'n/a');
        $pdf->WriteText(178, 124, Classroom::find(38)->evaluation_price);
        $pdf->WriteText(128, 257.5, Classroom::find(38)->lxl_price);

        $pdf->addPage();
        $template2 = $pdf->ImportPage(2);
        $pdf->useTemplate($template2);

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 70, 115, 60, 20);
        }
        $pdf->WriteText(42, 129, $student->printed_signature ?: '');
        $pdf->WriteText(28, 134, now()->format('m/d/Y'));

        $pdf->WriteText(65, 155, $student->parent_name ?: '');
        $pdf->WriteText(155, 155, phone($student->phone));

        $pdf->WriteText(65, 159, $student->parent_name_alternate ?: '');
        $pdf->WriteText(155, 159, phone($student->secondary_phone));

        $pdf->SetY(165);
        $pdf->MultiCell(0,4.5, $data['evaluation_reason']);

        return $pdf;
    }

    public function skills_contract(Student $student, $data): Mpdf
    {
        self::process_signature_image($student);

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile('forms/Skills.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(30, 50, $student->contract_name);
        $pdf->WriteText(175, 50, $student->goes_by ?: '');
        $pdf->WriteText(33, 60, $student->contract_address);
        $pdf->WriteText(181, 60, $student->display_birthdate);
        $pdf->WriteText(30, 69, phone($student->student_phone));
        $pdf->WriteText(104, 69, $student->email_student ?: 'n/a');
        $pdf->WriteText(57, 98.5, $student->permit_number ?: 'n/a');
        $pdf->WriteText(135, 98.5, $student->display_issue_date ?: 'n/a');
        $pdf->WriteText(171, 124, Classroom::find(38)->road_test_price);
        $pdf->WriteText(128, 257.5, Classroom::find(38)->lxl_price);

        $pdf->addPage();
        $template2 = $pdf->ImportPage(2);
        $pdf->useTemplate($template2);

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 70, 115, 60, 20);
        }
        $pdf->WriteText(42, 129, $student->printed_signature ?: '');
        $pdf->WriteText(28, 134, now()->format('m/d/Y'));

        $pdf->WriteText(65, 155, $student->parent_name ?: '');
        $pdf->WriteText(155, 155, phone($student->phone));

        return $pdf;
    }

    public function knowledge_contract(Student $student, $data): Mpdf
    {
        self::process_signature_image($student);

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile('forms/Knowledge.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');

        $pdf->WriteText(30, 50, $student->contract_name);
        $pdf->WriteText(175, 50, $student->goes_by ?: '');
        $pdf->WriteText(33, 60, $student->contract_address);
        $pdf->WriteText(181, 60, $student->display_birthdate);
        $pdf->WriteText(30, 69, phone($student->student_phone));
        $pdf->WriteText(104, 69, $student->email_student ?: 'n/a');
        $pdf->WriteText(57, 98.5, $student->permit_number ?: 'n/a');
        $pdf->WriteText(135, 98.5, $student->display_issue_date ?: 'n/a');
        $pdf->WriteText(179, 124, Classroom::find(38)->permit_test_price);
        $pdf->WriteText(128, 257.5, Classroom::find(38)->lxl_price);

        $pdf->addPage();
        $template2 = $pdf->ImportPage(2);
        $pdf->useTemplate($template2);

        if (file_exists(storage_path() . "/app/public/signatures/$student->id.png"))
        {
            $pdf->Image(storage_path() . "/app/public/signatures/$student->id.png", 70, 115, 60, 20);
        }
        $pdf->WriteText(42, 129, $student->printed_signature ?: '');
        $pdf->WriteText(28, 134, now()->format('m/d/Y'));

        $pdf->WriteText(65, 155, $student->parent_name ?: '');
        $pdf->WriteText(155, 155, phone($student->phone));

        return $pdf;
    }

    public function roster(Seminar $seminar)
    {
        //$pageWidth = 279.4;
        //$pageHeight = 215.9;
        $pdf = self::create_pdf_file('L');
        $pdf->setSourceFile('templates/roster.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', 11);

        $pdf->WriteText(102, 10.75, $seminar->date->format('m/d/Y'));
        $pdf->WriteText(194, 10.75, strval($seminar->id));
        $pdf->WriteText(102, 16.75, $seminar->employee->name);
        $pdf->WriteText(102, 22.75, $seminar->classroom->name);

        $lineHeight = 34.75;
        $pdf->SetFont('freesans', '', 10);
        $counter = 1;
        foreach($seminar->students as $student)
        {
            $pdf->WriteText(8, $lineHeight, strval($counter));
            $pdf->WriteText(15, $lineHeight, $student->display_name . ' (' . strval($student->dob->age) . ')');
            $pdf->WriteText(85, $lineHeight, strval($student->id));
            $pdf->WriteText(107, $lineHeight, money($student->balance));
            $pdf->WriteText(130, $lineHeight, $student->type->label());
            $pdf->WriteText(152, $lineHeight, yn($student->contract));
            $pdf->WriteText(165, $lineHeight, optional($student->zipcode)->city . '('. substr(strval($student->zip_id), -2) . ')');
            //$pdf->WriteText(197, $lineHeight, substr(strval($student->zip_id), -2));
            $pdf->WriteText(205, $lineHeight, current($student->notification_emails));
            $lineHeight += 5.65;
            if (is_int($counter/31))
            {
                $pdf->AddPage('L');
                $pdf->useTemplate($template);
                $pdf->SetFont('freesans', 'bi', 11);

                $pdf->WriteText(102, 10.75, $seminar->date->format('m/d/Y'));
                $pdf->WriteText(194, 10.75, strval($seminar->id));
                $pdf->WriteText(102, 16.75, $seminar->employee->list_name);
                $pdf->WriteText(102, 22.75, $seminar->classroom->name);

                $lineHeight = 34.75;
                $pdf->SetFont('freesans', '', 10);
            }
            $counter++;
        }
        return $pdf;
    }

    public function vertical_roster(Seminar $seminar)
    {

        $pdf = self::create_pdf_file('P');
        $pdf->setSourceFile('forms/vertical_roster.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '9');
        $lineHeight = 15;
        $columnX = 10;

        return $pdf;
    }

    public function invoice(Collection $lessons)
    {
        $pdf = self::create_pdf_file();
        $pdf->setSourceFile('forms/invoice.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');

        // Student Bill To
        $pdf->writeText(15, 72, 'Roper Rehabilitation Hospital');
        $pdf->writeText(15, 77, '316 Calhoun St, 8th Floor');
        $pdf->writeText(15, 82, 'Charleston, SC  29401');

        // Date
        $pdf->writeText(171, 54, Carbon::now()->format('m/d/Y'));

        // Customer ID
        $pdf->writeText(171, 73, Carbon::now()->format('Ymd') . '-' . substr(strval(time()), -4));

        $lineHeight = 115;
        $running_balance = 0;
        foreach($lessons as $lesson)
        {
            $pdf->writeText(15, $lineHeight, $lesson->start_time->format('m/d/Y'));
            $pdf->writeText(42, $lineHeight, $lesson->student->display_name ?? '');
            $pdf->writeText(142, $lineHeight, money('200'));
            $running_balance += 200;
            $pdf->writeText(168, $lineHeight, money($running_balance));
            $lineHeight += 6.75;
        }
//$pdf->setFont('sans-serif', 'B', 11);
        $pdf->writeText(171, 236, money($running_balance));
        $pdf->WriteText(165, 243, 'Terms: Net 30 Days');


        // Logo
        if (config('app.logo'))
        {
            $pdf->Image(asset('images/logos/' . config('app.logo')), 15, 10, 0, 25, 'svg');
        } else {
            $pdf->WriteText(15, 35, config('app.school_name'));
        }

        $pdf->WriteText(15, 40, config('app.school_billing_street') ?? '');
        $pdf->WriteText(15, 45, config('app.school_billing_csz') ?? '');
        $pdf->WriteText(15, 50, config('app.school_phone') ?? '');
        $pdf->WriteText(15, 55, config('app.school_email') ?? '');

        return $pdf;
    }

    public function beginner_invoice($student) : Mpdf
    {
        $pdf = self::create_pdf_file();
        $pdf->setSourceFile('forms/invoice.pdf');
        $template = $pdf->ImportPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');

        switch ($student->type)
        {
            case 'Course A':
                $amount = Classroom::find(38)->teen_price;
                $title = 'Beginner Course A';
                $description = "8hr Classroom/6hr Driving + Road Test";
                break;
            case 'Course B':
                $amount = Classroom::find(38)->ext_price;
                $title = 'Beginner Course B';
                $description = "8hr Classroom/8hr Driving + Road Test";
                break;
            case 'Course C':
                $amount = Classroom::find(38)->adv_price;
                $title = 'Beginner Course C';
                $description = "8hr Classroom/10hr Driving + Road Test";
                break;
            default:
                return $pdf;
        }


        // Student Bill To
        $pdf->writeText(15, 72, $student->short_contract_name . " ($student->id)");
        $pdf->writeText(15, 77, $student->street);
        $pdf->writeText(15, 82, $student->csz);

        // Date
        $pdf->writeText(171, 54, Carbon::now()->format('m/d/Y'));

        // Customer ID
        $pdf->writeText(171, 73, Carbon::now()->format('Ymd') . '-' . substr(strval(time()), -4));

        $lineHeight = 115;


        $pdf->writeText(15, $lineHeight, now()->format('m/d/y'));
        $pdf->writeText(42, $lineHeight, $title);
        $pdf->writeText(142, $lineHeight, money($amount));
        $pdf->writeText(168, $lineHeight, money($amount));
        $pdf->writeText(42, $lineHeight + 7, $description);


//$pdf->setFont('sans-serif', 'B', 11);
        $pdf->writeText(171, 236, money($amount));
        $pdf->WriteText(165, 243, 'Terms: Net 30 Days');


        // Logo
        if (config('app.logo'))
        {
            $pdf->Image(asset('images/logos/' . config('app.logo')), 15, 10, 0, 25, 'svg');
        } else {
            $pdf->WriteText(15, 35, config('app.school_name'));
        }

        $pdf->WriteText(15, 40, config('app.school_billing_street') ?? '');
        $pdf->WriteText(15, 45, config('app.school_billing_csz') ?? '');
        $pdf->WriteText(15, 50, config('app.school_phone') ?? '');
        $pdf->WriteText(15, 55, config('app.school_email') ?? '');

        return $pdf;
    }

    public function monthly_activity_report(Carbon $start, Carbon $end)
    {
        if (Auth::check() == true)
        {
            $user = Auth::user()->name;
        } else {
            $user = config(env('CONFIG_FILE', 'default').".reports_name");
        }

        $students = Student::whereBetween('date_completed', [$start, $end])->get();
        $count = count($students);

        $pdf = self::create_pdf_file('L', 'Letter');
        $pdf->setSourceFile(public_path('forms/di-43.pdf'));
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);
        $pdf->setFont('sans-serif', 'BI', 10);

        $pdf->WriteText(50, 177, config('app.school_name'));
        $pdf->WriteText(200, 177, $user);
        $pdf->WriteText(50, 186, Carbon::now()->format('m/d/Y'));
        $pdf->WriteText(158, 186, strval($count));
        $pdf->WriteText(220, 186, $start->format('F, Y'));
        $lineHeight = 82;
        $pdf->setFont('sans-serif', 'R', 10);
        $counter = 0;
        foreach ($students as $student)
        {
//            $page->drawText($student->full_name, 48, $lineHeight);
            $pdf->WriteText(17, $lineHeight, $student->full_name);
//            $page->drawText(format_phone_number($student->phone, 7), 205, $lineHeight);
            $pdf->WriteText(75, $lineHeight, phone($student->phone) ?: '');
//            $page->drawText($student->permit_number, 310, $lineHeight);
            $pdf->WriteText(120, $lineHeight, $student->permit_number ?: '');
//            $page->drawText(optional($student->date_started)->format('m/d/Y'), 460, $lineHeight);
            $pdf->WriteText(162, $lineHeight, optional($student->date_started)->format('m/d/Y') ?: '');
//            $page->drawText(optional($student->date_completed)->format('m/d/Y'), 560, $lineHeight);
            $pdf->WriteText(200, $lineHeight, optional($student->date_completed)->format('m/d/Y') ?: '');
            $type = match ($student->type) {
                'Lxl', 'LADS Hand' => 'In-Car',
                'Road Test Only' => 'Skills Testing',
                'Permit Test' => 'Knowledge Testing',
                'DIP' => 'DIP',
                'Mature Operator' => 'Mature Operator',
                'Insurance Discount' => 'Insurance Discount',
                'Instructor Course' => 'SC Instructor Cert',
                default => 'Beginner'
            };
            $pdf->WriteText(231, $lineHeight, $type);
            $lineHeight += 8;
            $counter++;
            if (is_int($counter / 12))
            {
                $pdf->addPage();
                $pdf->useTemplate($template);
                $pdf->setFont('sans-serif', 'BI', 10);

                $pdf->WriteText(50, 177, config('app.school_name'));
                $pdf->WriteText(200, 177, $user);
                $pdf->WriteText(50, 186, Carbon::now()->format('m/d/Y'));
                $pdf->WriteText(158, 186, strval($count));
                $pdf->WriteText(220, 186, $start->format('F, Y'));
                $lineHeight = 82;
                $pdf->setFont('sans-serif', 'R', 10);
            }
        }

        return $pdf;
    }

    public function tpt_report(Carbon $start, Carbon $end)
    {
        if (Auth::check() == true)
        {
            $user = Auth::user()->name;
        } else {
            $user = config(env('CONFIG_FILE', 'default').".reports_name");
        }

        $tests = Tpttest::query()
            ->where('complete', 1)
            ->whereBetween('date',[$start->startOfDay(), $end->endOfDay()])
            ->orderBy('date')
            ->with('student')->get();

        $pdf = self::create_pdf_file('L', 'Letter');
        $pdf->setSourceFile(public_path('forms/304h.pdf'));
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);
        $pdf->setFont('sans-serif', 'BI', 12);

        $pdf->WriteText(77, 33, config('app.school_name'));
        $pdf->WriteText(90, 39, $user);
        $pdf->WriteText(240, 39, Carbon::now()->format('m/d/Y'));
        $pdf->SetFontSize(14);
        $pdf->WriteText(136, 48, 'X');

        $counter = 1;
        $lineHeight = 90;
        $pdf->SetFontSize(9);
        foreach ($tests as $test)
        {
            if ($test->student)
            {
                $pdf->WriteText(25, $lineHeight, $test->student->full_name);
                $pdf->WriteText(80, $lineHeight, $test->student->permit_number ?: '');
                $pdf->WriteText(103, $lineHeight, $test->date->format('m/d/y'));
                $pdf->WriteText(125, $lineHeight, $test->date->format('Hi'));
                $pdf->WriteText(142, $lineHeight, $test->test_type);
                $pdf->WriteText(162, $lineHeight, $test->route);
                $pdf->WriteText(185, $lineHeight, $test->so_id ?: '');
                $pdf->WriteText(203, $lineHeight, $test->test_id ?: '');
                $pdf->WriteText(219, $lineHeight, $test->status);
                $pdf->WriteText(239, $lineHeight, true_false($test->walk_in));
                $pdf->WriteText(253, $lineHeight, true_false($test->substitute));
                $lineHeight += 5.7;

                if (is_int($counter/20))
                {
                    $pdf->addPage();
                    $pdf->useTemplate($template);
                    $pdf->setFont('sans-serif', 'BI', 12);

                    $pdf->WriteText(77, 33, config('app.school_name'));
                    $pdf->WriteText(90, 39, $user);
                    $pdf->WriteText(240, 39, Carbon::now()->format('m/d/Y'));
                    $pdf->SetFontSize(14);
                    $pdf->WriteText(136, 48, 'X');

                    $counter = 0;
                    $lineHeight = 90;
                    $pdf->SetFontSize(9);
                }
                $counter++;
            }
        }

        return $pdf;
    }

    public function dip_certificate(Student $student)
    {
        $seminars = $student->seminars()->get();
        $seminar = $seminars->first();

        $pdf = self::create_pdf_file('L', 'Letter');
        $pdf->setSourceFile(public_path('forms/DIPColorCertificate-2014.pdf'));
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);

        $pdf->setXY(52, 71);
        $pdf->AutosizeText($student->contract_name, 172, 'tangerine', 'B');
        $pdf->setFont('sans-serif', 'BI', 12);
        $pdf->WriteText(85,135, $seminar->employee->name ?? 'Instructor Name');
        $pdf->WriteText(85,157, config('app.school_name'));
        $pdf->WriteText(85,178, $seminar->date->format('m/d/Y'));

        $pdf->SetFont('sans-serif', 'B', 10);
        $pdf->WriteText(20, 180, "DL#: " . $student->permit_number ?? '');
        $pdf->WriteText(20, 185, "DOB: " . $student->display_birthdate ?? '');
        $pdf->WriteText(20, 190, "Course Length: 8 Hours");

        return $pdf;
    }

    public function dip_letter(Student $student)
    {
        $seminars = $student->seminars()->get();
        $seminar = $seminars->first();

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile(public_path('forms/point_reduction_2023.pdf'));
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);
        $pdf->setFont('sans-serif', 'BI', 12);

        $pdf->WriteText(21,39, $student->short_contract_name ?? '');
        $pdf->WriteText(53 ,115.5, $student->contract_name ?? '');
        $pdf->WriteText(32 ,123.5, $student->street ?? '');
        $pdf->WriteText(105 ,123.5, $student->zipcode->city ?? '');
        $pdf->WriteText(158 ,123.5, $student->zipcode->state ?? '');
        $pdf->WriteText(180 ,123.5, strval($student->zipcode->zipcode) ?? '');
        $pdf->WriteText(65 ,131, $student->permit_number ?? '');
        $pdf->WriteText(150 ,131, $student->display_birthdate ?? '');
        $pdf->WriteText(60 ,144.5, $seminar->date->format('m/d/Y') ?? '');
        $pdf->WriteText(21 ,157, $seminar->employee->name ?? '');

        $pdf->setFont('sans-serif', 'B', 10);
        $pdf->WriteText(42 ,227, config('app.school_name'));
        $pdf->WriteText(42 ,232, config('app.school_street'));
        $pdf->WriteText(44 ,240.5, config('app.school_csz'));
        $pdf->WriteText(42 ,245, config('app.school_phone'));

        return $pdf;
    }

    public function payroll_report($start)
    {
        $instructors = Employee::active()->where('user_level', 'Instructor')->get();

        $wk1_start = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
        $wk1_end = Carbon::createFromFormat('Y-m-d', $start)->addDays(6)->endOfDay();
        $wk2_start = Carbon::createFromFormat('Y-m-d', $start)->addWeek(1)->startOfDay();
        $wk2_end = Carbon::createFromFormat('Y-m-d', $start)->addDays(13)->endOfDay();

        $pdf = self::create_pdf_file('L');
        $pdf->setSourceFile(public_path('forms/PayrollReport2015.pdf'));
        $template = $pdf->importPage(1);
//        $pdf->useTemplate($template);
//        $pdf->setFont('sans-serif', 'BI', 12);

        foreach($instructors as $instructor)
        {
            $wk1_lessons = Lesson::whereBetween('start_time', [$wk1_start, $wk1_end])->where('employee_id', $instructor->id)->where('type','<>','CR Complete')->where('complete', true)->orderBy('start_time')->get();
            $wk2_lessons = Lesson::whereBetween('start_time', [$wk2_start, $wk2_end])->where('employee_id', $instructor->id)->where('type','<>','CR Complete')->where('complete', true)->orderBy('start_time')->get();
            // Week 1

            $pdf->AddPage();
            $pdf->useTemplate($template);
            $pdf->setFont('sans-serif', 'R', 10);

            $pdf->WriteText(25, 11, $instructor->name);
            $pdf->WriteText(215, 11, $wk1_start->format('m/d/Y') . '-' . $wk1_end->format('m/d/Y'));
            $sns = $ic = $rt = $cr = $kt =0;
            $lineHeight = 28.5;
            foreach($wk1_lessons as $lesson)
            {
                switch ($lesson->type)
                {
                    case 'SNS':
                        $sns++;
                        break;
                    case 'In-Car':
                        $ic++;
                        break;
                    case 'Road Test':
                        $rt++;
                        break;
                    case 'Knowledge Test':
                        $kt++;
                        break;
                    case 'CR Start':
                    case 'CR Complete':
                    case 'Classroom':
                        $cr++;
                        break;
                    default:
                        break;
                }

                $pdf->WriteText(15, $lineHeight, $lesson->start_time->format('m/d/y'));
                $pdf->WriteText(33, $lineHeight, optional($lesson->student)->short_contract_name ?? '');
                $pdf->WriteText(97, $lineHeight, $lesson->type ?? '');
                $pdf->WriteText(133, $lineHeight, $lesson->start_time->format('H:i'));
                $pdf->WriteText(158, $lineHeight, $lesson->end_time->format('H:i'));
                $pdf->WriteText(183, $lineHeight, strval($lesson->total_time) .' hrs');
                $pdf->WriteText(198, $lineHeight, strval($lesson->begin_mileage));
                $pdf->WriteText(222, $lineHeight, strval($lesson->end_mileage));
                $pdf->WriteText(247, $lineHeight, strval($lesson->mileage));
                $lineHeight += 5.1;
            }

            $pdf->WriteText(10, 200, 'SNS= ' . $sns);
            $pdf->WriteText(40, 200, '#1= ' . $ic);
            $pdf->WriteText(70, 200, 'RT= ' . $rt);
            $pdf->WriteText(100, 200, 'KT= ' . $kt);
            $pdf->WriteText(130, 200, 'Classroom= ' . $cr);
            // Week 2
            $pdf->AddPage();
            $pdf->useTemplate($template);
            $pdf->setFont('sans-serif', 'R', 10);

            $pdf->WriteText(25, 11, $instructor->name);
            $pdf->WriteText(215, 11, $wk2_start->format('m/d/Y') . '-' . $wk2_end->format('m/d/Y'));
            $sns = $ic = $rt = $cr = $kt = 0;
            $lineHeight = 28.5;
            foreach($wk2_lessons as $lesson)
            {
                switch ($lesson->type)
                {
                    case 'SNS':
                        $sns++;
                        break;
                    case 'In-Car':
                        $ic++;
                        break;
                    case 'Road Test':
                        $rt++;
                        break;
                    case 'Knowledge Test':
                        $kt++;
                        break;
                    case 'CR Start':
                    case 'CR Complete':
                    case 'Classroom':
                        $cr++;
                        break;
                    default:
                        break;
                }
                $pdf->WriteText(15, $lineHeight, $lesson->start_time->format('m/d/y'));
                $pdf->WriteText(33, $lineHeight, optional($lesson->student)->short_contract_name ?? '');
                $pdf->WriteText(97, $lineHeight, $lesson->type ?? '');
                $pdf->WriteText(133, $lineHeight, $lesson->start_time->format('H:i'));
                $pdf->WriteText(158, $lineHeight, $lesson->end_time->format('H:i'));
                $pdf->WriteText(183, $lineHeight, strval($lesson->total_time) .' hrs');
                $pdf->WriteText(198, $lineHeight, strval($lesson->begin_mileage));
                $pdf->WriteText(222, $lineHeight, strval($lesson->end_mileage));
                $pdf->WriteText(247, $lineHeight, strval($lesson->mileage));
                $lineHeight += 5.1;
            }
            $pdf->WriteText(10, 200, 'SNS= ' . $sns);
            $pdf->WriteText(40, 200, '#1= ' . $ic);
            $pdf->WriteText(70, 200, 'RT= ' . $rt);
            $pdf->WriteText(100, 200, 'KT= ' . $kt);
            $pdf->WriteText(130, 200, 'Classroom= ' . $cr);
        }


        return $pdf;
    }

    public function enrollment_report(Carbon $start, Carbon $end)
    {
        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setSourceFile(public_path('forms/EnrollmentReport.pdf'));
        $template = $pdf->importPage(1);
//        $pdf->useTemplate($template);
//        $pdf->setFont('sans-serif', 'BI', 12);

        $payments = Payment::whereBetween('date',[$start->startOfDay(),$end->endOfDay()])->where('remarks','like','%Online Registration Payment%')->with('student')->get();
        $grouped = $payments->groupBy('student.created_by');
        $counter = 1;
        foreach ($grouped as $key => $value)
        {
            $pdf->AddPage();
            $pdf->useTemplate($template);
            $pdf->setFont('sans-serif', 'R', 10);

            $pdf->WriteText(25, 12, Employee::find($key)->name);
            $pdf->WriteText(170, 12, $start->format('m/d/y') .'-'. $end->format('m/d/y'));

            $sorted = $value->sortBy('student.type');
            $lineHeight = 28.5;
            $ca = $cb = $cc = $dip = 0;
            foreach ($sorted as $pay)
            {
                switch ($pay->student->type)
                {
                    case 'Course A':
                        $ca++;
                        break;
                    case 'Course B':
                        $cb++;
                        break;
                    case 'Course C':
                        $cc++;
                        break;
                    case 'DIP':
                    case 'Point Reduction':
                    case 'Insurance Reduction':
                        $dip++;
                        break;
                    default:
                        break;
                }
                $pdf->WriteText(10, $lineHeight, $pay->date->format('m/d/y'));
                $pdf->WriteText(38, $lineHeight, $pay->student->short_contract_name);
                $pdf->WriteText(108, $lineHeight, strval($pay->student->id));
                $pdf->WriteText(130, $lineHeight, $pay->student->type ?: '');
                $pdf->WriteText(164, $lineHeight, money($pay->amount));
                $lineHeight += 5.1;

                if (is_int($counter/46))
                {
                    $pdf->AddPage();
                    $pdf->useTemplate($template);
                    $pdf->setFont('sans-serif', 'R', 10);

                    $pdf->WriteText(25, 12, Employee::find($key)->name);
                    $pdf->WriteText(170, 12, $start->format('m/d/y') .'-'. $end->format('m/d/y'));
                    $lineHeight = 28.5;
                }
                $counter++;
            }

            $pdf->WriteText(10, 270, 'A= ' . $ca);
            $pdf->WriteText(40, 270, 'B= ' . $cb);
            $pdf->WriteText(70, 270, 'C= ' . $cc);
            $pdf->WriteText(100, 270, 'DIP= ' . $dip);
            $pdf->WriteText(130, 270, 'LxL= ');
        }


        return $pdf;
    }

    public function quarterly_income_report($quarter, $year)
    {
        switch ($quarter) {
            case 1:
                $months = FIRST_QUARTER;
                break;
            case 2:
                $months = SECOND_QUARTER;
                break;
            case 3:
                $months = THIRD_QUARTER;
                break;
            case 4:
                $months = FOURTH_QUARTER;
                break;
        }

        $start_date = Carbon::createFromDate($year, $months[0]);

        $end_date = Carbon::createFromDate($year, $months[2]);

        $payments = Payment::whereBetween('date', [$start_date->firstOfMonth(), $end_date->endOfMonth()])->get();
        $filtered = $payments->reject(function ($payment) {
            return $payment->type == 'Credit';
        });

        $grouped = $filtered->groupBy(function ($payment) {
            return $payment->date->format('m');
        });

        $pdf = self::create_pdf_file('P', 'Letter');
        $pdf->setFont('sans-serif', 'R', 10);
        $pdf->AddPage();
        $lineHeight = 50;
        $total = 0;
        foreach ($grouped as $key => $group)
        {
            $total = $total + $group->sum('amount');
            $pdf->WriteText(50, $lineHeight, $key . ": " . money($group->sum('amount')));
            $lineHeight += 5;
        }
        $pdf->WriteText(50, $lineHeight+10, 'Quarter Total: ' . money($total));
        return $pdf;
    }

    public function workzone_report($start, $end)
    {
        //dd(auth()->user());
        $seminars = Seminar::whereBetween('date', [$start, $end])->get();
        $pdf = self::create_pdf_file('L', 'Letter');
        $pdf->setSourceFile(public_path('templates/wzsf2024.pdf'));
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);
        $pdf->SetFont('freesans', 'bi', '11');
        $lineHeight = 69;
        $pdf->WriteText(45, 33.25, auth()->user()->firstname . ' ' . auth()->user()->lastname);
        $pdf->WriteText(140, 33.25, today()->format('m/d/Y'));
        $counter = 1;
        foreach ($seminars as $seminar) {
            $students = $seminar->students;
            foreach ($students as $student) {
                $pdf->WriteText(10, $lineHeight, $seminar->date->format('m/d/Y'));
                $pdf->WriteText(42, $lineHeight, \Illuminate\Support\Str::limit(config('app.school_name'), 20, $end='...'));
                $pdf->WriteText(90, $lineHeight, $seminar->employee->firstname . ' ' . $seminar->employee->lastname);
                $pdf->WriteText(138, $lineHeight, $student->firstname . ' ' . $student->lastname);
                $pdf->WriteText(191, $lineHeight, $student->permit_number ?: '');
                $lineHeight += 5.4;
                if (is_int($counter/21))
                {
                    $pdf->AddPage('L');
                    $pdf->useTemplate($template);
                    $pdf->SetFont('freesans', 'bi', 11);

                    $pdf->WriteText(45, 33.25, auth()->user()->firstname . ' ' . auth()->user()->lastname);
                    $pdf->WriteText(140, 33.25, today()->format('m/d/Y'));

                    $lineHeight = 69;
                    //$pdf->SetFont('freesans', '', 10);
                }
                $counter++;
            }
            //$pdf->AddPage();
            //$pdf->useTemplate($template);
        }

        return $pdf;
    }

    public function set_configuration_variables()
    {
        $defaultConfig = (new MConfig\ConfigVariables())->getDefaults();
        $this->fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new MConfig\FontVariables())->getDefaults();
        $this->fontData = $defaultFontConfig['fontdata'];
    }

    private function create_pdf_file($orientation = 'P', $format = 'Letter')
    {
        self::set_configuration_variables();

        $pdf = new Mpdf([
            'fontDir' => array_merge($this->fontDirs, [
                public_path('fonts/')
            ]),
            'fontdata' => $this->fontData + [
                    'dancingscript' => [
                        'R' => 'DancingScript-Regular.ttf',
                        'B' => 'DancingScript-Bold.ttf',
                    ],
                    'tangerine' => [
                        'R' => 'Tangerine-Regular.ttf',
                        'B' => 'Tangerine-Bold.ttf'
                    ]
                ],
            'default-font' => 'sans-serif',
            'orientation' => $orientation,
            'format' => $format
        ]);

        return $pdf;
    }

    private function addLayoutGrid($pdf)
    {
        $pdf->setDrawColor(50, 50, 50);
        for ($w = 0; $w <= 350; $w += 5) {
            $pdf->line($w, 0, $w, 350);
        }
        for ($h = 0; $h <= 350; $h += 5) {
            $pdf->line(0, $h, 350, $h);
        }

        return $pdf;
    }

    private function process_signature_image(Student $student)
    {
        if (!empty($student->signature_image)){
            \Storage::put("signatures/$student->id.png", base64_decode(Str::of($student->signature_image)->after(',')));
            imagecreatefrompng(storage_path() . "/app/private/signatures/$student->id.png");
        }
    }

    private function selectContract(Student $student, Seminar $seminar = null, $data = null)
    {
        switch ($student->type)
        {
            case StudentTypes::COURSE_A:
            case 'Beginner':
            case 'Beginner Basic':
            case StudentTypes::COURSE_B:
            case 'Beginner Plus':
            case StudentTypes::COURSE_C:
            case 'Beginner Premium':
                $contract = $this->beginner_contract($student, $seminar, $data);
                break;
            case StudentTypes::POINT_REDUCTION:
            case StudentTypes::DIP:
                $contract = $this->point_reduction_contract($student, $seminar, $data);
                break;
            case StudentTypes::LxL:
            case StudentTypes::LXL:
                $contract = $this->btw_contract($student, $data);
                break;
            case StudentTypes::ROAD_TESTING:
                $contract = $this->skills_contract($student, $data);
                break;
            case StudentTypes::DRIVER_EVALUATION:
                $contract = $this->driver_evaluation_contract($student, $data);
                break;
            case StudentTypes::INSTRUCTOR_TRAINING:
                $contract = $this->instructor_course_contract($student, $data);
                break;
            case 'Mature Operator':
            case 'Insurance Reduction':
                $contract = $this->insurance_reduction_contract($student, $seminar, $data);
                break;
            case StudentTypes::LADS_HAND:
                $contract = $this->hand_controls_contract($student, $data);
                break;
            case StudentTypes::PERMIT_TESTING:
                $contract = $this->knowledge_contract($student, $data);
                break;
        }

        return $contract;
    }

}
