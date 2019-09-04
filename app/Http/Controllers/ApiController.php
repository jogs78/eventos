<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ApiController extends Controller
{
    use ApiResponser;

    public function __construct(){
 		//$this->middleware('auth');
    }

    protected function allowedAdminAction()
    {
	    if (Gate::denies('admin-action')) {
            throw new AuthorizationException('Esta acción no te es permitida');
        }    	
    }

    protected function allowedStaffAction()
    {
	    if (Gate::denies('staff-action')) {
            throw new AuthorizationException('Esta acción no te es permitida');
        }    	
    }

    protected function allowedAdminStaffAction()
    {
	    if (Gate::denies('admin-action') && Gate::denies('staff-action')) {
            throw new AuthorizationException('Esta acción no te es permitida');
        }    	
    }
}
