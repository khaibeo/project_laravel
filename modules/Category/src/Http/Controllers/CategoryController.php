<?php
namespace Modules\Category\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Modules\Category\src\Http\Requests\CategoryRequest;
use Modules\Category\src\Repositories\CategoryRepository;
use Yajra\DataTables\Facades\DataTables;
class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index(){
        $pageTitle = "Quản lý danh mục";
        return view('Category::list' , compact('pageTitle'));
    }

    public function add(){
        $pageTitle = "Thêm danh mục";

        return view('Category::add' , compact('pageTitle'));
    }

    public function store(CategoryRequest $request){
        $this->categoryRepo->create([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('admin.categories.index')->with('msg','Thêm thành công');
    }

    public function data(){
        $categories = $this->categoryRepo->getCategories();

        return DataTables::of($categories)
        ->addColumn('link', function($category){
            return "<a href='#' class='btn btn-primary'>Link</a>";
        })
        ->addColumn('edit', function($category){
            return '<a href="'. route('admin.categories.edit',$category) .'" class="btn btn-warning">Sửa</a>';
        })
        ->addColumn('delete', function($category){
            return "<a href='". route('admin.categories.delete',$category) . "' class='btn btn-danger delete-action'>Xóa</a>";
        })
        ->editColumn('created_at', function($category){
            return Carbon::parse($category->created_at)->format('d/m/Y H:i:s');
        })
        ->rawColumns(['edit', 'delete','link'])
        ->toJson();
    }

    public function delete($id){
        $this->categoryRepo->delete($id);

        return back()->with('msg','Xóa thành công');
    }

    public function edit($id){
        $pageTitle = "Sửa thông tin danh mục";
        $category = $this->categoryRepo->find($id);

        return view('Category::edit' , compact('pageTitle','category'));
    }

    public function update(CategoryRequest $request,$id){
        $data = $request->except('_token');

        $this->categoryRepo->update($id,$data);

        return redirect()->route('admin.categories.index')->with('msg','Sửa thông tin thành công');
    }
}