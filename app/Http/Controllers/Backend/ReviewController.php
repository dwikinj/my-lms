<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{

    public function StoreReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|exists:courses,id',
            'instructor_id' => 'nullable|integer|exists:users,id', // Pastikan tabel users
            'user_id' => 'required|integer|exists:users,id',
            'comment' => 'required|string|min:3',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Review::create([
                'course_id' => $request->course_id,
                'instructor_id' => $request->instructor_id,
                'user_id' => $request->user_id,
                'comment' => $request->comment,
                'rating' => $request->rating ?? 5,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your review. Please try again.'
            ], 500);
        }
    } //end method
}
