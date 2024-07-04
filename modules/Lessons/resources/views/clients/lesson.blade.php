@foreach (getModuleByPosition($course) as $key => $module)
    <div class="accordion-group">
        <h4 class="accordion-title mb-2 {{ $module->id == $currentLesson->parent_id ? 'active' : '' }}">{{ $module->name }}</h4>
        <div class="accordion-detail" style="{{  $module->id == $currentLesson->parent_id ? 'display: block;' : '' }}">
            @foreach (getLessonsByPosition($course, $module->id) as $lesson)
                <div class="card-accordion">
                    <div>
                        <i class="fa-brands fa-youtube"></i>
                        <a class="text-dark {{ $lesson->id == $currentLesson->id ? 'fw-bold' : '' }}" href="{{route('lesson.index', $lesson->slug)}}">{{"Bài ".(++$index).": ".$lesson->name}}</a>
                        <span>{{ getTime($lesson->durations) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach