<?php

namespace App\Services;

use App\Models\SendoraSetting;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected ?SendoraSetting $settings;

    public function __construct()
    {
        $this->settings = SendoraSetting::current();
    }

    public function send(string $phone, string $message, ?int $submissionId = null): array
    {
        if (!$this->settings || !$this->settings->is_active) {
            Log::warning('WhatsApp: Sendora is not configured or inactive.');
            return [
                'success' => false,
                'message' => 'Sendora WhatsApp is not configured or inactive.',
            ];
        }

        $phone = $this->formatPhone($phone);

        $log = WhatsappMessage::create([
            'phone' => $phone,
            'message' => $message,
            'status' => 'pending',
            'submission_id' => $submissionId,
        ]);

        try {
            $response = Http::withToken($this->settings->api_token)
                ->timeout($this->settings->timeout)
                ->post(rtrim($this->settings->api_url, '/') . '/messages', array_filter([
                    'phone' => $phone,
                    'message' => $message,
                    'sender' => $this->settings->sender_number,
                    'device_id' => $this->settings->device_id,
                ]));

            if ($response->successful()) {
                $log->update([
                    'status' => 'sent',
                    'response' => json_encode($response->json()),
                ]);

                Log::info('WhatsApp: Message sent successfully', [
                    'phone' => $phone,
                    'message_id' => $log->id,
                ]);

                return [
                    'success' => true,
                    'message' => 'Message sent successfully.',
                    'data' => $response->json(),
                ];
            }

            $log->update([
                'status' => 'failed',
                'error' => $response->body(),
                'response' => json_encode($response->json()),
            ]);

            Log::error('WhatsApp: API returned error', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'API error: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            Log::error('WhatsApp: Exception sending message', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send: ' . $e->getMessage(),
            ];
        }
    }

    public function testConnection(): array
    {
        if (!$this->settings || !$this->settings->api_token) {
            return [
                'success' => false,
                'message' => 'Sendora is not configured. Please set API token first.',
            ];
        }

        try {
            $response = Http::withToken($this->settings->api_token)
                ->timeout($this->settings->timeout)
                ->get(rtrim($this->settings->api_url, '/') . '/status');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful!',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'API returned status: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '60' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '60')) {
            $phone = '60' . $phone;
        }

        return $phone;
    }
}
