<?php

namespace App\Controllers;
use App\Models\Barberos_db;
use PhpParser\Node\Expr\AssignOp\Mod;

class Admin extends BaseController
{
    public function index()
    {
        return view('admin');
    }

}