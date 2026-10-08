<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContactController extends Controller
{
    /**
     * Display a listing of incoming contact messages.
     */
    public function index(Request $request): View
    {
        $query = Contact::query()->latest('created_at');

        if ($request->filled('status')) {
            if ($request->input('status') === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->input('status') === 'read') {
                $query->where('is_read', true);
            }
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::where('is_read', false)->count(),
            'read' => Contact::where('is_read', true)->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    /**
     * Display the specified contact message content.
     */
    public function show(Contact $contact): View
    {
        // Automatically mark as read upon viewing
        if (! $contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Toggle or mark the specified contact message as read / unread.
     */
    public function markRead(Contact $contact): RedirectResponse
    {
        if ($contact->is_read) {
            $contact->update([
                'is_read' => false,
                'read_at' => null,
            ]);
            $msg = 'Pesan ditandai sebagai belum dibaca.';
        } else {
            $contact->markAsRead();
            $msg = 'Pesan ditandai sebagai sudah dibaca.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Pesan kontak berhasil dihapus.');
    }
}
