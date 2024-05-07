<?php
namespace Modules\Dashboard\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Dashboard\src\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    public function index(){
        $pageTitle = "Bảng điều khiển";
        return view('Dashboard::dashboard', compact('pageTitle'));
    }
}