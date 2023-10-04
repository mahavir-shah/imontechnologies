<?php

namespace App\Http\Controllers;

use App\Models\TermAndCondition;
use Auth;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
{
    public function index(){
		/*if(\Auth::user()->can('manage terms condition'))
        {*/	
			$termscondition = TermAndCondition::get();
            return view('terms_conditions.index', compact('termscondition'));
       /*  }
       else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }*/
	}
	
	public function edit($id){
		$termscondition = TermAndCondition::where('id',$id)->get();
        return view('terms_conditions.edit', compact('termscondition'));
	}
}
