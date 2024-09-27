<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseHelper;
use App\Interfaces\AuthInterface;
use App\Models\User;
use App\Statuses\UserStatus;
use Illuminate\Support\Facades\Auth;

class AuthRepository extends BaseRepositoryImplementation implements AuthInterface
{
    public function model()
    {
        return User::class;
    }

    public function login($data)
    {
        $token = Auth::attempt($data);
        if (! $token) {
            return ApiResponseHelper::sendMessageResponse('user not found ', 404, false);

        }

        $user = Auth::user();

        return $this->createNewToken($token, $user);
    }

    public function register($data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $token = Auth::login($user);

        return $this->createNewToken($token, $user);

    }

    protected function createNewToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type ?? UserStatus::USER,
            ],
        ]);
    }
}
