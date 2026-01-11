<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    
    protected $fillable = [
        'full_name',
        'phone',
        'birth_date',
        'gender',
        'address',
    ];



    public function visits()
{
    return $this->hasMany(Visit::class);
}
public function needsReExamination()
{
    // بنجيب آخر زيارة للمريض
    $lastVisit = $this->visits()->latest()->first();

    if (!$lastVisit) return false;

    // لو آخر زيارة كانت من أقل من 14 يوم، يبقى يستحق إعادة كشف
    return $lastVisit->created_at->diffInDays(now()) <= 14;
}

}
