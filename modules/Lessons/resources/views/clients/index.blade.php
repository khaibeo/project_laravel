@extends('layouts.client')

@section('content')
@include('parts.clients.page_title')
<section class="video">
    <div class="container">
        <h3>{{ $currentLesson->name }} </h3>
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="video-detail">
                    <video id="my-video"
                        class="video-js"
                        preload="auto"
                        controls
                        data-setup="{}">
                            <source src="/data/stream?video={{ $currentLesson->video->url }}" type="video/mp4" />
                    <p class="vjs-no-js">
                    To view this video please enable JavaScript
                    </p>
                        </video>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        @if ($prevLesson)
                            <a href="{{ route('lesson.index', $prevLesson->slug) }}" class="prev text-white bg-primary">Quay lại</a>
                        @endif
                    </div>
                    
                    <div>
                        @if ($nextLesson)
                            <a href="{{ route('lesson.index', $nextLesson->slug) }}" class="next text-white bg-primary">Tiếp theo</a>
                        @endif 
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="nav flex">
                    <p class="lesson active">Bài học</p>
                    <p class="document">Tài liệu</p>
                </div>
                <div class="group">
                    <div class="accordion active title mt-0">
                        @include('Lessons::clients.lesson')
                    </div>
                    <div class="document-title title">
                        @include('Lessons::clients.document')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection