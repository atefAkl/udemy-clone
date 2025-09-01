<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{

    /**
     * Get authenticated admin user
     */
    protected function getAuthUser()
    {
        return auth()->user();
    }

    /**
     * Return success response for API calls
     */
    protected function successResponse($message, $data = null, $status = 200)
    {
        $response = ['success' => true, 'message' => $message];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Return error response for API calls
     */
    protected function errorResponse($message, $errors = null, $status = 400)
    {
        $response = ['success' => false, 'message' => $message];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Return paginated response for API calls
     */
    protected function paginatedResponse($data, $message = 'Data retrieved successfully')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ]);
    }

    /**
     * Handle common search and filter logic
     */
    protected function applyFilters($query, Request $request, array $searchable = [])
    {
        // Search functionality
        if ($request->filled('search') && !empty($searchable)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchable, $searchTerm) {
                foreach ($searchable as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', boolval($request->status));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        return $query;
    }

    /**
     * Get common dashboard statistics
     */
    protected function getDashboardStats()
    {
        return [
            'users_count' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('is_active', true)->count(),
            'courses_count' => \App\Models\Course::count(),
            'published_courses' => \App\Models\Course::where('status', 'published')->count(),
            'categories_count' => \App\Models\Category::count(),
            'reviews_count' => \App\Models\Review::count(),
            'enrollments_count' => \App\Models\Enrollment::count(),
        ];
    }
}
