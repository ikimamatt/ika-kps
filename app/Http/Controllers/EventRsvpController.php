<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventRsvpController extends Controller
{
    /**
     * Store an RSVP registration for the authenticated user.
     */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        // Check if fully booked
        if ($event->isFullyBooked()) {
            return back()->with('error', 'Mohon maaf, kuota peserta untuk kegiatan ini telah penuh.');
        }

        // Check if already registered
        $existing = $event->registrations()->where('user_id', auth()->id())->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah terdaftar pada kegiatan ini.');
        }

        $event->registrations()->create([
            'user_id' => auth()->id(),
            'status' => 'confirmed',
            'notes' => $request->input('notes'),
            'registered_at' => now(),
        ]);

        return back()->with('success', 'Konfirmasi kehadiran (RSVP) Anda berhasil dicatat. Sampai jumpa di lokasi acara!');
    }

    /**
     * Cancel an RSVP registration for the authenticated user.
     */
    public function destroy(Request $request, string $slug): RedirectResponse
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        $registration = $event->registrations()->where('user_id', auth()->id())->first();

        if ($registration) {
            $registration->delete();

            return back()->with('success', 'Konfirmasi kehadiran Anda pada kegiatan ini telah dibatalkan.');
        }

        return back()->with('error', 'Anda belum terdaftar pada kegiatan ini.');
    }
}
