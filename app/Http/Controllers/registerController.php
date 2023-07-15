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

        // sms infobip
            $api_Key = '9ecaf3082130b29eb2153dae4a55e43b-a407507f-76f2-462f-9238-5b6cd761aa3b';
            $base_url = 'lz51nr.api.infobip.com';
            $json = [
                'messages' => [
                    'destinations' => [
                        'to' => '62895336397742'
                    ],
                    'from' => 'Rokhmad',
                    'text' => 'berhasil convert array ke json'
                ]
            ];
            $result = json_encode($json);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://$base_url/sms/2/text/advanced",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $result,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: App $api_Key",
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
        // end sms

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
