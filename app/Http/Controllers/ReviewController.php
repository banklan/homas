<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;

class ReviewController extends Controller
{
    public function createReview(Request $request, $id){
        $this->validate($request, [
            'title' => 'required|min:3|max:200',
            'detail' => 'required|min:5|max:500',
            'rating' => 'required|numeric|between:0, 5',
        ]);

        $user = auth('api')->user()->id;

        $review = new Review;
        $review->service_id = $id;
        $review->user_id = $user;
        $review->title = $request->title;
        $review->detail = $request->detail;
        $review->rating = $request->rating;
        $review->is_approved = 1;
        $review->save();
        $review = $review->fresh();

        return response()->json($review, 200);
    }

    public function deleteReview($id){
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    public function updateReview(Request $request, $id){
        $this->validate($request, [
            'review.title' => 'required|min:3|max:200',
            'review.detail' => 'required|min:5|max:500',
        ]);

        $review = Review::findOrFail($id);

        $review->update([
            $review->title = $request->review['title'],
            $review->detail = $request->review['detail']
        ]);

        return response()->json($review, 200);
    }

    public function getServiceReviews($id){
        $rev = Review::where('service_id', $id)->get();

        return response()->json($rev, 200);
    }
}
