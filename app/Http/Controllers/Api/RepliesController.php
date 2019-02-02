<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\ReplyRequest;
use App\Models\Topic;
use App\Models\Reply;
use App\Transformers\ReplyTransformer;
use App\Models\User;

class RepliesController extends Controller
{

    /**
     * 新建话题回复
     *
     * @param ReplyRequest $request
     * @param Topic $topic
     * @param Reply $reply
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->input('content');
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }


    /**
     * 删除话题回复
     *
     * @param Topic $topic
     * @param Reply $reply
     * @return \Dingo\Api\Http\Response|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic, Reply $reply)
    {
        // 用户提交的话题 id 和回复表中的话题 id 必须一致
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }


    /**
     * 话题回复列表
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }


    /**
     * 用户回复列表
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user)
    {
        $replies = $user->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }




}
