@props(['value' => true])

@if($value)
    <flux:icon.check-circle class="text-green-700"/>
@else
    <flux:icon.x-circle class="text-red-700" />
@endif
