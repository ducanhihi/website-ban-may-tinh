<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function getProductFeedback($productId)
    {
        $feedbacks = Feedback::where('product_id', $productId)->get();
        return view('customer.view-detail', compact('feedbacks'));
    }

}
