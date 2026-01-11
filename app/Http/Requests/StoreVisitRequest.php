<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
        // بعدين ممكن نخليها:
        // return auth()->check();
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            
            'medicines' => 'required|array|min:1',
            'medicines.*.name' => 'required|string',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.duration' => 'nullable|string',

            'tests' => 'nullable|array',
            'tests.*' => 'string|max:255',
        ];
    }
}
