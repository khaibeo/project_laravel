<?php

namespace Modules\Auth\src\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->email_verified_at) {
            return redirect()->route('home');
        }
        $pageTitle = 'Kích hoạt tài khoản';
        return view('Auth::clients.verify', compact('pageTitle'));
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('msg', 'Email kích hoạt đã được gửi thành công');
    }
}