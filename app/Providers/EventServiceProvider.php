<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserCreateEvent' => [
            'App\Listeners\UserCreateListener',
        ],
        'App\Events\QuestionCreatedEvent' => [
            'App\Listeners\QuestionCreatedListen'
        ],
        'App\Events\TopicDeleteEvent' => [
            'App\Listeners\TopicDeleteListener'
        ],
        'App\Events\QuestionTopicDeleteEvent' => [
            'App\Listeners\QuestionTopicDeleteListener'
        ],
        'App\Events\QuestionDeletedEvent' => [
            'App\Listeners\QuestionDeletedListener'
        ],
        'App\Events\AnswerCreatedEvent' => [
            'App\Listeners\AnswerCreatedListener'
        ],
        'App\Events\CommentCreatedEvent' => [
            'App\Listeners\CommentCreatedListen'
        ],
        'App\Events\PermissionCreatedEvent' => [
            'App\Listeners\PermissionCreatedListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
