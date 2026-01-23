<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $articles = Article::where('is_published', true)->get();

        $content = view('sitemap', [
            'products' => $products,
            'articles' => $articles,
        ])->render();

        return Response::make($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}