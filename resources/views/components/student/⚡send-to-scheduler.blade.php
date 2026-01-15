<?php

use App\Models\Student;
use App\Models\Scheduler\Student as SchedulerStudent;
use Livewire\Component;

new class extends Component {
    public Student $student;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function sendToScheduler()
    {
        if (!empty($this->student->stu_web_id))
        {
            $schedulerStudent = SchedulerStudent::find($this->student->stu_web_id);
        } else {
            $schedulerStudent = new SchedulerStudent();
        }

        $schedulerStudent->school_id = config('app.default_school_scheduler_id');
        $schedulerStudent->firstname = $this->student->firstname;
        $schedulerStudent->middlename = $this->student->middlename;
        $schedulerStudent->lastname = $this->student->lastname;
        $schedulerStudent->suffix = $this->student->suffix;
        $schedulerStudent->address = $this->student->street;
        $schedulerStudent->address1 = $this->student->street1;
        $schedulerStudent->city = $this->student->zipcode->city;
        $schedulerStudent->state = $this->student->zipcode->state;
        $schedulerStudent->zip = $this->student->zipcode->zipcode;
        $schedulerStudent->phone_home = $this->student->phone;
        $schedulerStudent->phone_parent = $this->student->secondary_phone;
        $schedulerStudent->phone_student = $this->student->student_phone;
        $schedulerStudent->email_parent = $this->student->email;
        $schedulerStudent->email_student = $this->student->email_student;
        $schedulerStudent->dob = $this->student->dob;
        $schedulerStudent->start_date = $this->student->date_started;
        $schedulerStudent->end_date = $this->student->date_completed;
        $schedulerStudent->lpn = $this->student->permit_number;
        $schedulerStudent->lpn_doi = $this->student->issue_date;
        $schedulerStudent->username = $this->student->username;
        $schedulerStudent->drivetime = $this->student->drive_time_purchased;
        $schedulerStudent->drivetimecompleted = $this->student->drive_time_completed;
        $schedulerStudent->status = $this->student->status;
        $schedulerStudent->lxl = in_array($this->student->type, [\App\Enums\StudentTypes::LXL, \App\Enums\StudentTypes::LxL]) ? 1 : 0;
        $schedulerStudent->zone_id = $this->student->zone_id;
        $schedulerStudent->createdat = now();
        $schedulerStudent->updatedat = now();
        $schedulerStudent->created_by = 2;
        $schedulerStudent->updated_by = 2;
        $schedulerStudent->high_school = $this->student->high_school;
        $schedulerStudent->password = Hash::make($this->student->firstname . substr($this->student->phone, -4));

        if ($this->student->instructor_id)
        {
            $instructor = \App\Models\Scheduler\Instructor::where('dtdb_id', $this->student->instructor_id)->first();
            $schedulerStudent->instructor_id = $instructor->id;
        }

        $schedulerStudent->save();

        $this->student->update([
            'stu_web_id' => $schedulerStudent->id,
        ]);

        Flux::toast('Sent to scheduler');
        Flux::modals()->close();
        $this->dispatch("student-updated.{$this->student->id}");
    }
};
?>

<flux:navmenu.item icon="cloud-arrow-up" wire:click="sendToScheduler">Send To Scheduler</flux:navmenu.item>
