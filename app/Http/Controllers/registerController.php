<?php

namespace App\Http\Controllers;

use App\Repositories\userRepository;
use Illuminate\Http\Request;

class registerController extends Controller
{
    private $userRepository;
    public function __construct(userRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return view('welcome');
    }

    public function register()
    {
        return view('register');
    }

    public function registerAksi(Request $request)
    {
        $user = $this->userRepository->storeUser($request);
        $this->userRepository->resendOtp($user);

        return to_route('verifyOTP', ['nomer' => $request->nomer]);
    }

    public function verifyOTP($nomer)
    {
        return view('otp', [
            'nomer' => $nomer
        ]);
    }
}
