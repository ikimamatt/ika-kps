<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    /**
     * Display a listing of events for administrators.
     */
    public function index(Request $request): View
    {
        $query = Event::query()->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->withCount(['registrations' => fn ($q) => $q->where('status', 'confirmed')])
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Event::count(),
            'published' => Event::where('status', 'published')->count(),
            'draft' => Event::where('status', 'draft')->count(),
            'total_participants' => EventRegistration::where('status', 'confirmed')->count(),
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $data['image_url'] = Storage::url($path);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']).'-'.rand(100, 999);
        }

        $data['created_by'] = auth()->id();

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda kegiatan / event berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($event->image_url && Str::contains($event->image_url, '/storage/events/')) {
                $oldPath = Str::after($event->image_url, '/storage/');
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('events', 'public');
            $data['image_url'] = Storage::url($path);
        } elseif (empty($data['image_url'])) {
            $data['image_url'] = $event->image_url;
        } elseif ($event->image_url && $data['image_url'] !== $event->image_url && Str::contains($event->image_url, '/storage/events/')) {
            $oldPath = Str::after($event->image_url, '/storage/');
            Storage::disk('public')->delete($oldPath);
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda kegiatan berhasil dihapus.');
    }

    /**
     * Display a listing of RSVP participants for the specified event.
     */
    public function participants(Event $event): View
    {
        $participants = $event->registrations()
            ->with('user.alumnus')
            ->latest('registered_at')
            ->paginate(25);

        return view('admin.events.participants', compact('event', 'participants'));
    }

    /**
     * Toggle the publication status of the event.
     */
    public function toggleStatus(Event $event): RedirectResponse
    {
        $newStatus = $event->status === 'published' ? 'draft' : 'published';
        $event->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'published' ? 'dipublikasikan ke publik' : 'ditarik ke draft';

        return back()->with('success', "Status event '{$event->title}' berhasil {$statusLabel}.");
    }
}
