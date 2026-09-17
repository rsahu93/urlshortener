<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    public function __invoke(string $shortCode): RedirectResponse
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();

        return redirect()->away($shortUrl->original_url, 302);
    }
}
