<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\userRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

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

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password'), 'otp_verify' => 1])) {
            $request->session()->regenerate();

            return redirect()->intended('zoom');
        }
        return back()->with('success', 'username atau password salah! atau user belum di verifykasi!');
    }

    public function register()
    {
        return view('register');
    }

    public function registerAksi(Request $request)
    {
        $user = $this->userRepository->storeUser($request);
        $this->userRepository->sendOtp($user);

        return to_route('verifyOTP', ['nomer' => $request->nomer]);
    }

    public function verifyOTP(Request $request,$nomer)
    {
        $key = 'otp.' . request()->ip();
        return view('otp', [
            'nomer' => $nomer,
            'key' => $key,
            'retries' => RateLimiter::retriesLeft($key, 2),
            'seconds' => RateLimiter::availableIn($key)
        ]);
    }

    public function verifyOTPAksi(Request $request)
    {
        $user = User::token($request)->first('id');
        if ($user) {
            $this->userRepository->verifyOTP($user);
            return to_route('login')->with('success', 'kode otp berhasil di verify!');
        } else {
            return back()->with('error', 'Nomor tidak di temukan!');
        }

        return back()->with('error', 'kode otp salah!');
    }

    public function resendOTP($nomer)
    {
        $this->userRepository->updateUserOTP($nomer);
        $getUser = User::User(['nomer' => $nomer])->first(['otp', 'nomer']);
        // resend otp
        $this->userRepository->sendOtp($getUser);
        return back()->with('error', 'proses kirim ulang otp');
    }
}
