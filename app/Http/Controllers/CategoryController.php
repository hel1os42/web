<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    /**
     * @return Response
     */
    public function index() : Response
    {
        return \response()->render('category', ['data' => (new Category())->get()]);
    }
}
