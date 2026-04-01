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

    protected function apiClient()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->settings->api_token,
            'Accept' => 'application/json',
        ])->timeout($this->settings->timeout);
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
            $payload = [
                'to' => $phone,
                'message' => $message,
            ];

            if ($this->settings->device_id) {
                $payload['device_id'] = (int) $this->settings->device_id;
            }

            $response = $this->apiClient()
                ->post(rtrim($this->settings->api_url, '/') . '/api/v1/send-message', $payload);

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
                    'message' => 'Mesej berjaya dihantar.',
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
                'message' => 'Ralat API: ' . $response->status(),
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
                'message' => 'Gagal menghantar: ' . $e->getMessage(),
            ];
        }
    }

    public function testConnection(): array
    {
        if (!$this->settings || !$this->settings->api_token) {
            return [
                'success' => false,
                'message' => 'Sendora belum dikonfigurasi. Sila tetapkan API token terlebih dahulu.',
            ];
        }

        try {
            $response = $this->apiClient()
                ->get(rtrim($this->settings->api_url, '/') . '/api/v1/devices');

            if ($response->successful()) {
                $data = $response->json();
                $deviceCount = is_array($data['data'] ?? null) ? count($data['data']) : 0;
                return [
                    'success' => true,
                    'message' => "Sambungan berjaya! {$deviceCount} peranti ditemui.",
                    'data' => $data,
                ];
            }

            if ($response->status() === 401) {
                return [
                    'success' => false,
                    'message' => 'Token API tidak sah. Sila semak semula token anda.',
                ];
            }

            return [
                'success' => false,
                'message' => 'API mengembalikan status: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Sambungan gagal: ' . $e->getMessage(),
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
