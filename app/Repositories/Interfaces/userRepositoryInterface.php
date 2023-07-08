<?php

namespace App\Repositories\Interfaces;

interface userRepositoryInterface
{
    public function allUser();
    public function storeUser($request);
    public function resendOtp($request);
    // public function findUser($id);
    // public function updateUser($data, $id); 
    // public function destroyUser($id);
}