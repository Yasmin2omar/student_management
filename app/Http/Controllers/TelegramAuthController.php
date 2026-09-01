<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();

        if (! $this->verifyTelegramData($data)) {
            abort(403, 'Telegram data is incorrect');
        }

        if (time() - (int) $data['auth_date'] > 86400) {
            abort(403, 'Your login credentials have expired, try again');
        }

        $user = User::query()->firstOrCreate(
            ['telegram_id' => $data['id']],
            [
                'name' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
                'email' => 'tg_' . $data['id'] . '@telegram.local',
                'password' => Hash::make(Str::random(32)),
                'telegram_username' => $data['username'] ?? null,
                'telegram_photo_url' => $data['photo_url'] ?? null,
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended('/admin');
    }

    protected function verifyTelegramData(array $data): bool
    {
        if (! isset($data['hash'])) {
            return false;
        }

        $checkHash = $data['hash'];
        unset($data['hash']);

        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = $key . '=' . $value;
        }
        sort($pairs);
        $dataCheckString = implode("\n", $pairs);

        $secretKey = hash('sha256', config('services.telegram.bot_token'), true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);

        return hash_equals($hash, $checkHash);
    }
}
