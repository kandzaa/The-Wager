<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function changeUsername()
    {

        $user       = Auth::user();
        $user->name = request('name');
        $user->save();
        return redirect()->route('profile');
    }

    public function changeEmail()
    {
        $user        = Auth::user();
        $user->email = request('email');
        $user->save();
        return redirect()->route('profile');
    }

}
