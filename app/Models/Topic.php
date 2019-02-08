<?php

namespace App\Models;

class Topic extends Model
{

    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug'
    ];

    /**
     * 话题所对应的分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }

    /**
     * 话题所对应的作者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order){
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }


    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 给 url 添加参数
     * @param array $params
     * @return string
     */
    public function link($params = []){
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    /**
     * 一条帖子拥有多个话题
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 显示5条回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topReplies()
    {
        return $this->replies()->limit(5);
    }

    /**
     * 重新计算回复帖子数量
     */
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }




}
