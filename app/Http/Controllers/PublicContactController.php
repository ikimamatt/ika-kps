<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicContactController extends Controller
{
    /**
     * Display the public contact form and secretariat information.
     */
    public function show(): View
    {
        return view('contact.show');
    }

    /**
     * Store a newly created contact message in storage.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        // Honeypot spam bot protection: if filled, silently succeed without saving to DB
        if ($request->filled('hp_check')) {
            return back()->with('success', 'Pesan Anda telah berhasil dikirimkan ke Sekretariat IKA KPS. Terima kasih!');
        }

        Contact::create($request->only([
            'name',
            'email',
            'phone',
            'subject',
            'message',
        ]));

        return back()->with('success', 'Pesan Anda telah berhasil dikirimkan ke Sekretariat IKA KPS. Pengurus kami akan segera merespons.');
    }
}
