<?php

use App\Models\Seminar;
use App\Models\Scheduler\Student as SchedulerStudent;
use Livewire\Component;

new class extends Component {
    public Seminar $seminar;

    public function sendToScheduler()
    {
        $students = $this->seminar->students;
        foreach ($students as $student)
        {
            if (!empty($student->stu_web_id))
            {
                $schedulerStudent = SchedulerStudent::find($student->stu_web_id);
            } else {
                $schedulerStudent = new SchedulerStudent();
            }

            $schedulerStudent->school_id = config('app.default_school_scheduler_id');
            $schedulerStudent->firstname = $student->firstname;
            $schedulerStudent->middlename = $student->middlename;
            $schedulerStudent->lastname = $student->lastname;
            $schedulerStudent->suffix = $student->suffix;
            $schedulerStudent->address = $student->street;
            $schedulerStudent->address1 = $student->street1;
            $schedulerStudent->city = $student->zipcode->city;
            $schedulerStudent->state = $student->zipcode->state;
            $schedulerStudent->zip = $student->zipcode->zipcode;
            $schedulerStudent->phone_home = $student->phone;
            $schedulerStudent->phone_parent = $student->secondary_phone;
            $schedulerStudent->phone_student = $student->student_phone;
            $schedulerStudent->email_parent = $student->email;
            $schedulerStudent->email_student = $student->email_student;
            $schedulerStudent->dob = $student->dob;
            $schedulerStudent->start_date = $student->date_started;
            $schedulerStudent->end_date = $student->date_completed;
            $schedulerStudent->lpn = $student->permit_number;
            $schedulerStudent->lpn_doi = $student->issue_date;
            $schedulerStudent->username = $student->username;
            $schedulerStudent->drivetime = $student->drive_time_purchased;
            $schedulerStudent->drivetimecompleted = $student->drive_time_completed;
            $schedulerStudent->status = $student->status;
            $schedulerStudent->lxl = in_array($student->type, [\App\Enums\StudentTypes::LXL, \App\Enums\StudentTypes::LxL]) ? 1 : 0;
            $schedulerStudent->zone_id = $student->zone_id;
            $schedulerStudent->createdat = now();
            $schedulerStudent->updatedat = now();
            $schedulerStudent->created_by = 2;
            $schedulerStudent->updated_by = 2;
            $schedulerStudent->high_school = $student->high_school;
            $schedulerStudent->password = Hash::make($student->firstname . substr($student->phone, -4));

            if ($student->instructor_id)
            {
                $instructor = \App\Models\Scheduler\Instructor::where('dtdb_id', $student->instructor_id)->first();
                $schedulerStudent->instructor_id = $instructor->id;
            }

            $schedulerStudent->save();

            $student->update([
                'stu_web_id' => $schedulerStudent->id,
            ]);
        }
        Flux::toast('Sent to scheduler');
        Flux::modals()->close();
    }
};
?>

<flux:menu.item icon="cloud-arrow-up" wire:click="sendToScheduler">Send To Scheduler</flux:menu.item>
