<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Exceptions\InvalidToken;
use App\Models\JwtToken;
use App\Models\User;
use App\Services\JWTService\JWTService;
use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::viaRequest('jwt', function ($request) {
            $token = $request->bearerToken();
            if ($token && strlen($token) > 0) {
                try {
                    $isInvalid = JwtToken::query()->where('token', $token)
                        ->whereNotNull('expires_at')
                        ->first();
                    if ($isInvalid) {
                        throw new InvalidToken('Token Expired');
                    }
                    $user = JWTService::make()->decodeToken($token);
                    if (! $user) {
                        throw new InvalidToken();
                    }
                } catch (Exception $e) {
                    return null;
                }
                return User::query()->where('uuid', $user['uuid'])->first();
            }
            return null;
        });
        //
    }
}
