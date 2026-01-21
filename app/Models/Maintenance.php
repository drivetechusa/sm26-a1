<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceFactory> */
    use HasFactory;

    protected $table = 'maintenance';

    protected $fillable = [
        'vehicle_id',
        'maintenancetype_id',
        'mileage',
        'description',
        'employee_id',
        'cost',
        'date',
        'vendor',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'mileage' => 'decimal:1',
            'cost' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenancetype_id');
    }
}
