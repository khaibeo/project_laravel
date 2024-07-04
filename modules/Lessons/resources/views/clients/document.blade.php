<ul class="list-group mt-3">
    @foreach (getLessonsByPosition($course, null, true) as $lesson)
        <li class="list-group-item"><a download="{{ $lesson->document->url }}" target="_blank" href="{{ $lesson->document->url }}">{{ $lesson->name }} {{ getSize($lesson->document->size,'MB') . ' MB' }}</a></li>
    @endforeach
</ul>