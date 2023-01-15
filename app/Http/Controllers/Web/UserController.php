<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Interfaces\UserRepositoryInterface as UserRepository;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $users = $this->userRepo->get(per_page());
        return view('users', compact('users'));
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $this->userRepo->login($request->safe()->toArray());
            return redirect()->route("welcome");
        } catch (\Exception $e) {
            return redirect("login")->withErrors([$e->getMessage()]);
        }
    }

}
