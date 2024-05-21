<?php

namespace Modules\Courses\src\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Modules\Courses\src\Models\Course;
use Yajra\DataTables\Facades\DataTables;
use Modules\Courses\src\Http\Requests\CoursesRequest;
use Modules\Category\src\Repositories\CategoryRepositoryInterface;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Teacher\src\Repositories\TeacherRepositoryInterface;

class CoursesController extends Controller
{
    protected $coursesRepository;
    protected $categoriesRepository;
    protected $teacherRepo;

    public function __construct(CoursesRepositoryInterface $coursesRepository, CategoryRepositoryInterface $categoriesRepository,
    TeacherRepositoryInterface $teacherRepository)
    {
        $this->coursesRepository = $coursesRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->teacherRepo = $teacherRepository;
    }
    public function index()
    {
        $pageTitle = 'Quản lý khóa học';

        return view('Courses::lists', compact('pageTitle'));
    }

    public function data()
    {
        $courses = $this->coursesRepository->getAllCourses();

        $data =  DataTables::of($courses)
        ->addColumn('edit', function ($course) {
            return '<a href="'.route('admin.courses.edit', $course).'" class="btn btn-warning">Sửa</a>';
        })
        ->addColumn('delete', function ($course) {
            return '<a href="'.route('admin.courses.delete', $course).'" class="btn btn-danger delete-action">Xóa</a>';
        })
        ->editColumn('created_at', function ($course) {
            return Carbon::parse($course->created_at)->format('d/m/Y H:i:s');
        })
        ->editColumn('status', function ($course) {
            return $course->status == 1 ? '<button class="btn btn-success">Ra mắt</button>' : '<button class="btn btn-warning">Chưa ra mắt</button>';
        })
        ->editColumn('price', function ($course) {
            if ($course->price) {
                if ($course->sale_price) {
                    $price = number_format($course->sale_price).'đ';
                } else {
                    $price = number_format($course->price).'đ';
                }
            } else {
                $price = 'Miễn phí';
            }

            return $price;
        })
        ->rawColumns(['edit', 'delete', 'status'])
        ->toJson();
        return $data;
    }

    public function create()
    {
        $pageTitle = 'Thêm khóa học';

        $categories = $this->categoriesRepository->getAllCategories();

        $teachers = $this->teacherRepo->getAllTeacher()->get();


        return view('Courses::add', compact('pageTitle', 'categories','teachers'));
    }

    public function store(CoursesRequest $request)
    {
        $courses = $request->except(['_token']);

        if (!$courses['sale_price']) {
            $courses['sale_price'] = 0;
        }

        if (!$courses['price']) {
            $courses['price'] = 0;
        }

        $course = $this->coursesRepository->create($courses);

        $categories = $this->getCategories($courses);

        $this->coursesRepository->createCourseCategories($course, $categories);


        return redirect()->route('admin.courses.index')->with('msg', 'Thêm thành công');
    }

    public function edit($id)
    {
        $course = $this->coursesRepository->find($id);

        $categoryIds = $this->coursesRepository->getRelatedCategories($course);

        $categories = $this->categoriesRepository->getAllCategories();
        $teachers = $this->teacherRepo->getAllTeacher()->get();


        if (!$course) {
            abort(404);
        }

        $pageTitle = 'Cập nhật khóa học';

        return view('Courses::edit', compact('course', 'pageTitle', 'categories', 'categoryIds','teachers'));
    }

    public function update(CoursesRequest $request, $id)
    {

        $courses = $request->except(['_token', '_method']);
        if (!$courses['sale_price']) {
            $courses['sale_price'] = 0;
        }

        if (!$courses['price']) {
            $courses['price'] = 0;
        }


        $this->coursesRepository->update($id, $courses);

        $categories = $this->getCategories($courses);

        $course = $this->coursesRepository->find($id);

        $this->coursesRepository->updateCourseCategories($course, $categories);

        return back()->with('msg', 'Sửa thành công');
    }

    public function delete($id)
    {
        $course = $this->coursesRepository->find($id);
        // $this->coursesRepository->deleteCourseCategories($course);
        $status = $this->coursesRepository->delete($id);
        if($status){
            $image = public_path($course->thumbnail);

            File::delete($image);
        }
        return back()->with('msg', 'Xóa thành công');
    }

    public function getCategories($courses)
    {
        $categories = [];
        foreach ($courses['categories'] as $category) {
            $categories[$category] = ['created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')];
        }

        return $categories;
    }
}
