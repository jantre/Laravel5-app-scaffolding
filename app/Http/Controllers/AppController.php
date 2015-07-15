<?php
namespace App\Http\Controllers;

class AppController extends Controller
{
    public function __construct()
    {
        //$this->beforeFilter('auth');
    $this->middleWare('auth');
    }

    public function getMain()
    {
        return view('app.main');
    }
}
