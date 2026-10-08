<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicEventController extends Controller
{
    /**
     * Display a listing of events and activities.
     */
    public function index(Request $request): View
    {
        $timeframe = $request->input('timeframe', 'upcoming');

        $query = Event::published();

        if ($timeframe === 'upcoming') {
            $query->upcoming()->orderBy('start_date', 'asc');
        } elseif ($timeframe === 'past') {
            $query->past()->orderBy('start_date', 'desc');
        } else {
            $query->orderBy('start_date', 'desc');
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

        $categoryCounts = Event::published()
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $stats = [
            'upcoming' => Event::published()->upcoming()->count(),
            'total' => Event::published()->count(),
        ];

        $events = $query->withCount(['registrations' => fn ($q) => $q->where('status', 'confirmed')])
            ->paginate(9)
            ->withQueryString();

        return view('event.index', compact('events', 'categoryCounts', 'timeframe', 'stats'));
    }

    /**
     * Display the specified event detail.
     */
    public function show(string $slug): View
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->withCount(['registrations' => fn ($q) => $q->where('status', 'confirmed')])
            ->firstOrFail();

        $userRegistration = auth()->check()
            ? $event->registrations()->where('user_id', auth()->id())->first()
            : null;

        $confirmedParticipants = $event->registrations()
            ->where('status', 'confirmed')
            ->with('user')
            ->latest('registered_at')
            ->take(8)
            ->get();

        $relatedEvents = Event::published()
            ->where('id', '!=', $event->id)
            ->where('category', $event->category)
            ->upcoming()
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        if ($relatedEvents->count() < 3) {
            $excludeIds = $relatedEvents->pluck('id')->push($event->id)->toArray();
            $fallbackEvents = Event::published()
                ->whereNotIn('id', $excludeIds)
                ->upcoming()
                ->orderBy('start_date', 'asc')
                ->take(3 - $relatedEvents->count())
                ->get();
            $relatedEvents = $relatedEvents->concat($fallbackEvents);
        }

        return view('event.show', compact('event', 'userRegistration', 'confirmedParticipants', 'relatedEvents'));
    }
}
