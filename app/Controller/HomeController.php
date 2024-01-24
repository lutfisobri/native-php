<?php
namespace App\Controller;

use App\Model\User;
use Riyu\Foundation\Auth\Auth;
use Riyu\Http\Request;
use Riyu\Http\Response;
use views\Home;

class HomeController extends Controller
{
    public function index()
    {
        return widget(Home::class);
    }

    public function about()
    {
        echo 'About Us';
        return 'About Us';
    }

    public function contact()
    {
        echo 'Contact Us';
        return 'Contact Us';
    }

    public function login(Request $request, User $user)
    {
        // dd($request->all());
        $errors = $this->validate($request, [
            'username' => 'required|min:5',
            'password' => 'required|min:5'
        ], [
            'required' => ':field harus diisi.'
        ]);

        if ($errors) {
            return redirect()->route('login');
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return redirect()->route('login');
        } else {
            if (!password_verify($request->password, $user->password)) {
                return redirect()->route('login');
            }
        }

        
        auth()->login($user);

        // app()->make(Auth::class)->login($user);

        return redirect()->route('home');

        return 'Login Success';
    }

    public function logout(User $user)
    {
        auth()->logout();

        return redirect()->route('home');
    }

    public function user(User $user)
    {
        return $user;
    }
}