<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenAI\Client;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function generate(Request $request)
    {
        $apiKey = getenv('OPENAI_KEY');
        $ingredients = $request->get("ingredients");
        $cuisine = $request->get("cuisine");
        $prompt = "Generate a recipe based on the following information:\n
                    Ingredients: {$ingredients}\n
                    Cuisine: {$cuisine}
                    ";
        
        $client = \OpenAI::client($apiKey);
        $result = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'temperature'=>0.3,
            'max_tokens' => 500,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert chef who has expertise in different kind of cuisines. The generated Recipe should have only the following headings: Recipe name, Ingredients and Instructions'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
        $response = $result->choices[0]->message->content;
        
        return response()->json([
            'status' => 'OK',
            'message' => $response,
        ]);
    }
}
