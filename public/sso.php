<?php
/**
 * BRIDGE SSO MOSIP - Jalur Cepat (Sama dengan SIMuti)
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot the application
$app->boot();

$uid   = $_GET['uid']   ?? null;
$role  = $_GET['role']  ?? null;
$ts    = $_GET['ts']    ?? null;
$token = $_GET['token'] ?? null;
$key   = config('sso.shared_secret');

if (!$key) {
    die("SSO Error: Secret key tidak ditemukan di .env (SSO_SHARED_SECRET).");
}

if (!$uid || !$token) {
    header("Location: /login");
    exit;
}

// Validasi Hash (Sama persis dengan SIMuti)
$validToken = hash_hmac('sha256', "{$uid}|{$role}|{$ts}", $key);

if ($token !== $validToken) {
    die("SSO Error: Token mismatch. Check Secret Key di .env MOSIP.");
}

// Proses Login menggunakan session resmi Laravel
$request = Request::capture();
$request->setLaravelSession($app['session']->driver());

try {
    $user = \App\Models\User::find($uid);
    if ($user) {
        Auth::login($user, true);
        
        // Target redirect disesuaikan dengan MOSIP (menggunakan route resmi)
        $target = match($user->role) {
            'Super Admin', 'Admin' => route('admin.dashboard'),
            'Tekpol'               => route('tekpol.dashboard'),
            default                => route('dashboard'),
        };

        header("Location: " . $target);
        exit;
    } else {
        die("SSO Error: User ID {$uid} tidak ditemukan di database MOSIP.");
    }
} catch (\Exception $e) {
    die("SSO Error: " . $e->getMessage());
}

header("Location: /login");
