<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UserUpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserBusinessController extends Controller
{
    /**
     * Display a listing of businesses owned by the authenticated alumni.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $alumnus = $user->alumnus;

        $businesses = $alumnus
            ? Business::where('alumnus_id', $alumnus->id)->latest('id')->paginate(10)
            : collect();

        return view('profile.business.index', compact('businesses', 'alumnus'));
    }

    /**
     * Show the form for alumni to create/submit a new business.
     */
    public function create(Request $request): View
    {
        $categories = [
            'Kuliner & F&B',
            'Jasa & Konsultan',
            'Teknologi Informasi',
            'Kesehatan',
            'Konstruksi & Energi',
            'Retail & Fesyen',
            'Otomotif & Logistik',
        ];

        return view('profile.business.create', [
            'alumnus' => $request->user()->alumnus,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly submitted business from alumni.
     */
    public function store(StoreBusinessRequest $request): RedirectResponse
    {
        $user = $request->user();
        $alumnus = $user->alumnus;

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('businesses', 'public');
            $data['image_url'] = Storage::url($path);
        }
        unset($data['image']);

        $data['alumnus_id'] = $alumnus?->id;
        $data['status'] = 'pending'; // Always submitted as pending for review

        if ($alumnus && empty($data['owner_info'])) {
            $year = $alumnus->full_year ?? ($alumnus->class_year ?? '');
            $level = strtoupper($alumnus->level ?? 'SMA');
            $data['owner_info'] = "{$user->name} ({$level} KPS {$year})";
        }

        // Setup WhatsApp link if action_type is whatsapp
        if (($data['action_type'] ?? '') === 'whatsapp' || ! empty($data['whatsapp_number'])) {
            $data['action_type'] = 'whatsapp';
            $phone = preg_replace('/[^0-9]/', '', $data['whatsapp_number'] ?? '');
            if (str_starts_with($phone, '0')) {
                $phone = '62'.substr($phone, 1);
            }
            if (! empty($phone)) {
                $data['action_link'] = 'https://wa.me/'.$phone;
            }
        }

        $data['slug'] = Str::slug($data['name']).'-'.rand(100, 999);

        Business::create($data);

        return redirect()->route('profile.business.index')->with('success', 'Usaha Anda berhasil didaftarkan dan sedang menunggu peninjauan pengurus.');
    }

    /**
     * Show the form for editing an existing business owned by the alumnus.
     */
    public function edit(Business $business): View
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $business->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit unit usaha ini.');
        }

        $categories = [
            'Kuliner & F&B',
            'Jasa & Konsultan',
            'Teknologi Informasi',
            'Kesehatan',
            'Konstruksi & Energi',
            'Retail & Fesyen',
            'Otomotif & Logistik',
        ];

        return view('profile.business.edit', compact('business', 'alumnus', 'categories'));
    }

    /**
     * Update the specified business owned by the alumnus.
     */
    public function update(UserUpdateBusinessRequest $request, Business $business): RedirectResponse
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $business->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit unit usaha ini.');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('businesses', 'public');
            $data['image_url'] = Storage::url($path);
        }
        unset($data['image']);

        // Setup WhatsApp link if action_type is whatsapp
        if (($data['action_type'] ?? '') === 'whatsapp' || ! empty($data['whatsapp_number'])) {
            $data['action_type'] = 'whatsapp';
            $phone = preg_replace('/[^0-9]/', '', $data['whatsapp_number'] ?? '');
            if (str_starts_with($phone, '0')) {
                $phone = '62'.substr($phone, 1);
            }
            if (! empty($phone)) {
                $data['action_link'] = 'https://wa.me/'.$phone;
            }
        }

        $business->update($data);

        return redirect()->route('profile.business.index')
            ->with('success', 'Informasi unit bisnis Anda berhasil diperbarui.');
    }

    /**
     * Remove the specified business owned by the alumnus.
     */
    public function destroy(Business $business): RedirectResponse
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $business->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus unit usaha ini.');
        }

        $business->delete();

        return redirect()->route('profile.business.index')
            ->with('success', 'Unit bisnis berhasil dihapus dari daftar Anda.');
    }
}
