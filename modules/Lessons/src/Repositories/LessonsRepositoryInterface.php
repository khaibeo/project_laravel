<?php

namespace Modules\Lessons\src\Repositories;

use App\Repositories\RepositoryInterface;

interface LessonsRepositoryInterface extends RepositoryInterface
{
    public function getLessons($courseId);

    public function getAllLessions();

    public function getPosition($courseId);

    public function getLessonActive($slug);

    public function getLessonsByPosition($course, $moduleId = null, $isDocument = null);
}
