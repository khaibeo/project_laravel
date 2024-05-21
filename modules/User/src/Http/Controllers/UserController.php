<?php
namespace Modules\User\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Modules\User\src\Http\Requests\UserRequest;
use Modules\User\src\Repositories\UserRepository;
use Modules\User\src\Repositories\UserRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
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
        ->addColumn('edit', function($user){
            return '<a href="'. route('admin.users.edit',$user) .'" class="btn btn-warning">Sửa</a>';
        })
        ->addColumn('delete', function($user){
            return "<a href='". route('admin.users.delete',$user) . "' class='btn btn-danger delete-action'>Xóa</a>";
        })
        ->editColumn('created_at', function($user){
            return Carbon::parse($user->created_at)->format('d/m/Y H:i:s');
        })
        ->rawColumns(['edit', 'delete'])
        ->toJson();
    }

    public function delete($id){
        $this->userRepo->delete($id);

        return back()->with('msg','Xóa thành công');
    }

    public function edit($id){
        $pageTitle = "Sửa thông tin người dùng";
        $user = $this->userRepo->find($id);

        return view('User::edit' , compact('pageTitle','user'));
    }

    public function update(UserRequest $request,$id){
        $data = $request->except('password','_token');

        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $this->userRepo->update($id,$data);

        return redirect()->route('admin.users.index')->with('msg','Sửa thông tin thành công');
    }
}