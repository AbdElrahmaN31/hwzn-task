<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Traits\ResponsesTrait;
use App\Interfaces\UserRepositoryInterface as UserRepository;

class UserController extends Controller
{
    use ResponsesTrait;
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->userRepo->apiLogin($request->safe()->toArray());
            return $this->successResponse('You are logged in successfully!', $data);
        } catch (\Exception $e) {
            return $this->failResponse($e->getMessage());
        }
    }

}
