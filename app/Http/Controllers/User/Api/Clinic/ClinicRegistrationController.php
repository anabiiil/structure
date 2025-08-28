<?php

namespace App\Http\Controllers\User\Api\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClinicRegistrationController extends Controller
{
    /**
     * Handle clinic registration request
     */
    public function register(Request $request): JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'clinic_name' => 'required|string|max:220',
            'google_map_location_url' => 'nullable|string|url',
            'specialities' => 'required|array',
            'specialities.*' => 'exists:specialities,id',
            'purpose_note' => 'nullable|string',
            'phone' => 'required|string|max:30|unique:clinics,phone',
            'whatsapp_number' => 'nullable|string|max:30',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        try {
            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('clinic-images', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Create clinic record
            $clinic = Clinic::create([
                'name' => $request->clinic_name,
                'clinic_name' => $request->clinic_name,
                'google_map_location_url' => $request->google_map_location_url,
                'purpose_note' => $request->purpose_note,
                'phone' => $request->phone,
                'whatsapp_number' => $request->whatsapp_number,
                'status' => 'pending'
            ]);

            // Attach specialities to the clinic
            if (!empty($request->specialities)) {
                $clinic->specialities()->attach($request->specialities);
            }

            return ApiResponse::success(
                [
                    'clinic_id' => $clinic->id,
                    'status' => 'pending'
                ],
                'Clinic registration request submitted successfully. We will review your request and get back to you soon.'
            );

        } catch (\Exception $e) {
            return ApiResponse::error('Failed to submit registration request', $e->getMessage(), 500);
        }
    }
}
