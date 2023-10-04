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
		$termscondition = TermAndCondition::where('id',$id)->first();
        return view('terms_conditions.edit', compact('termscondition'));
	}

    public function update(Request $request,$id){
        $termscondition = TermAndCondition::find($id);
        $termscondition->content = $request->input('content');
        $termscondition->update();
        return redirect()->back()->with('status','Terms & Condition Updated Successfully');
    }
}
