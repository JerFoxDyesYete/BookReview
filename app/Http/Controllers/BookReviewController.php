<?php

namespace App\Http\Controllers;

use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = BookReview::all();
        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
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

        $review = BookReview::create($validator->validated());

        return response()->json($review, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = BookReview::find($id);
        
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        return response()->json($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $review = BookReview::find($id);
        
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = BookReview::find($id);
        
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}