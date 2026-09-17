<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return match (true) {
            $user->isSuperAdmin() => view('dashboard.super_admin', [
                'companies' => Company::withCount('users')->orderBy('name')->get(),
                'totalCompanies' => Company::count(),
                'totalUsers' => User::count(),
                'totalShortUrls' => ShortUrl::count(),
                'invitations' => Invitation::with(['company', 'inviter'])->latest()->get(),
            ]),

            $user->isAdmin() => view('dashboard.admin', [
                'shortUrls' => ShortUrl::visibleTo($user)->with('creator')->latest('created_at')->get(),
                'totalCompanyUrls' => ShortUrl::where('company_id', $user->company_id)->count(),
                'totalCompanyUsers' => User::where('company_id', $user->company_id)->count(),
                'invitations' => Invitation::where('company_id', $user->company_id)->with('inviter')->latest()->get(),
            ]),

            default => view('dashboard.member', [
                // Sales/Manager can create; Member (and anyone else) just views their own.
                'shortUrls' => ShortUrl::visibleTo($user)->latest('created_at')->get(),
                'canCreate' => $user->role->canCreateShortUrls(),
                'myUrlCount' => ShortUrl::where('user_id', $user->id)->count(),
            ]),
        };
    }
}
