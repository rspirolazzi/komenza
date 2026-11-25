<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use OwenIt\Auditing\Models\Audit;

class AuditorController extends Controller
{
    public function index(){
        $auditories = Audit::orderBy('created_at','desc')->paginate(25);
        return view('admin.auditories.index')
            ->with('auditories', $auditories);
    }

    public  function show(Request $request){
        $id = $request->input('id');
        $auditory = Audit::find($id);
        $auditory->user_complete = User::where('id',$auditory->user_id)->first();
        return response()->json($auditory);
    }
}
