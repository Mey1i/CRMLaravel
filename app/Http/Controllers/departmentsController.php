<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\departmentsRequest;

use App\Models\Departments;
use App\Models\Positions;
use App\Models\Staff;
use App\Exports\DepartmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class departmentsController extends Controller
{
    public function send(departmentsRequest $post){

        $user_id = Auth::id();
        $departmentName = $post->department;

        $departmentName = Departments::where('department', '=', $post->department)->first();
        if ($departmentName) {
            return redirect()->route('departments')->with(['message' => 'Department is already in the database!', 'message_type' => 'danger']);
        }

        $con = new Departments;

        $con->user_id = Auth::id();
        $con->department = $post->department;

        $con->save();

        return redirect()->route('departments')->with(['message' => 'Department has been added successfully!', 'message_type' => 'success']);

    }
    public function list(Request $request){
        $user = Auth::user();

        $data = Departments::orderBy('id', 'desc')->get();
        $say = Departments::count();
        $psay = Positions::count();
        $ssay = Staff::count();
        $settings = Settings::first();

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Departments::where('department', 'LIKE', "%$search%")->orderBy('id', 'desc')->get();
        } else {
            $data = Departments::orderBy('id', 'desc')->get();
        }

        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No departments found with the given search query' : null;


        return view('departments',[
            'data'=>$data,
            'say'=>$say,
            'psay'=>$psay,
            'ssay'=>$ssay,
            'userMenu' => $userMenu,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'settings' => $settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function delete($id){
        $data = Departments::find($id)->delete();
        return redirect()->route('departments')->with(['message' => 'Department has been deleted successfully!', 'message_type' => 'success']);
    }


    public function edit(Request $request, $id)
    {   $user = Auth::user();
        $say = Departments::count();
        $psay = Positions::count();
        $ssay = Staff::count();
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
        $edit = Departments::find($id);

        $search = $request->input('search', '');
        $notFoundMessage = null;
    
        if ($search !== "") {
            $data = Departments::where('department', 'LIKE', "%$search%")->orderBy('id', 'desc')->get();
            $notFoundMessage = $data->isEmpty() ? 'No departments found with the given search query' : null;
        }
        else{
            $data = Departments::orderBy('id', 'desc')->get();
        }
    
        if (!$edit) {
            return redirect()->route('department')->with(['message' => 'Department not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('departments');
        }
    
        return view('departments', [
            'data' => $data,
            'say' => $say,
            'userMenu' => $userMenu,
            'psay' => $psay,
            'ssay' => $ssay,
            'edit' => $edit,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'settings' => $settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }
    

    public function update(departmentsRequest $post)
    {
        $yoxla = Departments::where('department', '=', $post->department)
            ->where('id', '!=', $post->id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Departments::find($post->id);
    
            $con->department = $post->department;
    
            $con->save();
    
            return redirect()->route('departments')->with(['message' => 'Department has been updated successfully!', 'message_type' => 'success']);
        }
    }


    public function delete_selected_departments(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No departments selected for deletion!', 'message_type' => 'danger']);
    }

    Departments::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected departments have been deleted successfully!', 'message_type' => 'success']);
}

public function export_departments(){return Excel::download(new DepartmentsExport,'departments.xlsx');}
}
