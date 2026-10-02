<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Updates;               // modelo das publicações
use App\Models\User;                  // modelo dos usuários
use Illuminate\Support\Facades\Log;

class SearchSuggestionController extends Controller
{
    /**
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggest(Request $request)
    {
       
        $term = trim($request->query('q', ''));

     
        if (strlen($term) < 2) {
            return response()->json(['suggestions' => []]);
        }

       
        $updatesQuery = Updates::query()
            ->where('description', 'LIKE', "%{$term}%")
            ->select('description')
            ->limit(8);

        $usersQuery = User::query()
            ->where('name', 'LIKE', "%{$term}%")
            ->select('name')
            ->limit(8);

       
        $suggestions = collect()
            ->merge($updatesQuery->pluck('description'))
            ->merge($usersQuery->pluck('name'))
            ->unique()
            ->sortBy(fn($s) => strlen($s))
            ->take(8)
            ->values()
            ->toArray();
		
        return response()->json(['suggestions' => $suggestions]);
    }
}
