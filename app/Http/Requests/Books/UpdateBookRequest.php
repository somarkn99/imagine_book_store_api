<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'string|max:255',
            'cover' => 'image|mimes:png,jpg',
            'author' => 'string|max:255',
            'genre' => 'string|max:255',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.max' => trans('books.title_max'),
            'author.max' => trans('books.author_max'),
            'genre.max' => trans('books.genre_max'),
            'price.numeric' => trans('books.price_numeric'),
            'price.min' => trans('books.price_min'),
            'stock.integer' => trans('books.stock_integer'),
            'stock.min' => trans('books.stock_min'),
        ];
    }
}
