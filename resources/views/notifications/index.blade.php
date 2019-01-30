@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">

                <div class="card-body">

                    <h3 class="text-xs-center">
                        <i class="far fa-bell" aria-hidden="true"></i> 我的通知
                    </h3>
                    <hr>

                    @if ($notifications->count())

                        <div class="list-unstyled notification-list">
                            @foreach ($notifications as $notification)
                                {{--snake_case(class_basename($notification->type)) 渲染以后会是 —— topic_replied。
                                class_basename() 方法会取到 TopicReplied，Laravel 的辅助方法 snake_case()
                                会字符串格式化为下划线命名。--}}
                                @include('notifications.types._' . snake_case(class_basename($notification->type)))
                            @endforeach

                            {!! $notifications->render() !!}
                        </div>

                    @else
                        <div class="empty-block">没有消息通知！</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@stop