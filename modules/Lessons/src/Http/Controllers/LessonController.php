<?php

namespace Modules\Lessons\src\Http\Controllers;

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
    protected $coursesRepository, $videoRepository, $documentRepository, $lessonRepository;
    public function __construct(CoursesRepositoryInterface $coursesRepository, VideoRepositoryInterface $videoRepository, DocumentRepositoryInterface $documentRepository, LessonsRepositoryInterface $lessonRepository)
    {
        $this->coursesRepository = $coursesRepository;
        $this->videoRepository = $videoRepository;
        $this->documentRepository = $documentRepository;
        $this->lessonRepository = $lessonRepository;
    }

    public function index($courseId)
    {
        $course = $this->coursesRepository->find($courseId);

        $pageTitle = "Bài giảng: " . $course->name;

        return view('Lessons::lists', compact('pageTitle', 'course'));
    }

    public function data($courseId)
    {
        $lessons = $this->lessonRepository->getLessons($courseId);

        $lessons = DataTables::of($lessons)->toArray();

        $lessons['data'] = $this->getLessionsTable($lessons['data']);
        return $lessons;
    }

    public function getLessionsTable($lessons, $char = '', &$result = [])
    {
        if (!empty($lessons)) {
            foreach ($lessons as $key => $lesson) {
                $row = $lesson;
                $row['name'] = $char . $row['name'];
                if ($row['parent_id'] == null) {
                    $row['is_trial'] = '';
                    $row['view'] = '';
                    $row['durations'] = '';
                    $row['add'] = '<a href="' . route('admin.lessons.create', $row['course_id']) . '?module=' . $row['id'] . '" class="btn btn-primary btn-sm">Thêm bài</a>';
                    $row['edit'] = '<a href="' . route('admin.lessons.edit', $row['id']) . '" class="btn btn-warning btn-sm">Sửa</a>';
                    $row['delete'] = '<a href="' . route('admin.lessons.delete', $row['id']) . '" class="btn btn-danger btn-sm delete-action">Xóa</a>';
                } else {
                    $row['is_trial'] = ($row['is_trial'] == 1 ? 'Có' : 'Không');
                    $row['view'] = $row['view'];
                    $row['durations'] = getTime($row['durations']);
                    $row['add'] = '';
                    $row['edit'] = '<a href="' . route('admin.lessons.edit', $row['id']) . '" class="btn btn-warning btn-sm">Sửa</a>';
                    $row['delete'] = '<a href="' . route('admin.lessons.delete', $row['id']) . '" class="btn btn-danger btn-sm delete-action">Xóa</a>';
                }

                unset($row['sub_lessons']);
                unset($row['course_id']);

                $result[] = $row;
                if (!empty($lesson['sub_lessons'])) {
                    $this->getLessionsTable($lesson['sub_lessons'], $char . '|--', $result);
                }
            }
        }

        return $result;
    }

    public function create(Request $request, $courseId)
    {
        $pageTitle = 'Thêm bài giảng';
        $position = $this->lessonRepository->getPosition($courseId);
        $lessons = $this->lessonRepository->getAllLessions();
        return view('Lessons::add', compact('pageTitle', 'courseId', 'position', 'lessons'));
    }

    public function store($courseId, LessonRequest $request)
    {

        $name = $request->name;
        $slug = $request->slug;
        $video = $request->video;
        $document = $request->document;
        $parentId = $request->parent_id == 0 ? null : $request->parent_id;
        $isTrail = $request->is_trial;
        $position = $request->position;
        $description = $request->description;
        $status = $request->status;

        $documentId = null;
        $videoId = null;
        if ($document) {
            $documentInfo = getFileInfo($document);
            $document = $this->documentRepository->createDocument([
                'name' => $documentInfo['name'],
                'url' => $document,
                'size' => $documentInfo['size']
            ], $document);
            $documentId = $document ? $document->id : null;
        }
        if ($video) {
            $videoInfo = getVideoInfo($video);
            $video = $this->videoRepository->createVideo(['url' => $video, 'name' => $videoInfo['filename'], 'size' => $videoInfo['playtime_seconds']], $video);
            $videoId = $video ? $video->id : null;
        }

        $this->lessonRepository->create([
            'name' => $name,
            'slug' => $slug,
            'video_id' => $videoId,
            'course_id' => $courseId,
            'document_id' => $documentId,
            'parent_id' => $parentId,
            'is_trial' => $isTrail,
            'position' => $position,
            'durations' => $videoInfo['playtime_seconds'] ?? 0,
            'description' => $description,
            'status' => $status ?? 0,
        ]);

        $this->updateDurations($courseId);
        return redirect()->route('admin.lessons.index', $courseId)->with('msg', 'Thêm bài giảng thành công');
    }

    public function edit(Request $request, $lessonId)
    {
        $pageTitle = 'Cập nhật bài giảng';
        $lessons = $this->lessonRepository->getAllLessions();
        $lesson = $this->lessonRepository->find($lessonId);
        $lesson->video = $lesson->video?->url;
        $lesson->document = $lesson->document?->url;

        if (!$lesson) {
            return abort(404);
        }
        $courseId = $lesson->course_id;

        return view('Lessons::edit', compact('pageTitle', 'courseId', 'lessons', 'lesson'));
    }

    public function update(LessonRequest $request, $lessonId)
    {
        //Xử lý update
        $name = $request->name;
        $slug = $request->slug;
        $video = $request->video;
        $document = $request->document;
        $parentId = $request->parent_id == 0 ? null : $request->parent_id;
        $isTrail = $request->is_trial;
        $position = $request->position;
        $description = $request->description;
        $status = $request->status ?? 0;

        $documentId = null;
        $videoId = null;
        if ($document) {
            $documentInfo = getFileInfo($document);
            $document = $this->documentRepository->createDocument([
                'name' => $documentInfo['name'],
                'url' => $document,
                'size' => $documentInfo['size']
            ], $document);
            $documentId = $document ? $document->id : null;
        }
        if ($video) {
            $videoInfo = getVideoInfo($video);
            $video = $this->videoRepository->createVideo(['url' => $video, 'name' => $videoInfo['filename'], 'size' => $videoInfo['playtime_seconds']], $video);
            $videoId = $video ? $video->id : null;
        }

        $this->lessonRepository->update($lessonId, [
            'name' => $name,
            'slug' => $slug,
            'video_id' => $videoId,
            'document_id' => $documentId,
            'parent_id' => $parentId,
            'is_trial' => $isTrail,
            'position' => $position,
            'durations' => $videoInfo['playtime_seconds'] ?? 0,
            'description' => $description,
            'status' => $status
        ]);

        $lesson = $this->lessonRepository->find($lessonId);
        $courseId = $lesson->course_id;
        $this->updateDurations($courseId);

        return redirect()->route('admin.lessons.edit', $lessonId)->with('msg', 'Sửa bài giảng thành công');
    }

    public function delete(Request $request, $lessonId)
    {
        $lesson = $this->lessonRepository->find($lessonId);

        $this->lessonRepository->delete($lessonId);

        $courseId = $lesson->course_id;
        $this->updateDurations($courseId);

        return redirect()->route('admin.lessons.index', $courseId)->with('msg', 'Xóa thành công');
    }

    public function sort(Request $request, $courseId)
    {
        $pageTitle = 'Sắp xếp bài giảng';
        $modules = $this->lessonRepository->getLessons($courseId)->with('children')->get();
        return view('Lessons::sort', compact('pageTitle', 'courseId', 'modules'));
    }
    public function handleSort(Request $request, $courseId)
    {
        $lessons = $request->lesson;
        if ($lessons) {
            foreach ($lessons as $index => $lessionId) {
                $this->lessonRepository->update($lessionId, [
                    'position' => $index
                ]);
            }
            return redirect()->route('admin.lessons.sort', $courseId)->with('msg', 'Sắp xếp bài giảng thành công');
        }
    }

    private function updateDurations($courseId)
    {
        $lessons = $this->lessonRepository->getAllLessions($courseId);

        $durations = $lessons->reduce(function ($prev, $item) {
            return $prev + $item->durations;
        }, 0);

        $this->coursesRepository->update($courseId, [
            'durations' => $durations,
        ]);
    }
}
