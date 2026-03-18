<?php

namespace App\Http\Controllers;

use App\Services\SmartAiService;
use Illuminate\Http\Request;

class SmartAiController extends Controller
{
    protected $aiService;

    public function __construct(SmartAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Generate description for vendor.
     */
    public function generateDescription(Request $request)
    {
        $specs = $request->only(['title', 'length', 'width', 'capacity', 'engine', 'amenities']);
        $description = $this->aiService->generateBoatDescription($specs);

        return response()->json(['status' => 'success', 'description' => $description]);
    }

    /**
     * Generate reply for review.
     */
    public function generateReply(Request $request)
    {
        $reply = $this->aiService->generateReviewReply($request->customer_name, $request->review_text);

        return response()->json(['status' => 'success', 'reply' => $reply]);
    }
}
