<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Guideline;
use App\Models\Level;
use App\Models\Plan;
use App\Models\Speaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // The landing page is one long page (owner note م2) — cache its reads together.
        $data = Cache::remember('home:data', now()->addMinutes(10), fn () => [
            'levels' => Level::published()
                ->with(['lectures' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get(),
            'plans' => Plan::active()->orderBy('sort_order')->get(),
            'faqs' => Faq::published()->get(),
            'guidelines' => Guideline::active()->get()->groupBy('group_key'),
            'speakers' => Speaker::active()->get(),
        ]);

        return view('pages.home', $data);
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
