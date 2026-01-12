<?php

use App\Models\Student;
use App\Models\Charge;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    public Student $student;

    #[Computed]
    public function charges()
    {
        $query = Charge::query()->where('student_id', $this->student->id)->orderBy('entered', 'desc');
        return $query->paginate(7, pageName: 'charges-page');
    }

    #[Computed]
    public function payments()
    {
        $query = Payment::query()->where('student_id', $this->student->id)->orderBy('date', 'desc');
        return $query->paginate(7, pageName: 'payments-page');
    }
};
?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div>Charges Table</div>
        <div>Payments Table</div>
    </div>
</div>
