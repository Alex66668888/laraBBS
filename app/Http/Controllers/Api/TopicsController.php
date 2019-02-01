<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;

class TopicsController extends Controller
{

    /**
     * 新建话题（帖子）
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());

        // $this->user() 等同于\Auth::guard('api')->user()
        // 见 Dingo\Api\Routing\Helpers 这个 trait
        $topic->user_id = $this->user()->id;

        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);

    }


    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return $this->response->item($topic, new TopicTransformer());
    }




}
