<?php

namespace App\Repositories\Interfaces;

interface userRepositoryInterface
{
    public function allUser();
    public function storeUser($request);
    public function updateUserOTP($nomer);
    public function sendOtp($request);
    public function verifyOTP($user);
    // public function findUser($id);
    // public function updateUser($data, $id); 
    // public function destroyUser($id);
}