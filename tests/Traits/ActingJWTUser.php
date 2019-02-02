<?php

namespace Tests\Traits;

use App\Models\User;

trait ActingJWTUser
{

    /**
     * 用户生成 token
     *
     * @param User $user
     * @return $this
     */
    public function JWTActingAs(User $user)
    {
        $token = \Auth::guard('api')->fromUser($user);
        $this->withHeaders(['Authorization' => 'Bearer '.$token]);

        return $this;
    }


}