<?php

namespace App\Http\Controllers;

use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookReviewController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return response()->json($request->user()->bookReviews);
        }
        return response()->json(BookReview::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();
        $validated['user_id'] = $request->user()->id;
        $review = BookReview::create($validated);

        return response()->json($review, 201);
    }

    public function show(string $id)
    {
        $review = BookReview::find($id);
        
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        return response()->json($review);
    }

    public function update(Request $request, string $id)
    {
        $review = BookReview::find($id);
        
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        // Check if the user owns the review
        if ($request->user()->id !== $review->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'book_title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'review' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $review->update($validator->validated());

        return response()->json($review);
    }

    public function destroy(Request $request, string $id)
    {
        $review = BookReview::find($id);
        
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        // Check if the user owns the review
        if ($request->user()->id !== $review->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}