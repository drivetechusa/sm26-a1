<?php

use App\Models\Vehicle;
use Livewire\Component;

new class extends Component {
    public Vehicle $vehicle;
};
?>

<div>
    {{$vehicle->name}}
</div>
