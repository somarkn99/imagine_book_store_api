<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Seed the database before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test the index endpoint.
     */
    public function testIndex()
    {
        // Create some sample books
        Book::factory()->count(5)->create();

        // Make a GET request to the index endpoint
        $response = $this->get('/api/book');

        // Assert that the response has a success status
        $response->assertStatus(200);

        // Assert that the response contains the expected JSON structure
        $response->assertJsonStructure(['status', 'message', 'data' => ['current_page', 'data']]);
    }

    /**
     * Test the store endpoint.
     */
    public function testNormalUserStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create book data for testing
        $bookData = [
            'title' => 'Sample Book',
            'author' => 'Sample Author',
            'genre' => 'Sample Genre',
            'cover' => 'sample_cover.jpg', // Make sure to provide a valid file name or use a dummy file for testing
        ];

        // Make a POST request to the store endpoint with the book data
        $response = $this->post('/api/book', $bookData);

        // Assert that the response has a success status
        $response->assertStatus(403);
    }

    public function testAdminUserStore()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('sample_cover.jpg');

        // Create book data for testing
        $bookData = [
            'title' => 'Sample Book',
            'author' => 'Sample Author',
            'genre' => 'Sample Genre',
            'cover' => $file,
            'price' => 100,
            'stock' => 52,
        ];

        // Make a POST request to the store endpoint with the book data
        $response = $this->post('/api/book', $bookData);

        // Assert that the response has a success status
        $response->assertStatus(201);
        $response->assertJson(['status' => 'success', 'message' => 'Data Stored Successfully.']);
    }


    /**
     * Test the show endpoint.
     */
    public function testShow()
    {
        // Create a sample book
        $book = Book::factory()->create();

        // Make a GET request to the show endpoint with the book ID
        $response = $this->get('/api/book/' . $book->id);

        // Assert that the response has a success status
        $response->assertStatus(200);

        // Assert that the response contains the expected JSON structure
        $response->assertJsonStructure(['status', 'message', 'data']);
    }
}
