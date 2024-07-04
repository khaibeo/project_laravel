<?php

namespace Modules\Lessons\src\Repositories;

use App\Repositories\BaseRepository;
use Modules\Lessons\src\Models\Lesson;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;

use function Illuminate\Events\queueable;

class LessonsRepository extends BaseRepository implements LessonsRepositoryInterface
{
    public function getModel()
    {
        return Lesson::class;
    }

    public function getPosition($courseId)
    {
        $result = $this->model->where('course_id', $courseId)->count();
        return $result + 1;
    }

    public function getLessons($courseId)
    {
        return $this->model->with('subLessons')->whereCourseId($courseId)->whereNull('parent_id')->select(['id', 'name', 'slug', 'is_trial', 'parent_id', 'view', 'durations', 'course_id'])->orderBy('position', 'asc');
    }

    public function getLessonActive($slug)
    {
        return $this->model->whereSlug($slug)->firstOrFail();
    }

    public function getAllLessions()
    {
        return $this->getAll();
    }

    public function getLessonCount($course)
    {
        return (object) [
            'module' => $course->lessons()->whereNull('parent_id')->count(),
            'lessons' => $course->lessons()->whereNotNull('parent_id')->count(),
        ];
    }

    public function getModuleByPosition($course)
    {
        return $course->lessons()->whereNull('parent_id')->orderBy('position')->get();
    }

    public function getLessonsByPosition($course, $moduleId = null, $isDocument = null)
    { 
        $query = $course->lessons();

        if($moduleId){
            $query = $query->where('parent_id', $moduleId);
        }else{
            $query = $query->whereNotNull('parent_id');
        }

        if($isDocument){
            $query = $query->whereNotNull('document_id');
        }

        return $query->orderBy('position')->get();
    }
}
