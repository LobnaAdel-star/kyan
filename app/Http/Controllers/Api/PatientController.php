<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // 1. عرض كل المرضى مع إمكانية البحث (زي ما موجود في كيان)
    public function index(Request $request)
    {
        $query = Patient::query();

        // بحث بالاسم أو رقم التليفون (Senior Touch: Search Logic)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // عرض أحدث المرضى أولاً مع Pagination
        $patients = $query->latest()->paginate(10);

        return response()->json($patients);
    }

    // 2. عرض التاريخ الطبي الكامل للمريض (Medical History)
    public function show($id)
    {
        // بنجيب المريض مع كل زياراته وروشتاته وتحاليله في Query واحدة (Eager Loading)
        $patient = Patient::findOrFail($id);
        $visits = $patient->visits()
    ->with([
        'prescription.items',
        'medicalTests'
    ])
    ->latest()
    ->paginate(10);


        return response()->json([
    'patient' => $patient,
    'visits' => $visits,
    'history_summary' => [
        'total_visits' => $patient->visits()->count(),
        'last_visit' => $patient->visits()
            ->latest()
            ->first()?->created_at?->format('Y-m-d'),
        'needs_re_examination' => $patient->needsReExamination()
    ]
]);

    }
}
