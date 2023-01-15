<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function get();
    public function findByEmail(string $email);
    public function login(array $credentials);
    public function apiLogin(array $credentials);
}