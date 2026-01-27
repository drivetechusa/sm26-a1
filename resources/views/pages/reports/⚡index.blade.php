<?php

use App\Traits\PDF_forms;
use Carbon\Carbon;
use Livewire\Component;

new class extends Component {
    use PDF_forms;

    public $tpt_start;
    public $tpt_end;
    public $activity_start;
    public $activity_end;
    public $enrollment_start;
    public $enrollment_end;
    public $workzone_start;
    public $workzone_end;
    public $payroll_start;
    public $payroll_end;
    public $current_year;
    public $quarter;
    public $quarter_year;

    public function mount()
    {
        $this->current_year = today()->year;
    }

    public function runTptReport()
    {
        $this->validate([
            'tpt_start' => 'required|date',
            'tpt_end' => 'required|date'
        ]);
        $start = \Carbon\Carbon::parse($this->tpt_start);
        $end = \Carbon\Carbon::parse($this->tpt_end);
        $pdf = $this->tpt_report($start, $end);
        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output();
        }, 'tpt-report.pdf');

    }

    public function runActivityReport()
    {
        $this->validate([
            'activity_start' => 'required|date',
            'activity_end' => 'required|date'
        ]);
        $pdf = $this->monthly_activity_report(Carbon::parse($this->activity_start), Carbon::parse($this->activity_end));
        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output();
        }, 'activity-report.pdf');
    }

    public function runEnrollmentReport()
    {
        $this->validate([
            'enrollment_start' => 'required|date',
            'enrollment_end' => 'required|date'
        ]);
        $start = \Carbon\Carbon::parse($this->enrollment_start);
        $end = \Carbon\Carbon::parse($this->enrollment_end);
        $pdf = $this->enrollment_report($start, $end);
        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output();
        }, 'enrollment-report.pdf');
    }

    public function runPayrollReport()
    {
        $this->validate([
            'payroll_start' => 'required|date',
        ]);
        $start = \Carbon\Carbon::parse($this->payroll_start);
        $pdf = $this->payroll_report($start);
        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output();
        }, 'payroll-report.pdf');
    }

    public function runWorkzoneReport()
    {
        $this->validate([
            'workzone_start' => 'required|date',
            'workzone_end' => 'required|date'
        ]);

        return (new \App\Exports\WorkzoneSafetyExport($this->workzone_start, $this->workzone_end))->download('workzone_safety_' . time() . '.xlsx');

    }

    public function quarter_income_report()
    {
        $this->validate([
            'quarter' => 'required',
            'quarter_year' => 'required'
        ]);
        $pdf = $this->quarterly_income_report($this->quarter, $this->quarter_year);
        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output();
        }, 'quarterly-income-report.pdf');
    }
};
?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">3rd Party Tester</flux:heading>
            </div>
            <div class="space-y-6">
                <flux:input label="Start" type="date" wire:model="tpt_start"/>
                <flux:input label="End" type="date" wire:model="tpt_end"/>
            </div>
            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" wire:click="runTptReport">Run Report</flux:button>
            </div>
        </flux:card>

        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">SC Activity Report</flux:heading>
            </div>

            <div class="space-y-6">
                <flux:input label="Start" type="date" wire:model="activity_start"/>
                <flux:input label="End" type="date" wire:model="activity_end"/>
            </div>

            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" wire:click="runActivityReport">Run Report</flux:button>
            </div>
        </flux:card>
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">Enrollment Report</flux:heading>
            </div>

            <div class="space-y-6">
                <flux:input label="Start" type="date" wire:model="enrollment_start"/>
                <flux:input label="End" type="date" wire:model="enrollment_end"/>
            </div>

            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" wire:click="runEnrollmentReport">Run Report</flux:button>
            </div>
        </flux:card>
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">SC Workzone Report</flux:heading>
            </div>

            <div class="space-y-6">
                <flux:input label="Start" type="date" wire:model="workzone_start"/>
                <flux:input label="End" type="date" wire:model="workzone_end"/>
            </div>

            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" wire:click="runWorkzoneReport">Run Report</flux:button>
            </div>
        </flux:card>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">Quarterly Income</flux:heading>
            </div>
            <div class="space-y-6">
                <flux:select wire:model="quarter" label="Quarter">
                    <flux:select.option>Select Quarter...</flux:select.option>
                    <flux:select.option value="1">Jan - Mar</flux:select.option>
                    <flux:select.option value="2">Apr - Jun</flux:select.option>
                    <flux:select.option value="3">Jul - Sep</flux:select.option>
                    <flux:select.option value="4">Oct - Dec</flux:select.option>
                </flux:select>
                <flux:select wire:model="quarter_year" label="Year">
                    <flux:select.option>Select Year...</flux:select.option>
                    @for ($i = 0; $i <= 10; $i++)
                        <flux:select.option value="{{$current_year - $i}}">{{$current_year - $i}}</flux:select.option>
                    @endfor
                </flux:select>

            </div>
            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" wire:click="quarter_income_report">Run Report</flux:button>
            </div>
        </flux:card>

        {{--        <flux:card class="space-y-6">--}}
        {{--            <div>--}}
        {{--                <flux:heading size="lg">SC Activity Report</flux:heading>--}}
        {{--            </div>--}}

        {{--            <div class="space-y-6">--}}
        {{--                <flux:input label="Start" type="date" wire:model="activity_start"/>--}}
        {{--                <flux:input label="End" type="date" wire:model="activity_end"/>--}}
        {{--            </div>--}}

        {{--            <div class="space-y-2">--}}
        {{--                <flux:button variant="primary" class="w-full" wire:click="runActivityReport">Run Report</flux:button>--}}
        {{--            </div>--}}
        {{--        </flux:card>--}}
        {{--        <flux:card class="space-y-6">--}}
        {{--            <div>--}}
        {{--                <flux:heading size="lg">Enrollment Report</flux:heading>--}}
        {{--            </div>--}}

        {{--            <div class="space-y-6">--}}
        {{--                <flux:input label="Start" type="date" wire:model="enrollment_start"/>--}}
        {{--                <flux:input label="End" type="date" wire:model="enrollment_end"/>--}}
        {{--            </div>--}}

        {{--            <div class="space-y-2">--}}
        {{--                <flux:button variant="primary" class="w-full" wire:click="runEnrollmentReport">Run Report</flux:button>--}}
        {{--            </div>--}}
        {{--        </flux:card>--}}
        {{--        <flux:card class="space-y-6">--}}
        {{--            <div>--}}
        {{--                <flux:heading size="lg">SC Workzone Report</flux:heading>--}}
        {{--            </div>--}}

        {{--            <div class="space-y-6">--}}
        {{--                <flux:input label="Start" type="date" wire:model="workzone_start"/>--}}
        {{--                <flux:input label="End" type="date" wire:model="workzone_end"/>--}}
        {{--            </div>--}}

        {{--            <div class="space-y-2">--}}
        {{--                <flux:button variant="primary" class="w-full" wire:click="runWorkzoneReport">Run Report</flux:button>--}}
        {{--            </div>--}}
        {{--        </flux:card>--}}
    </div>
</div>
