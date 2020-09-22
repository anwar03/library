<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {
        $response = $this->post('/authors',  $this->data());

        $author = Author::all();

        $response->assertOk();
        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1988/14/05', $author->first()->dob->format('Y/d/m'));
    }

    /** @test */
    public function an_new_author_is_automatically_added()
    {

        $this->post('/books', [
            'title' => 'Cool book title',
            'author_id' => 'jonh Doe',
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    /** @test */
    public function a_name_is_required(){
        $data = $this->data([ 'name' => '']);

        $response = $this->post('/authors', $data);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_dob_is_required(){
        $data = $this->data([ 'dob' => '']);

        $response = $this->post('/authors', $data);

        $response->assertSessionHasErrors('dob');
    }

    /**
     * @param array $data
     * @return string[]
     */
    private function data($data = []){
        return array_merge([
            'name' => 'Author name',
            'dob' => '05/14/1988',
        ], $data);
    }
}
