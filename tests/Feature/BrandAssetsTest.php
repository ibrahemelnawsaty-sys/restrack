<?php

namespace Tests\Feature;

use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The favicon and the share card are invisible until something breaks them:
 * a blank browser tab, or a blank grey card on every WhatsApp/X/LinkedIn share.
 */
class BrandAssetsTest extends TestCase
{
    use RefreshDatabase;

    /** Every icon and card the markup points at has to actually be on disk, and not empty. */
    public function test_the_brand_asset_files_are_shipped(): void
    {
        foreach (['favicon.ico', 'favicon.svg', 'apple-touch-icon.png', 'og-image.png', 'og-image.svg'] as $file) {
            $path = public_path($file);
            $this->assertFileExists($path);
            $this->assertGreaterThan(0, filesize($path), $file.' is an empty placeholder');
        }
    }

    public function test_pages_declare_the_favicon(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="icon" href="'.asset('favicon.ico').'" sizes="32x32">', $html);
        $this->assertStringContainsString('<link rel="icon" href="'.asset('favicon.svg').'" type="image/svg+xml">', $html);
        $this->assertStringContainsString('<link rel="apple-touch-icon" href="'.asset('apple-touch-icon.png').'">', $html);
    }

    /** Open Graph rejects relative paths — a share card only works from an absolute URL. */
    public function test_pages_declare_an_absolute_share_card(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['og:image' => 'property', 'twitter:image' => 'name'] as $meta => $attr) {
            $this->assertSame(1, preg_match('/<meta '.$attr.'="'.$meta.'" content="([^"]+)">/', $html, $m), $meta.' is missing');
            $this->assertMatchesRegularExpression('#^https?://#', $m[1], $meta.' must be absolute');
            $this->assertStringEndsWith('/og-image.png', $m[1]);
        }

        $this->assertStringContainsString('<meta property="og:image:width" content="1200">', $html);
        $this->assertStringContainsString('<meta property="og:image:height" content="630">', $html);
        $this->assertStringContainsString('<meta property="og:image:alt" content="', $html);
    }

    /** The seo_meta.og_image column exists so an admin can override the card per route. */
    public function test_an_admin_set_image_overrides_the_default_card(): void
    {
        SeoMeta::updateOrCreate(['route' => 'home'], ['og_image' => 'seo/launch-card.png']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<meta property="og:image" content="'.asset('storage/seo/launch-card.png').'">', $html);
        // Dimensions are only claimed for the bundled card, whose size we know.
        $this->assertStringNotContainsString('og:image:width', $html);
    }
}
