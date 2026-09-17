<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function storeForNewCompany(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'role' => ['required', 'in:Member,Sales,Manager'],
        ]);

        $company = Company::create(['name' => $data['company_name']]);

        $invitation = Invitation::create([
            'company_id' => $company->id,
            'invited_by' => $request->user()->id,
            'email' => $data['email'],
            'role' => $data['role'],
            'expires_at' => now()->addDays(7),
        ]);

        $url = $invitation->getAcceptUrl();

        return back()->with('status', "Invitation created for {$data['email']} (Company: \"{$company->name}\"). Link: {$url}");
    }

    public function storeForCompany(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:Sales,Manager'],
        ]);

        $invitation = Invitation::create([
            'company_id' => $request->user()->company_id,
            'invited_by' => $request->user()->id,
            'email' => $data['email'],
            'role' => $data['role'],
            'expires_at' => now()->addDays(7),
        ]);

        $this->sendInvitation($invitation);

        $url = $invitation->getAcceptUrl();

        return back()->with('status', "Invitation created for {$data['email']}. Link: {$url}");
    }

    private function sendInvitation(Invitation $invitation): void
    {
        $url = URL::temporarySignedRoute(
            'invitations.accept',
            $invitation->expires_at,
            ['invitation' => $invitation->id]
        );

        Log::info('Invitation created', [
            'email' => $invitation->email,
            'role' => $invitation->role->value,
            'company_id' => $invitation->company_id,
            'accept_url' => $url,
        ]);
    }


    public function accept(Request $request, Invitation $invitation): View|RedirectResponse
    {
        if (! $invitation->isValid()) {
            abort(410, 'This invitation is no longer valid.');
        }

        return view('invitations.accept', ['invitation' => $invitation]);
    }

    /**
     * Complete the invitation: create the user and mark the invitation accepted.
     */
    public function complete(Request $request, Invitation $invitation): RedirectResponse
    {
        if (! $invitation->isValid()) {
            abort(410, 'This invitation is no longer valid.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($data, $invitation) {
            User::create([
                'company_id' => $invitation->company_id,
                'name' => $data['name'],
                'email' => $invitation->email,
                'password' => $data['password'], // hashed automatically by the User model's 'hashed' cast
                'role' => $invitation->role,
                'email_verified_at' => now(),
            ]);

            $invitation->update(['accepted_at' => now()]);
        });

        return redirect()->route('login')->with('status', 'Account created — please log in.');
    }
}
