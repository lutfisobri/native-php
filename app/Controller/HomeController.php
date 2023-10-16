<?php
namespace App\Controller;

use Riyu\Http\Request;
use Riyu\Http\Response;

class HomeController extends Controller
{
    public function index($id)
    {
        return 'id: ' . $id;
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

    public function login(Request $request)
    {
        $errors = $this->validate($request, [
            'username' => 'required|min:5|max:10',
            'password' => 'required|min:5|max:10'
        ], [
            'required' => ':field harus diisi.'
        ]);

        if ($errors) {
            return (new Response())->json($errors)->code(400);
        }

        return 'Login Success';
    }
}