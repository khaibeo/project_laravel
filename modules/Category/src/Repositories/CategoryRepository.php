<?php 
namespace Modules\Category\src\Repositories;
use App\Repositories\BaseRepository;
use Modules\Category\src\Models\Category;
use Modules\Category\src\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function getModel()
    {
        return Category::class;
    }
    
    public function getCategories(){
        return $this->model->with('subCategories')->select(['id','name','slug','parent_id','created_at'])->whereParentId(0)->orderBy('created_at', 'desc')->get();
    }

    public function getAllCategories(){
        return $this->model->select(['id','name','slug','parent_id','created_at'])->orderBy('created_at', 'desc')->get();
    }
}