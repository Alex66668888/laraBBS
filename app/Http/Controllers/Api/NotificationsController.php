<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\NotificationTransformer;

class NotificationsController extends Controller
{

    /**
     * 通知列表
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationTransformer());
    }


    /**
     * 通知数据统计
     * @return mixed
     */
    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }


    /**
     * 标记所有未读消息为已读状态
     *
     * @return \Dingo\Api\Http\Response
     */
    public function read()
    {
        $this->user()->markAsRead();

        return $this->response->noContent();
    }





}