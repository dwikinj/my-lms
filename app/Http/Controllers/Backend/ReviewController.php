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

    public function AdminPendingReview()
    {
        $reviews = Review::with(['user', 'course', 'instructor'])->where('status', 0)->orderBy('id', 'desc')->get();
        return view('admin.backend.review.pending_review', compact('reviews'));
    } //end method

    public function AdminActiveReview()
    {
        $reviews = Review::with(['user', 'course', 'instructor'])->where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.backend.review.active_review', compact('reviews'));
    } //end method

    public function AdminUpdateStatusReview(Request $request)
    {
        $review_id = $request->input('review_id');
        $status = $request->input('status') ? 1 : 0;

        try {
            $review = Review::findOrFail($review_id);
            $review->update([
                'status' => $status,
            ]);

            // Mengembalikan respons JSON
            return response()->json([
                'message' => 'Review Status Updated Successfully',
                'alertType' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update review status.',
                'alertType' => 'error'
            ], 500);
        }
    } //end method
}
