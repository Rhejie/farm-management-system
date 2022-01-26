<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {

        return view('company.index');

    }

    public function createJob()
    {

        return view('company.create_job');

    }

    public function jobs()
    {

        return view('company.jobs');

    }

    public function applicants()
    {

        return view('company.applicants');

    }

    public function profile()
    {

        return view('company.profile');

    }
}
