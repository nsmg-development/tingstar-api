<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected ArticleRepositoryInterface $article;

    public function __construct(ArticleRepositoryInterface $article)
    {
        $this->article = $article;
    }

    public function index(Request $request)
    {
        $result = $this->article->get($request);

        return $this->response($result);
    }
}
