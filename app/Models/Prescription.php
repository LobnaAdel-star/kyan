<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'visit_id',
        'patient_id',
        'diagnosis',
        'doctor_notes',
    ];



    public function items()
{
    return $this->hasMany(PrescriptionItem::class);
}

}
