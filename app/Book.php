<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use function GuzzleHttp\Psr7\str;

class Book extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/books/' . $this->id . '-' . $this->title;
    }
}
