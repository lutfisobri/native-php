<?php
namespace App\Controller;

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
}