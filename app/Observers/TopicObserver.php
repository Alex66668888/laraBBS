<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic){
        // 避免 xss 攻击
        $topic->body = clean($topic->body, 'user_topic_body');

        // 裁剪部分文本
        $topic->excerpt = make_excerpt($topic->body);
    }



}