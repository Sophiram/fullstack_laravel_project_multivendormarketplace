<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * បង្ហាញទំព័រគ្រប់គ្រង Commission
     */
    public function index()
    {
        // ហៅទៅកាន់ឯកសារ Blade View របស់ Admin ផ្ទាល់
        return view('admin.manage.commission');
    }
}
