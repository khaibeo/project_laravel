<?php

namespace Modules\Lessons\src\Http\Controllers\clients;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Modules\Lessons\src\Http\Requests\LessonRequest;
use Modules\Video\src\Repositories\VideoRepositoryInterface;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;
use Modules\Document\src\Repositories\DocumentRepositoryInterface;
use Modules\Lessons\src\Models\Lesson;

class LessonController extends Controller
{
    protected $lessonRepository;
    public function __construct(LessonsRepositoryInterface $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function index($slug)
    {
        $currentLesson = $this->lessonRepository->getLessonActive($slug);

        $pageTitle = $currentLesson->name;
        $pageName = $currentLesson->name;

        $course = $currentLesson->course;
        $index = 0;

        $lessons = $this->lessonRepository->getLessonsByPosition($course);

        $currentLessonIndex = null;
        $prevLesson = null;
        $nextLesson = null;

        foreach ($lessons as $key => $item) {
            if($currentLesson->id == $item->id){
                $currentLessonIndex = $key;
                break;
            }
        }

        if(isset($lessons[$currentLessonIndex + 1])){
            $nextLesson = $lessons[$currentLessonIndex + 1];
        }

        if(isset($lessons[$currentLessonIndex - 1])){
            $prevLesson = $lessons[$currentLessonIndex - 1];
        }

        return view('Lessons::clients.index', compact('pageTitle', 'currentLesson','pageName', 'course','index','nextLesson', 'prevLesson'));
    }
}
