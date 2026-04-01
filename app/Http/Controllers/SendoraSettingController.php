<?php

namespace App\Http\Controllers;

use App\Models\SendoraSetting;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SendoraSettingController extends Controller
{
    public function edit()
    {
        $settings = SendoraSetting::current();

        return Inertia::render('Admin/SendoraSettings', [
            'settings' => $settings ? [
                'id' => $settings->id,
                'api_url' => $settings->api_url,
                'sender_number' => $settings->sender_number,
                'device_id' => $settings->device_id,
                'is_active' => $settings->is_active,
                'timeout' => $settings->timeout,
                'has_token' => !empty($settings->getAttributes()['api_token']),
            ] : null,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_url' => 'required|url|max:500',
            'api_token' => 'nullable|string|max:500',
            'sender_number' => 'nullable|string|max:20',
            'device_id' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'timeout' => 'integer|min:5|max:120',
        ]);

        $settings = SendoraSetting::current();

        if (!$settings) {
            $settings = new SendoraSetting();
        }

        $settings->api_url = $validated['api_url'];
        $settings->sender_number = $validated['sender_number'] ?? null;
        $settings->device_id = $validated['device_id'] ?? null;
        $settings->is_active = $validated['is_active'] ?? false;
        $settings->timeout = $validated['timeout'] ?? 30;

        if (!empty($validated['api_token'])) {
            $settings->api_token = $validated['api_token'];
        }

        $settings->save();

        return back()->with('success', 'Tetapan Sendora berjaya disimpan.');
    }

    public function testConnection()
    {
        $whatsapp = new WhatsappService();
        $result = $whatsapp->testConnection();

        return response()->json($result);
    }
}
