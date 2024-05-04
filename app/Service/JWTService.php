<?php

namespace App\Service;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Filesystem\Filesystem;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Log;
use stdClass;

class JWTService
{
    protected Filesystem $filesystem;
    protected string $publicKeyPath;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->publicKeyPath = base_path('keys/public.pem');
    }

    /**
     * @throws Exception
     */
    public function decodeToken($token): stdClass
    {
        $publicKey = $this->getPublicKey();

        try {
            return JWT::decode($token, new Key($publicKey, 'RS256'));
        } catch (ExpiredException $e) {
            throw new ExpiredException('Token expired');
        } catch (Exception $e) {
            Log::channel('errorlog')->error($e->getMessage());
            throw new Exception('Unauthorized');
        }
    }

    public function refreshToken($refreshToken): mixed
    {
        if (!$refreshToken) {
            return false;
        }

        try {
            $response = Http::post('???', ['refresh_token' => $refreshToken]);

            if ($response->successful()) {
                return $response->json('token');
            } elseif ($response->status() == 400) {
                return false;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::channel('errorlog')->error($e->getMessage());
            return false;
        }
    }

    private function getPublicKey(): string
    {
        return $this->filesystem->get($this->publicKeyPath);
    }
}
