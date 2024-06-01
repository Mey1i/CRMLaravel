<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\staffRequest;
use App\Models\Staff;
use App\Models\Positions;
use App\Models\Departments;
use App\Models\Settings;
use App\Exports\StaffExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class staffController extends Controller
{
    public function send(staffRequest $post){
        $staffTelephone = $post->telephone;
        $staffEmail = $post->email;
    
        $existingStaffByTelephone = Staff::where('telephone', $staffTelephone)->first();
        $existingStaffByEmail = Staff::where('email', $staffEmail)->first();
    
        if ($existingStaffByEmail && $existingStaffByTelephone) {
            return redirect()->route('staff')->with(['message' => 'This telephone and email is already entered into the database!', 'message_type' => 'danger']);
        }
    
        if ($existingStaffByEmail) {
            return redirect()->route('staff')->with(['message' => 'This email is already entered into the database!', 'message_type' => 'danger']);
        }
    
        if ($existingStaffByTelephone) {
            return redirect()->route('staff')->with(['message' => 'This telephone  is already entered into the database!', 'message_type' => 'danger']);
        }

        $post->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ]);

        $con = new Staff;

        if ($post->file('image')) 
        {
            $file = time().'.'.$post->image->extension();
            $post->image->storeAs('public/uploads/staff', $file);
            $con->image = 'storage/uploads/staff/'.$file;
        }

        $con->user_id = Auth::id();
        $con->position_id = $post->position_id;
        $con->name = $post->name;
        $con->surname = $post->surname;
        $con->email = $post->email;
        $con->telephone = $post->telephone;
        $con->salary = $post->salary;
        $con->work = $post->work;

        $con->save();

        return redirect()->route('staff')->with(['message' => 'Staff has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){
        $user = Auth::user();
        $data = Staff::join('positions', 'staff.position_id', '=', 'positions.id')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*',  'positions.position as position', 'staff.id as sid')
            ->orderBy('staff.id', 'desc')
            ->get();
    
        $posdata = Positions::join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('positions.*', 'departments.department as department')
            ->orderBy('positions.id', 'desc')
            ->get();
            $settings = Settings::first();
        $psay = Positions::count();
        $dsay = Departments::count();
        $say = Staff::count();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }    

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Staff::join('positions', 'staff.position_id', '=', 'positions.id')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*',  'positions.position as position', 'staff.id as sid')
            ->where('name', 'LIKE', "%$search%")
            ->orWhere('surname','LIKE',"%$search%")
            ->orWhere('email','LIKE',"%$search%")
            ->orWhere('telephone','LIKE',"%$search%")
            ->orWhere('salary','LIKE',"%$search%")
            ->orWhere('positions.position','LIKE',"%$search%")
            ->orWhere('departments.department','LIKE',"%$search%")
            ->orderBy('staff.id', 'desc')
            ->get();
        } else {
            $data = Staff::join('positions', 'staff.position_id', '=', 'positions.id')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*',  'positions.position as position', 'staff.id as sid')
            ->orderBy('staff.id','desc')
            ->get();
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No staff found with the given search query' : null;

        return view('staff', [
            'data' => $data,
            'say' => $say,
            'dsay'=>$dsay,
            'psay'=>$psay,
            'posdata' => $posdata,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'userMenu' => $userMenu,
            'settings'=>$settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }
    
    public function delete($id){
        $data = Staff::find($id)->delete();
        return redirect()->route('staff')->with(['message' => 'Staff has been deleted successfully!', 'message_type' => 'success']);
    }


    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $posdata = Positions::join('departments', 'positions.department_id', '=', 'departments.id')
        ->select('*','positions.id as posid')
        ->orderBy('posid','desc')
        ->get();
        $psay = Positions::count();
        $dsay = Departments::count();
        $say = Staff::count();
        $edit = Staff::find($id);
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }    
        if (!$edit) {
            return redirect()->route('staff')->with(['message' => 'Staff not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('staff');
        }
        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Staff::join('positions', 'staff.position_id', '=', 'positions.id')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*',  'positions.position as position', 'staff.id as sid')
            ->where('name', 'LIKE', "%$search%")
            ->orWhere('surname','LIKE',"%$search%")
            ->orWhere('email','LIKE',"%$search%")
            ->orWhere('telephone','LIKE',"%$search%")
            ->orWhere('salary','LIKE',"%$search%")
            ->orWhere('positions.position','LIKE',"%$search%")
            ->orWhere('departments.department','LIKE',"%$search%")
            ->orderBy('staff.id', 'desc')
            ->get();
        } else {
            $data = Staff::join('positions', 'staff.position_id', '=', 'positions.id')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*',  'positions.position as position', 'staff.id as sid')
            ->orderBy('staff.id','desc')
            ->get();
        }

        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No staff found with the given search query' : null;
    
        return view('staff', [
            'data' => $data,
            'say' => $say,
            'dsay'=>$dsay,
            'psay'=>$psay,
            'edit' => $edit,
            'posdata' => $posdata,
            'settings'=>$settings,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'userMenu' => $userMenu,
            'user_id' => $user->id,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function update(staffRequest $post)
    {
        $yoxla = Staff::where('name', '=', $post->name)
            ->where('id', '!=', $post->id)
            ->orwhere('surname', '=', $post->surname)
            ->where('id', '!=', $post->id)
            ->orwhere('email', '=', $post->email)
            ->where('id', '!=', $post->id)
            ->orwhere('telephone', '=', $post->telephone)
            ->where('id', '!=', $post->id)
            ->orwhere('salary', '=', $post->salary)
            ->where('id', '!=', $post->id)
            ->orwhere('work', '=', $post->work)
            ->where('id', '!=', $post->id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Staff::find($post->id);
    
            $con->position_id = $post->position_id;
            $con->name = $post->name;
            $con->surname = $post->surname;
            $con->email = $post->email;
            $con->telephone = $post->telephone;
            $con->salary = $post->salary;
            $con->work = $post->work;

            if ($post->file('new_image')) {
                $post->validate([
                    'new_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
                ]);
    
                $file = time().'.'.$post->new_image->extension();
                $post->new_image->storeAs('public/uploads/staff', $file);
                $con->image = 'storage/uploads/staff/'.$file;
            }

            $con->save();
    
            return redirect()->route('staff')->with(['message' => 'Staff has been updated successfully!', 'message_type' => 'success']);
        }
    }


    public function delete_selected_staff(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No staff selected for deletion!', 'message_type' => 'danger']);
    }

    Staff::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected staff have been deleted successfully!', 'message_type' => 'success']);
}


public function export_staff(){return Excel::download(new StaffExport,'staff.xlsx');}

}
