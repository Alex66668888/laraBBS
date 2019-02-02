<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use App\Models\User;

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


    /**
     * 更新话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return $this->response->item($topic, new TopicTransformer());
    }


    /**
     * 删除话题
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();
        return $this->response->noContent();
    }


    /**
     * 话题列表（支持分类查询、创建时间和更新时间排序）
     * @param Request $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();

        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        // 为了说明 N+1问题，不使用 scopeWithOrder
        switch ($request->order) {
            case 'recent':
                // 创建时间排序
                $query->recent();
                break;

            default:
                // 更新时间排序
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }


    /**
     * 根据用户查询话题
     * @param User $user
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user, Request $request)
    {
        $topics = $user->topics()->recent()
            ->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }


    /**
     * 单个话题详情
     *
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function show(Topic $topic){
        return $this->response->item($topic, new TopicTransformer());
    }




}
