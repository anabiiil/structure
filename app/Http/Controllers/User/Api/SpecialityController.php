<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\Speciality;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class SpecialityController extends Controller
{
    /**
     * Get all specialities
     */
    public function index(): JsonResponse
    {
        try {
            $specialities = Speciality::select('id', 'name', 'desc', 'logo', 'parent_id')
                ->orderBy('name', 'asc')
                ->get();

            return ApiResponse::success(
                $specialities,
                'Specialities retrieved successfully'
            );

        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve specialities', $e->getMessage(), 500);
        }
    }

    /**
     * Get specialities with hierarchical structure (parent with children)
     */
    public function hierarchical(): JsonResponse
    {
        try {
            $specialities = Speciality::with('children')
                ->whereNull('parent_id')
                ->select('id', 'name', 'desc', 'logo', 'parent_id')
                ->orderBy('name', 'asc')
                ->get();

            return ApiResponse::success(
                $specialities,
                'Hierarchical specialities retrieved successfully'
            );

        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve hierarchical specialities', $e->getMessage(), 500);
        }
    }
}
