<?php

namespace App\Http\Controllers;

use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Notifications\BookReviewNotification;

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
        Log::info('Starting review creation', ['user' => $request->user() ? $request->user()->id : null]);

        $validator = Validator::make($request->all(), [
            'book_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json($validator->errors(), 422);
        }

        try {
            $review = BookReview::create([
                ...$validator->validated(),
                'user_id' => $request->user()->id
            ]);

            Log::debug('Review created', ['review_id' => $review->id]);

            // Send notification for created review
            Log::info('Attempting to send creation notification');
            $request->user()->notify(new BookReviewNotification($review, 'created'));
            Log::info('Creation notification sent successfully');

            return response()->json($review, 201);

        } catch (\Exception $e) {
            Log::error('Review creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Server error'], 500);
        }
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

        // Store original data before update for comparison if needed
        $originalData = $review->toArray();

        $review->update($validator->validated());

        // Send notification for updated review
        $review->user->notify(new BookReviewNotification($review, 'updated'));

        return response()->json($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = BookReview::with(['user'])->find($id);

        if (!$review) {
            Log::error('Review not found for deletion', ['id' => $id]);
            return response()->json(['message' => 'Review not found'], 404);
        }

        try {
            // Prepare review data before deletion
            $reviewData = [
                'id' => $review->id,
                'book_title' => $review->book_title,
                'author' => $review->author,
                'review' => $review->review,
                'rating' => $review->rating,
                'user_id' => $review->user_id
            ];

            // Send notification with the review data
            if ($review->user) {
                $review->user->notify(new BookReviewNotification(
                    $reviewData,
                    'deleted'
                ));
                Log::info('Delete notification sent', ['review_id' => $review->id]);
            } else {
                Log::warning('No user associated with review', ['review_id' => $review->id]);
            }

            // Delete the review
            $review->delete();

            return response()->json(['message' => 'Review deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Delete process failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Deletion failed'], 500);
        }
    }
}