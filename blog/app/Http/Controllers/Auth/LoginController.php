<?php
// 1. redirectToProvider() 구글에 로그인요청
// 2. handleProviderCallback () 로그인한 후에 이미 만들어진 아이디인지 확인후 처리

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use App\User;

class LoginController extends Controller
{

    // 1. redirectToProvider() 구글에 로그인요청
    public function redirectToProvider()
    {
//        return Socialite::driver('google')->redirect();
        return Socialite::driver('google')->with(['access_type'=>'offline'])->redirect();
    }

// 2. handleProviderCallback () 로그인한 후에 이미 만들어진 아이디인지 확인후 처리
    public function handleProviderCallback()
    {
        //아이디 생성 그런느낌인거같음.
        $user = Socialite::driver('google')->stateless()->user();
//        $user = Socialite::driver('google')->user();

//        dd($user);
        $existUser = User::where('email',$user->email)->first();
        if($existUser){
            if($user->refreshToken===null){
                 User::where('email', $existUser->email)
                    ->update(['name' => $user->getName()],['avatar' =>$user->getAvatar()]);
            }
            else{
                User::where('email', $existUser->email)
                    ->update(['name' => $user->getName()],['avatar' =>$user->getAvatar()],['refresh_token'=> $user->refreshToken]);
            }
//            $compareUser->save();
            // 그전꺼로 로그인 되어있는 정보로 로그인해야함
            auth()->login($existUser, false);
        }
        else{
            $user = User::firstOrCreate([
                'name'  => $user->getName(),
                'email' => $user->getEmail(),
                'avatar' =>$user->getAvatar(),
                'sns_type'=>'google',
                'refresh_token'=> $user->refreshToken
//            'token'=>$user->token,
            ]);
            auth()->login($user, false);
        }

//        dd($user);

//        Auth::guard('admin')->login($user);


        return redirect()->to('/');
    }
}
