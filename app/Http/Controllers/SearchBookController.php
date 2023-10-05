<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class SearchBookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            // Get the search criteria from the request
            $title = $request->input('title');
            $author = $request->input('author');
            $genre = $request->input('genre');

            // Query the books based on the search criteria
            $books = Book::query()
                ->when($title, function ($query) use ($title) {
                    $query->where('title', 'like', '%'.$title.'%');
                })
                ->orWhere(function ($query) use ($author) {
                    $query->where('author', 'like', '%'.$author.'%');
                })
                ->orWhere(function ($query) use ($genre) {
                    $query->where('genre', $genre);
                })
                ->select('id', 'title', 'cover', 'created_at')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => trans('general.get'),
                'data' => $books,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
