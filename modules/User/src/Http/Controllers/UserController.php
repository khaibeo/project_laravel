<?php
namespace Modules\User\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Modules\User\src\Http\Requests\UserRequest;
use Modules\User\src\Repositories\UserRepository;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(){
        $pageTitle = "Quản lý người dùng";
        return view('User::list' , compact('pageTitle'));
    }

    public function detail($id){
        return "Chi tiet san pham $id";
    }

    public function add(){
        $pageTitle = "Thêm người dùng";

        return view('User::add' , compact('pageTitle'));
    }

    public function store(UserRequest $request){
        $this->userRepo->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'group_id' => $request->group_id
        ]);

        return redirect()->route('admin.users.index')->with('msg','Thêm thành công');
    }

    public function data(){
        $users = $this->userRepo->getAllUser();

        return DataTables::of($users)
        ->addColumn('edit', '<a href="#" class="btn btn-warning">Sửa</a>')
        ->addColumn('delete', '<a href="#" class="btn btn-danger">Xóa</a>')
        ->editColumn('created_at', function($user){
            return Carbon::parse($user->created_at)->format('d/m/Y H:i:s');
        })
        ->rawColumns(['edit', 'delete'])
        ->toJson();
    }
}