<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    /**
     * Display the website settings panel.
     */
    public function index(): View
    {
        $allSettings = Setting::all();
        $settings = $allSettings->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the website settings in storage.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $submittedSettings = $request->input('settings', []);

        // Define which keys belong to which groups
        $groupMapping = [
            'org_name' => 'general',
            'org_tagline' => 'general',
            'org_description' => 'general',
            'contact_email' => 'contact',
            'contact_phone' => 'contact',
            'contact_whatsapp' => 'contact',
            'contact_address' => 'contact',
            'social_instagram' => 'social',
            'social_linkedin' => 'social',
            'social_youtube' => 'social',
        ];

        foreach ($submittedSettings as $key => $value) {
            $group = $groupMapping[$key] ?? 'general';
            Setting::set($key, $value, $group);
        }

        return back()->with('success', 'Pengaturan website berhasil diperbarui dan cache konfigurasi telah disegarkan.');
    }
}
