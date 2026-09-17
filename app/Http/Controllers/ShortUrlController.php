<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShortUrlController extends Controller
{
   
    public function index(Request $request): View
    {
        $this->authorize('viewAny', ShortUrl::class);

        $shortUrls = ShortUrl::visibleTo($request->user())
            ->with('creator')
            ->latest('created_at')
            ->paginate(25);

        return view('short_urls.index', ['shortUrls' => $shortUrls]);
    }

    
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', ShortUrl::class);

        $data = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        $shortUrl = ShortUrl::create([
            'company_id' => $request->user()->company_id,
            'user_id' => $request->user()->id,
            'original_url' => $data['original_url'],
            'short_code' => $this->generateUniqueShortCode(),
            'created_at' => now(),
        ]);

        return back()->with('status', 'Short url created: '.url($shortUrl->short_code));
    }

    private function generateUniqueShortCode(): string
    {
        do {
            $code = Str::random(7);
        } while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}
