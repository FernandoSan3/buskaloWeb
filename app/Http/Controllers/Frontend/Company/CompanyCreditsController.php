<?php

namespace App\Http\Controllers\Frontend\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;

/**
 * Class CreditsController.
 */
class CompanyCreditsController extends Controller
{
    public function index()
    {
       return view('frontend.company.credits');
    }

}