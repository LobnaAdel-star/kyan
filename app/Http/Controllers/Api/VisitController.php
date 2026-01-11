<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    public function store(StoreVisitRequest $request)
    {
        // 1. الـ Validation (أساسي لأي Senior)
        $validated = $request->validated();

        // 2. استخدام الـ Database Transaction (عشان نضمن إن كل حاجة تتسيف أو مفيش حاجة تتسيف خالص)
        return DB::transaction(function () use ($request, $validated) {
            $doctor = $request->user();

            $patient = Patient::findOrfail($validated['patient_id']);

            // 3. الـ Smart Logic: تحديد نوع الزيارة والسعر أوتوماتيك
            $isReEx = $patient->needsReExamination();
            
            $visit = Visit::create([
                'patient_id' => $patient->id,
                 'doctor_id' => $doctor->id,
                'type' => $isReEx ? 're-examination' : 'examination',
                'fees' => $isReEx ? 0 : 200, // لو إعادة كشف السعر 0، لو كشف جديد 200
                'status' => 'completed',
            ]);

            // 4. تسجيل الروشتة والأدوية
            $prescription = $visit->prescription()->create([
                'patient_id' => $patient->id,
                'diagnosis' => $validated['diagnosis'],
            ]);

            foreach ($validated['medicines'] as $med) {
                $prescription->items()->create([
                    'medicine_name' => $med['name'],
                    'dosage' => $med['dosage'],
                    'duration' => $med['duration'] ?? 'Not specified',
                ]);
            }

            // 5. تسجيل التحاليل لو موجودة
            if (!empty($validated['tests'])) {
                foreach ($validated['tests'] as $testName) {
                    $visit->medicalTests()->create([
                        'test_name' => $testName,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Visit recorded successfully!',
                'visit_type' => $visit->type,
                'fees' => $visit->fees,
                'visit_id' => $visit->id
            ], 201);
        });
    }
}
