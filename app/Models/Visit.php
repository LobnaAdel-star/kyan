<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'type',
        'fees',
        'status',
    ];




    public function patient()
{
    return $this->belongsTo(Patient::class);
}

public function prescription()
{
    return $this->hasOne(Prescription::class);
}

public function medicalTests()
{
    return $this->hasMany(MedicalTest::class);
}

}
