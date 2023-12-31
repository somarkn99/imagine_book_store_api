<?php

namespace App\Http\Controllers;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct()
    {
        // Assign only to specific methods in this Controller
        $this->middleware(['auth:api', 'permission:Add-Book'])->only('store');
        $this->middleware(['auth:api', 'permission:Update-Book'])->only('update');
        $this->middleware(['auth:api', 'permission:Delete-Book'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Get the 'genre' and 'author' query parameters from the request
            $genre = $request->query('genre');
            $author = $request->query('author');

            // Use the custom method in the model to filter books
            $query = Book::filterByGenreAndAuthor($genre, $author);

            // Use pagination to limit the number of results per page
            $perPage = $request->query('per_page', 10); // Number of items per page (default is 10)
            $books = $query->paginate($perPage);

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $data = $request->validated();
            $data['cover'] = UploadFile($request->cover, 'Covers');

            Book::create($data);

            return response()->json([
                'status' => 'success',
                'message' => trans('general.store'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json([
            'status' => 'success',
            'message' => trans('general.get'),
            'data' => $book,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $data = $request->validated();

            // Only update the fields that have changed in the request
            $fieldsToUpdate = array_filter($data, function ($key) use ($request, $book, $data) {
                if ($key === 'cover') {
                    // Handle cover image update separately
                    return $request->has('cover');
                }

                return $request->has($key) && $book->{$key} !== $data[$key];
            }, ARRAY_FILTER_USE_KEY);

            // Handle cover image update
            if ($request->has('cover')) {
                $path = UpdateFile($request->file('cover'), 'Covers', $book->cover);
                $fieldsToUpdate['cover'] = $path;
            }

            // Check if any fields to update
            if (! empty($fieldsToUpdate)) {
                $book->update($fieldsToUpdate);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('general.update'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            Delete_File($book->cover);
            $book->delete();

            return response()->json([], 202);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
