<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withExceptionHandling();

        $response = $this->post('/books', $this->data());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());

    }

    /** @test */
    public function a_title_is_required()
    {

        $response = $this->post('/books', $this->data([
            'title' => '',
        ]));

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {

        $response = $this->post('/books', $this->data(['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $insert = $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());
        $insert->assertRedirect($book->path());


        $update = $this->patch($book->path(), $this->data([
            'title' => 'New title',
            'author_id' => 'New author',
        ]));


        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $update->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $insert = $this->post('/books', $this->data());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $insert->assertRedirect($book->path());

        $delete = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $delete->assertRedirect('/books');
    }

    /**
     * @param array $data
     * @return string[]
     */
    private function data($data = [])
    {
        return array_merge([
            'title' => 'Cool book title',
            'author_id' => 'jonh',
        ], $data);
    }
}
