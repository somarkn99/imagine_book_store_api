<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'cover' => 'required|image|mimes:png,jpg',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => trans('books.title_required'),
            'title.max' => trans('books.title_max'),
            'cover.required' => trans('books.cover_required'),
            'author.required' => trans('books.cover_image'),
            'author.max' => trans('books.author_max'),
            'genre.required' => trans('books.genre_required'),
            'genre.max' => trans('books.genre_max'),
            'price.required' => trans('books.price_required'),
            'price.numeric' => trans('books.price_numeric'),
            'price.min' => trans('books.price_min'),
            'stock.required' => trans('books.stock_required'),
            'stock.integer' => trans('books.stock_integer'),
            'stock.min' => trans('books.stock_min'),
        ];
    }
}
