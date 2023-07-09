<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\userRepositoryInterface;

class userRepository implements userRepositoryInterface
{

    public function allUser()
    {
        return User::all();
    }

    public function storeUser($request)
    {
        // $user = new User;
        // $user->username = $request->username;
        // $user->email = $request->email;
        // $user->nomer = $request->nomer;
        // $user->password = encrypt($request->password);
        // $user->otp = rand(1000, 9999);
        // $user->save();

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'nomer' => $request->nomer,
            'password' => bcrypt($request->password),
            'otp' => rand(1000, 9999)
        ]);
        return $user;
    }

    public function updateUserOTP($nomer)
    {
        $user = User::where('nomer', $nomer)->update([
            'otp' => rand(1000, 9999)
        ]);
        return $user;
    }

    public function sendOtp($user)
    {
        // send otp (WA)
        $curl = curl_init();
        $token = "bpnkFJP1mZZ+cegP7mNE";
        $url = "https://api.fonnte.com/send";
        $pesan = 'OTP : ' . $user->otp;
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $user->nomer,
                'message' => $pesan,
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: '.$token.''
            ],
        ]);
        curl_exec($curl);
        curl_close($curl);
    }

    public function verifyOTP($user)
    {
        $aksi = User::where('id', $user->id)->update([
            'otp_verify' => true
        ]);
        return $aksi;
    }

    // public function findUser($id)
    // {
    //     return User::find($id);
    // }

    // public function updateUser($data, $id)
    // {
    //     $ibadah = User::where('id', $id)->first();
    //     $ibadah->name = $data['name'];
    //     $ibadah->slug = $data['slug'];
    //     $ibadah->save();
    // }

    // public function destroyUser($id)
    // {
    //     $ibadah = User::find($id);
    //     $ibadah->delete();
    // }
}