<?php
namespace Modules\User\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\User\src\Repositories\UserRepository;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(){
        $user = $this->userRepo->getUser();
        dd($user);
        return view('User::list' , compact('user'));
    }

    public function detail($id){
        return "Chi tiet san pham $id";
    }
}