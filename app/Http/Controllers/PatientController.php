<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index(Request $request)
{
    $customerId = $request->get('user_id');

    $patients = Patient::where('customer_id', $customerId)->get()->map(function ($patient) {
        return [
            'id' => $patient->id,
            'customer_id' => $patient->customer_id,
            'name' => $patient->name,
            'birth_date' => $patient->birth_date, // Accessor formats it already
            'gender' => $patient->gender,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $patients
    ]);
}

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'birth_date' => 'required|date_format:d/m/Y',
                'gender' => 'required|in:Male,Female,Other',
            ], [
                'birth_date.date_format' => 'Birth date must be in DD/MM/YYYY format.',
            ]);

            // Frontend se aayi hui date 'd/m/Y' ko 'Y-m-d' me convert karo DB ke liye
            $formattedDate = Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d');

            $patient = Patient::create([
                'customer_id' => $request->get('user_id'),
                'name' => $request->name,
                'birth_date' => $formattedDate,
                'gender' => $request->gender,
            ]);

            return response()->json([
                'message' => 'Patient created successfully',
                'status' => 'success',
                'data' => [
                    'id' => $patient->id,
                    'customer_id' => $patient->customer_id,
                    'name' => $patient->name,
                    'birth_date' => $request->birth_date, // same as frontend sent
                    'gender' => $patient->gender,
                ]
            ]);
        } catch (ValidationException $e) {
            // Flatten error message to simple string response
            $errorMessage = collect($e->errors())->flatten()->first();
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $patient = Patient::where('customer_id', $request->get('user_id'))->findOrFail($id);

            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'birth_date' => 'sometimes|required|date_format:d/m/Y',
                'gender' => 'sometimes|required|in:Male,Female,Other',
            ], [
                'birth_date.date_format' => 'Birth date must be in DD/MM/YYYY format.',
            ]);

            $data = $request->only(['name', 'birth_date', 'gender']);

            if (isset($data['birth_date'])) {
                // Convert frontend date 'd/m/Y' to DB format 'Y-m-d'
                $data['birth_date'] = Carbon::createFromFormat('d/m/Y', $data['birth_date'])->format('Y-m-d');
            }

            $patient->update($data);

            $rawDate = $patient->getAttributes()['birth_date']; // raw 'Y-m-d' string

            return response()->json([
                'message' => 'Patient updated',
                'status' => true,
                'data' => [
                    'id' => $patient->id,
                    'customer_id' => $patient->customer_id,
                    'name' => $patient->name,
                    'birth_date' => Carbon::createFromFormat('Y-m-d', $rawDate)->format('d/m/Y'),
                    'gender' => $patient->gender,
                ]
            ]);
        } catch (ValidationException $e) {
            $errorMessage = collect($e->errors())->flatten()->first();
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ], 422);
        }
    }
}
