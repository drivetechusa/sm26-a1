<?php

namespace App\Livewire\Forms;

use App\Models\Vehicle;
use Livewire\Form;

class VehicleForm extends Form
{
    public ?Vehicle $vehicle = null;

    public ?string $name = null;

    public ?int $year = null;

    public ?string $make = null;

    public ?string $model = null;

    public ?string $vin = null;

    public ?string $tag_number = null;

    public ?float $mileage = null;

    public ?bool $active = true;

    public ?string $date_purchased = null;

    public ?float $purchase_price = null;

    public ?string $date_sold = null;

    public ?float $selling_price = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'make' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'max:255'],
            'tag_number' => ['nullable', 'string', 'max:255'],
            'mileage' => ['nullable', 'numeric', 'min:0'],
            'active' => ['required', 'boolean'],
            'date_purchased' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'date_sold' => ['nullable', 'date'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function setVehicle(Vehicle $vehicle): void
    {
        $this->vehicle = $vehicle;

        $this->name = $vehicle->name;
        $this->year = $vehicle->year;
        $this->make = $vehicle->make;
        $this->model = $vehicle->model;
        $this->vin = $vehicle->vin;
        $this->tag_number = $vehicle->tag_number;
        $this->mileage = $vehicle->mileage;
        $this->active = $vehicle->active;
        $this->date_purchased = $vehicle->date_purchased?->format('Y-m-d');
        $this->purchase_price = $vehicle->purchase_price;
        $this->date_sold = $vehicle->date_sold?->format('Y-m-d');
        $this->selling_price = $vehicle->selling_price;
    }

    public function store(): Vehicle
    {
        $this->validate();

        return Vehicle::create($this->only([
            'name',
            'year',
            'make',
            'model',
            'vin',
            'tag_number',
            'mileage',
            'active',
            'date_purchased',
            'purchase_price',
            'date_sold',
            'selling_price',
        ]));
    }

    public function update(): void
    {
        $this->validate();

        $this->vehicle->update($this->only([
            'name',
            'year',
            'make',
            'model',
            'vin',
            'tag_number',
            'mileage',
            'active',
            'date_purchased',
            'purchase_price',
            'date_sold',
            'selling_price',
        ]));
    }
}
