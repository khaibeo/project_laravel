<?php
namespace Modules\Category\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Modules\Category\src\Http\Requests\CategoryRequest;
use Modules\Category\src\Repositories\CategoryRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index(){
        $pageTitle = "Quản lý danh mục";
        return view('Category::list' , compact('pageTitle'));
    }

    public function add(){
        $pageTitle = "Thêm danh mục";
        $categories = $this->categoryRepo->getAllCategories();

        return view('Category::add' , compact('pageTitle','categories'));
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

        $categories = DataTables::of($categories)
        // ->addColumn('link', function($category){
        //     return "<a href='#' class='btn btn-primary'>Link</a>";
        // })
        // ->addColumn('edit', function($category){
        //     return '<a href="'. route('admin.categories.edit',$category) .'" class="btn btn-warning">Sửa</a>';
        // })
        // ->addColumn('delete', function($category){
        //     return "<a href='". route('admin.categories.delete',$category) . "' class='btn btn-danger delete-action'>Xóa</a>";
        // })
        // ->editColumn('created_at', function($category){
        //     return Carbon::parse($category->created_at)->format('d/m/Y H:i:s');
        // })
        // ->rawColumns(['edit', 'delete','link'])
        ->toArray();

        $categories['data'] = $this->getTreeCategory($categories['data']);
        
        return $categories;

        // return DataTables::of($categories)
        // ->addColumn('link', function($category){
        //     return "<a href='#' class='btn btn-primary'>Link</a>";
        // })
        // ->addColumn('edit', function($category){
        //     return '<a href="'. route('admin.categories.edit',$category) .'" class="btn btn-warning">Sửa</a>';
        // })
        // ->addColumn('delete', function($category){
        //     return "<a href='". route('admin.categories.delete',$category) . "' class='btn btn-danger delete-action'>Xóa</a>";
        // })
        // ->editColumn('created_at', function($category){
        //     return Carbon::parse($category->created_at)->format('d/m/Y H:i:s');
        // })
        // ->rawColumns(['edit', 'delete','link'])
        // ->toJson();
    }

    public function getTreeCategory($categories, $char= '',&$result = []){
        if(!empty($categories)){
            foreach ($categories as $key => $category) {
                $row = $category;
                $row['name'] = $char . $row['name'];
                $row['link'] = "<a href='/danh-muc/{$category['slug']}' class='btn btn-primary'>Link</a>";
                $row['edit'] = '<a href="'. route('admin.categories.edit',$category['id']) .'" class="btn btn-warning">Sửa</a>';
                $row['delete'] = "<a href='". route('admin.categories.delete',$category['id']) . "' class='btn btn-danger delete-action'>Xóa</a>";
                $row['created_at'] = Carbon::parse($category['created_at'])->format('d/m/Y H:i:s');

                unset($row['sub_categories']);
                unset($row['updated_at']);

                $result[] = $row;
                
                if(!empty($category['sub_categories'])){
                    $this->getTreeCategory($category['sub_categories'],$char. '|--',$result);
                }
            }
        }

        return $result;
    }

    public function delete($id){
        $this->categoryRepo->delete($id);

        return back()->with('msg','Xóa thành công');
    }

    public function edit($id){
        $pageTitle = "Sửa thông tin danh mục";
        $category = $this->categoryRepo->find($id);
        $categories = $this->categoryRepo->getAllCategories();

        return view('Category::edit' , compact('pageTitle','category','categories'));
    }

    public function update(CategoryRequest $request,$id){
        $data = $request->except('_token');

        $this->categoryRepo->update($id,$data);

        return redirect()->route('admin.categories.index')->with('msg','Sửa thông tin thành công');
    }
}