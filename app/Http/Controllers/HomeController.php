<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Level;
use App\Models\Plan;
use Illuminate\Http\Response;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $levels = Level::published()
            ->with(['lectures' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $plans = Plan::active()->orderBy('sort_order')->get();
        $faqs = Faq::published()->get();

        return view('pages.home', compact('levels', 'plans', 'faqs'));
    }

    public function sitemap(): Response
    {
        $urls = [route('home'), route('login'), route('register')];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $u) {
            $xml .= '<url><loc>'.e($u).'</loc><changefreq>weekly</changefreq></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
