<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Redis;

class OauthController extends Controller
{
    //
    public function google()
    {
        try{
            $user = Socialite::driver('google')->user();
            $find_user = User::where('email', $user->email)->first();
            if($find_user)
            {
                Auth::login($find_user);
            }
            else
            {
                $new_user = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make(Str::random(10)),
                    'profile_photo_path' => $user->avatar,
                ]);
                Auth::login($new_user);
            }
            return redirect()->intended('/');
        }
        catch(Exception $e)
        {
            return redirect()->route('google.login');
        }
        
        /*
            +id: "113296194671858307569"
            +nickname: "David Shim"
            +name: "David Shim"
            +email: "groupket.official@gmail.com"
            +avatar: "https://lh3.googleusercontent.com/a/ACg8ocLIxxFlGAw8kaNx4c4kdPJE4o-Ym3JqTXx0Zo2fY52G=s96-c"
            +user: array:8 [▼
                "sub" => "113296194671858307569"
                "name" => "David Shim"
                "given_name" => "David"
                "family_name" => "Shim"
                "picture" => "https://lh3.googleusercontent.com/a/ACg8ocLIxxFlGAw8kaNx4c4kdPJE4o-Ym3JqTXx0Zo2fY52G=s96-c"
                "email" => "groupket.official@gmail.com"
                "email_verified" => true
                "locale" => "en"
            ]
            +attributes: array:5 [▼
                "id" => "113296194671858307569"
                "nickname" => "David Shim"
                "name" => "David Shim"
                "email" => "groupket.official@gmail.com"
                "avatar" => "https://lh3.googleusercontent.com/a/ACg8ocLIxxFlGAw8kaNx4c4kdPJE4o-Ym3JqTXx0Zo2fY52G=s96-c"
            ]
            +token: "ya29.a0AfB_byABZC7xtL3CJXcDS1TGfmlRsRuXNRf7E9qtKQz2Dg1yh2N5dxDVCP78oyzegJ61PJ3NQp5OuszE3HYqixW64prKV8nC1I62xF-dlG-e263eOSOzf60iJojpPqjQW2TnVzV7SqWVVd2HZNuQ13i3dXjjcaEWenZxaCgYKARASARASFQGOcNnCctyzus7JVX24UeJKZKnmug0171 ◀"
            +refreshToken: null
            +expiresIn: 3599
            +approvedScopes: null
            +accessTokenResponseBody: array:5 [▼
                "access_token" => "ya29.a0AfB_byABZC7xtL3CJXcDS1TGfmlRsRuXNRf7E9qtKQz2Dg1yh2N5dxDVCP78oyzegJ61PJ3NQp5OuszE3HYqixW64prKV8nC1I62xF-dlG-e263eOSOzf60iJojpPqjQW2TnVzV7SqWVVd2HZNuQ13i3d ▶"
                "expires_in" => 3599
                "scope" => "openid https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
                "token_type" => "Bearer"
                "id_token" => "eyJhbGciOiJSUzI1NiIsImtpZCI6IjdjMGI2OTEzZmUxMzgyMGEzMzMzOTlhY2U0MjZlNzA1MzVhOWEwYmYiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiO ▶"
            ]
        */
    }

    public function facebook()
    {
        try{
            $user = Socialite::driver('facebook')->user();
            $find_user = User::where('email', $user->email)->first();
            if($find_user)
            {
                Auth::login($find_user);
            }
            else
            {
                $new_user = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make('password', [
                        'rounds' => 12,
                    ]),
                ]);
                Auth::login($new_user);
            }
            if(Cookie::get('activity'))
            {
                $activity = json_decode(Cookie::get('activity'));
                Cookie::expire('activity');
                if($activity->keeping)
                {
                    $cart = Auth::user()->carts()->updateOrCreate(
                        ['keeping' => true, 'cart_status' => 'pending'],
                    );
                    if($cart)
                    {
                        $cart->orders()->updateOrCreate(
                            ['cart_id' => $cart->id, 'product_id' => $activity->product_id, 'order_status' => 'pending', 'visiting_date' => $activity->visiting_date,],
                            [
                                'adults_price' => $activity->adults_price,
                                'adults_quantity' => $activity->adults,
                                'children_price' => $activity->children_price,
                                'children_quantity' => $activity->children,
                            ],
                        );
                        return redirect()->route('cart');
                    }
                }
                else
                {
                    $cart = Auth::user()->carts()->updateOrCreate(
                        ['keeping' => false, 'cart_status' => 'pending'],
                    );
                    if($cart)
                    {
                        $store_order = $cart->orders()->updateOrCreate(
                            ['cart_id' => $cart->id],
                            [
                                'product_id' => $activity->product_id,
                                'order_status' => 'pending', 
                                'visiting_date' => $activity->visiting_date,
                                'adults_price' => $activity->adults_price,
                                'adults_quantity' => $activity->adults,
                                'children_price' => $activity->children_price,
                                'children_quantity' => $activity->children,
                            ],
                        );
                        if($store_order)
                        {
                            Redis::set('cart:'.$cart->user->id, $cart->id);
                            return redirect()->route('payment');
                        }
                    }
                }
            }
            else
            {
                return redirect()->intended('/');
            }
            
        }
        catch(Exception $e)
        {
            return redirect()->route('google.login');
        }
    }
}
