<?php 
namespace Modules\User\src\Repositories;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Modules\User\src\Repositories\UserRepositoryInterface;
use Modules\User\src\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getUsers($limit)
    {
        return $this->model->paginate($limit);
    }

    public function getAllUser(){
        return $this->model->select(['id','name','email','group_id','created_at'])->get();
    }

    public function setPassword($password, $id)
    {
        $user = $this->update($id,['password' => Hash::make($password)]);
    }

    public function checkPassword($password,$id)
    {
        $user = $this->find($id);

        if($user){
            $hashPassword = $user->password;
            return Hash::check($password,$hashPassword); 
        }
        return false;
    }
}