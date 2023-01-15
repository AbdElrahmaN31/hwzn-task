<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * return users data paginated per page
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function get(int $perPage = 10): LengthAwarePaginator
    {
        return $this->user::query()->orderBy('email', 'asc')->orderBy('phone', 'asc')->paginate($perPage);
    }

    /**
     * Find user by email
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?Model
    {
        return $this->user::query()->where('email', $email)->first();
    }

    /**
     * login user web scenario
     *
     * @param array $credentials
     * @return User
     * @throws Exception
     */
    public function login(array $credentials): User
    {
        // extract login data from credential array
        $email = Arr::get($credentials, 'email');
        $password = Arr::get($credentials, 'password');

        // Get the user based on the email
        /**@var User $user */
        $user = $this->findByEmail($email);
        if (!$user) {
            throw new Exception('Invalid credentials');
        }

        // Check if the password is correct
        $status = $this->checkPassword($user, $password);

        if ($status) {
            // Password is correct
            // Reset the failed attempts
            $user->failed_attempts = 0;
            $user->save();
            // Log the user in
            Auth::login($user);
            return $user;
        } elseif ($user->failed_attempts === 2) {
            // increase failed attempts and update its time
            // throw an exception
            $user->failed_attempts++;
            $user->last_attempt = now();
            $user->save();
            throw new Exception('Invalid credentials,  try again after 30 sec!');
        } elseif ($user->failed_attempts === 3 && $user->last_attempt > now()->subSeconds(30)) {
            // user entered his password 3 times wrong and 30 secs hasn't finished yet.
            // wait until finish
            throw new Exception('You should wait until 30 seconds end!');
        } else {
            // Password is incorrect
            // Increment the failed attempts
            $user->failed_attempts++;
            $user->last_attempt = now();
            $user->is_blocked = $user->failed_attempts > 3;
            $user->save();
            throw new Exception('Invalid credentials');
        }
    }

    /**
     * login user api with only two devices scenario
     * @param array $credentials
     * @return array $token
     * @throws Exception
     */
    public function apiLogin(array $credentials): array
    {
        // extract login data from credential array
        $email = Arr::get($credentials, 'email');
        $password = Arr::get($credentials, 'password');

        // Get the user based on the email
        // check if not exists throw invalid credential exception
        /**@var User $user */
        $user = $this->findByEmail($email);
        if (!$user) {
            throw new Exception('Invalid credentials');
        }

        // Check if the password is correct
        $status = $this->checkPassword($user, $password);
        if ($status) {
            // correct password
            // check logged in devices by getting active tokens count
            $tokensCount = $this->tokensCount($user);
            if ($tokensCount >= 2) {
                throw new Exception("You're logged in from two devices");
            }
            return [
                'token' => $this->createToken($user),
                'user' => $user,
            ];
        } else {
            throw new Exception('Invalid credentials');
        }
    }

    protected function checkPassword($user, $password): bool
    {
        $credentialStatus = true;
        if (!Hash::check($password, $user->password)) {
            $credentialStatus = false;
        }
        return $credentialStatus;
    }

    private function createToken($user)
    {
        return $user->createToken('token')->plainTextToken;
    }

    protected function tokensCount($user): int
    {
        return $user->tokens()->count();
    }
}