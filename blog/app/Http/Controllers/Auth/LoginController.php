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
    public function redirectToProvider($social)
    {
//        return Socialite::driver('google')->redirect();
        return Socialite::driver($social)->with(['access_type'=>'offline'])->redirect();
    }

// 2. handleProviderCallback () 로그인한 후에 이미 만들어진 아이디인지 확인후 처리
    public function handleProviderCallback($social)
    {
        //아이디 생성 그런느낌인거같음.
        $socialUser = Socialite::driver($social)->stateless()->user();
//        $socialUser = Socialite::driver('google')->user();

//        dd($socialUser);
        $user = $this->findOrCreateUser($socialUser,$social);

//      로그인
        Auth::login($user, false);

        return redirect()->away(env('LOGIN_ENDPOINT').'/?cf='.$socialUser->id);
    }

    // 아이디 존재하지않으면 새로 생성 하는 메서드
    public function findOrCreateUser($socialUser,$social){
        $existUser = User::where('uid',$socialUser->id)->first();
        if($existUser){
            if($socialUser->refreshToken!==null){
                User::where('uid', $existUser->uid)
                    ->update(['access_token' => $socialUser->token],['refresh_token'=> $socialUser->refreshToken]);
            }
            else{
                User::where('uid', $existUser->uid)
                    ->update(['access_token' => $socialUser->token]);
            }

//            if($socialUser->refreshToken===null){
//                User::where('uid', $existUser->uid)
//                    ->update(['name' => $socialUser->getName()],['avatar' =>$socialUser->getAvatar()]);
//            }
//            else{
//                User::where('uid', $existUser->uid)
////                    ->update(['refresh_token'=> $socialUser->refreshToken]);
//                    ->update(['name' => $socialUser->getName()],['avatar' =>$socialUser->getAvatar()],['refresh_token'=> $socialUser->refreshToken]);
//            }

            // 그전꺼로 로그인 되어있는 정보로 로그인해야함
            return $existUser;
        }
        else{
            $nickname = 'null';

            if($socialUser->getNickname()===null){
                $nickname=$socialUser->getName();
            }
            else{
                $nickname=$socialUser->getNickname();
            }

            $user = User::firstOrCreate([
                'name'  => $nickname,
                'uid'  => $socialUser->getId(),
                'email' => $socialUser->getEmail(),
                'avatar' =>$socialUser->getAvatar(),
                'sns_type'=>$social,
                'access_token'=>$socialUser->token,
                'refresh_token'=> $socialUser->refreshToken
//            'token'=>$user->token,
            ]);
            return $user;
        }
    }
}
