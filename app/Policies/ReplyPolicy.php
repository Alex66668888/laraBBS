<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Reply $reply)
    {
        // 只有 话题的作者和评论的作者，才有权限删除评论。
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
