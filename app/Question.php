<?php

namespace App;

use App\Events\QuestionCreatedEvent;
use App\Events\QuestionDeletedEvent;
use Illuminate\Database\Eloquent\Model;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class Question extends Model
{
    // 解决sync不触发事件的问题
    use PivotEventTrait;
    protected $fillable = ['title', 'user_id', 'body', 'flowers_count', 'comments_count', 'close_comment', 'answers_count', 'is_hidden'];

    public function isHidden()
    {
        return $this->is_hidden === 'T';
    }

    public function isClose()
    {
        return $this->close_comment === 'T';
    }

    protected $dispatchesEvents = [
        'created' => QuestionCreatedEvent::class,
    ];

    /**
     * 当前问题的多态关联
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    /**
     * 定义问题答案的relationship （一对多）
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }


    /**
     * 话题多对多关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function topic()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    /**
     *  隐藏的帖子不显示
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('is_hidden', 'F');
    }

    /**
     * 用户的一对一关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 问题关注者(多对多)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follower_question', 'question_id', 'user_id')->withTimestamps();
    }

    /**
     * relationship事件
     */
    public static function boot()
    {
        parent::boot();

        static::pivotAttaching(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //
        });

        static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {

            // 问题和关注者attached事件
            if ($relationName === 'followers') {
                $followers_count = count($pivotIds);
                $model->increment('flowers_count', $followers_count);
            }


            // 问题和话题的attached事件
            if ($relationName == 'topic') {
                array_map(function ($topic_id) {
                    Topic::find($topic_id)->increment('questions_count', 1);
                }, $pivotIds);
            }


        });

        static::pivotDetaching(function ($model, $relationName, $pivotIds) {

        });

        static::pivotDetached(function ($model, $relationName, $pivotIds) {

            if ($relationName === 'followers') {
                $followers_count = count($pivotIds);
                $model->decrement('flowers_count', $followers_count);
            }


            // 当问题关联的话题被detached 话题下面的问题数量自然要减去一个
            if ($relationName === 'topic') {
                // 因为触发 所以$pivotIds一定是有值的
                array_map(function ($topic_id) {
                    Topic::find($topic_id)->increment('questions_count', -1);
                }, $pivotIds);
            }
        });

        static::pivotUpdating(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //
        });

        static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //
        });

        static::updating(function ($model) {
            //this is how we catch standard eloquent events
        });
    }
}
