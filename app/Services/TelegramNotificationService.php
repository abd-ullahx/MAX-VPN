<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    /**
     * Send a notification message via Telegram bot.
     *
     * @param string $message HTML formatted message to send
     * @return bool
     */
    public static function send(string $message): bool
    {
        $token = config('services.telegram.token');
        $chatId = config('services.telegram.chat_id');
        $baseUrl = config('services.telegram.base_url', 'https://api.telegram.org');
        $proxy = config('services.telegram.proxy');

        if (!$token || !$chatId) {
            Log::warning('Telegram token or chat ID is not configured.');
            return false;
        }

        try {
            $client = Http::baseUrl($baseUrl);

            if ($proxy) {
                $client = $client->withOptions([  
                    
                    'proxy' => $proxy,
                ]);
            }

            $response = $client->post("/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('Telegram notification sent successfully.');
                return true;
            }

            Log::error('Telegram API response error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification: ' . $e->getMessage());
            return false;
        }
    }
}
