<?php
// 1. redirectToProvider() 구글에 로그인요청
// 2. handleProviderCallback () 로그인한 후에 이미 만들어진 아이디인지 확인후 처리
// 3. findOrCreateUser() 아이디 존재하지않으면 새로 생성 하는 메서드
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
        $socialUser = Socialite::driver('google')->stateless()->user();
//        $socialUser = Socialite::driver('google')->user();

//        dd($socialUser);
        $user = $this->findOrCreateUser($socialUser);

//      로그인
        Auth::login($user, false);

        return redirect()->away(env('LOGIN_ENDPOINT').'/?cf='.$socialUser->id);
    }

    // 아이디 존재하지않으면 새로 생성 하는 메서드
    public function findOrCreateUser($socialUser){
        $existUser = User::where('uid',$socialUser->id)->first();
        if($existUser){
            if($socialUser->refreshToken===null){
                User::where('uid', $existUser->uid)
                    ->update(['name' => $socialUser->getName()],['avatar' =>$socialUser->getAvatar()]);
            }
            else{
                User::where('uid', $existUser->uid)
                    ->update(['name' => $socialUser->getName()],['avatar' =>$socialUser->getAvatar()],['refresh_token'=> $socialUser->refreshToken]);
            }
            // 그전꺼로 로그인 되어있는 정보로 로그인해야함
            return $existUser;
        }
        else{
            $user = User::firstOrCreate([
                'name'  => $socialUser->getName(),
                'uid'  => $socialUser->getId(),
                'email' => $socialUser->getEmail(),
                'avatar' =>$socialUser->getAvatar(),
                'sns_type'=>'google',
                'refresh_token'=> $socialUser->refreshToken
//            'token'=>$user->token,
            ]);
            return $user;
        }
    }
}
