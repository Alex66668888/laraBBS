<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\UserTransformer;


class UsersController extends Controller
{

    /**
     * 注册保存用户信息
     *
     * @param UserRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store(UserRequest $request){

        $verifyData = \Cache::get($request->verification_key);

        if(! $verifyData){
            return $this->response->error('验证码已失效', 422);
        }

        // hash_equals 是可防止时序攻击的字符串比较
        if(!hash_equals(trim($verifyData['code']), $request->verification_code)){
            // 返回 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }


    /**
     * 单条用户信息
     * @return \Dingo\Api\Http\Response
     */
    public function me()
    {
        // $this->user() 等同于\Auth::guard('api')->user()
        // 见 Dingo\Api\Routing\Helpers 这个 trait
        return $this->response->item($this->user(), new UserTransformer());
    }




}
