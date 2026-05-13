<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SsoConsumeController extends Controller
{
    /**
     * Consume SSO token from 1Tep Portal.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // Check method if configured to only allow POST
        if ($request->isMethod('get') && !config('sso.allow_get_consume')) {
            abort(405);
        }

        $token = (string) $request->input('token', '');
        if ($token === '') {
            return redirect()->route('login')->withErrors(['email' => 'Token SSO tidak ditemukan.']);
        }

        $claims = $this->validateBrokerToken($token);
        if ($claims === null) {
            return redirect()->route('login')->withErrors(['email' => 'Token SSO tidak valid atau kedaluwarsa.']);
        }

        $user = $this->resolveUser($claims);
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun belum terdaftar di MOSIP. Hubungi administrator.',
            ]);
        }

        // Login user
        Auth::login($user, true);
        $request->session()->regenerate();

        // Redirect based on role
        return redirect($this->redirectPath($user->role));
    }

    /**
     * Find or create user based on claims.
     */
    private function resolveUser(array $claims): ?User
    {
        $email = strtolower((string) ($claims['email'] ?? ''));
        if ($email === '') {
            return null;
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        if (!config('sso.auto_provision')) {
            return null;
        }

        // Map role from portal to MOSIP role
        $portalRole = (string) ($claims['role'] ?? 'tekpol');
        $mappedRole = (string) (config('sso.role_map')[$portalRole] ?? 'Tekpol');

        return User::create([
            'name' => (string) ($claims['name'] ?? $email),
            'email' => $email,
            'password' => Str::random(40),
            'role' => $mappedRole,
        ]);
    }

    /**
     * Validate JWT token from portal.
     */
    private function validateBrokerToken(string $token): ?array
    {
        $secret = (string) config('sso.shared_secret');
        if ($secret === '') {
            return null;
        }

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $header = $this->decodeSegment($encodedHeader);
        $payload = $this->decodeSegment($encodedPayload);
        
        if (!is_array($header) || !is_array($payload)) {
            return null;
        }

        // Basic JWT header check
        if (($header['alg'] ?? null) !== 'HS256' || ($header['typ'] ?? null) !== 'JWT') {
            return null;
        }

        // Signature check
        $signedData = $encodedHeader.'.'.$encodedPayload;
        $expected = $this->base64UrlEncode(hash_hmac('sha256', $signedData, $secret, true));
        if (!hash_equals($expected, $encodedSignature)) {
            return null;
        }

        // Expiry check
        $now = time();
        $exp = (int) ($payload['exp'] ?? 0);
        $iat = (int) ($payload['iat'] ?? 0);
        if ($exp < $now || $iat > ($now + 30)) {
            return null;
        }

        // Issuer & Audience check
        if (($payload['iss'] ?? null) !== config('sso.issuer')) {
            return null;
        }

        if (($payload['aud'] ?? null) !== config('sso.audience')) {
            return null;
        }

        // Replay attack prevention (using JTI)
        $jti = (string) ($payload['jti'] ?? '');
        if ($jti === '') {
            return null;
        }

        $ttl = max(1, $exp - $now);
        $isNewToken = Cache::add('sso:jti:'.$jti, 1, now()->addSeconds($ttl));
        if (!$isNewToken) {
            return null;
        }

        return $payload;
    }

    private function decodeSegment(string $value): ?array
    {
        $remainder = strlen($value) % 4;
        if ($remainder > 0) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        $decoded = base64_decode(strtr($value, '-_', '+/'), true);
        if ($decoded === false) {
            return null;
        }

        $json = json_decode($decoded, true);
        return is_array($json) ? $json : null;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function redirectPath(string $role): string
    {
        if (in_array($role, ['Super Admin', 'Admin'])) {
            return route('admin.dashboard');
        }

        if ($role === 'Tekpol') {
            return route('tekpol.dashboard');
        }

        return route('dashboard');
    }
}
