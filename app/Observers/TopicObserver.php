<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;

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

    public function saved(Topic $topic){

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {

            // 推动任务到队列
            dispatch(new TranslateSlug($topic));
        }

    }


    public function deleted(Topic $topic){
        // 话题删除之后，对应话题回复也删除
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }



}