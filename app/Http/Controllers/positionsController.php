<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\positionsRequest;
use App\Models\Positions;
use App\Models\Departments;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use App\Exports\PositionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
class positionsController extends Controller
{
    public function send(positionsRequest $post){

        $con = new Positions;

        $con->user_id = Auth::id();
        $con->department_id = $post->department_id;
        $con->position = $post->position;

        $con->save();

        return redirect()->route('positions')->with(['message' => 'Position has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){
        $user = Auth::user();
        $ddata = Departments::orderBy('id','asc')->get();
        $say = Positions::count();
        $dsay = Departments::count();
        $ssay = Staff::count();
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Positions::join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*','positions.id as posid')
            ->where('departments.department','LIKE',"%$search%")
            ->orWhere('positions.position','LIKE',"%$search%")
            ->orderBy('positions.id','desc')
            ->get();
        } else {
            $data = Positions::join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*','positions.id as posid')
            ->orderBy('positions.id','desc')
            ->get();
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No positions found with the given search query' : null;

        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
    
        

        return view('positions',[
            'data'=>$data,
            'say'=>$say,
            'dsay'=>$dsay,
            'ssay'=>$ssay,
            'ddata'=>$ddata,
            'user' => $user,
            'userMenu' => $userMenu,
            'image'=>$user->image,
            'name' => $user->name,
            'settings'=>$settings,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function delete($id){
        $ddata = Departments::orderBy('id','asc')->get();
        $data = Positions::find($id)->delete();
        return redirect()->route('positions')->with(['message' => 'Position has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request,$id)
    {
        $user = Auth::user();
        $settings = Settings::first();
        $ddata = Departments::orderBy('id','asc')->get();
        $say = Positions::count();
        $dsay = Departments::count();
        $ssay = Staff::count();
        $edit = Positions::find($id);
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }    
        if (!$edit) {
            return redirect()->route('positions')->with(['message' => 'Position not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('positions');
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Positions::join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*','positions.id as posid')
            ->where('departments.department','LIKE',"%$search%")
            ->orWhere('positions.position','LIKE',"%$search%")
            ->orderBy('positions.id','desc')
            ->get();
        } else {
            $data = Positions::join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('*','positions.id as posid')
            ->orderBy('positions.id','desc')
            ->get();
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No positions found with the given search query' : null;
    
        return view('positions', [
            'data' => $data,
            'say' => $say,
            'dsay'=>$dsay,
            'ssay'=>$ssay,
            'edit' => $edit,
            'ddata'=>$ddata,
            'settings'=>$settings,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'userMenu' => $userMenu,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function update(positionsRequest $post,$id)
    {
        $yoxla = Positions::where('position', '=', $post->position)
            ->where('id', '!=', $id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Positions::find($id);
    
            $con->department_id = $post->department_id;
            $con->position = $post->position;

            $con->save();
    
            
        }
        return redirect()->route('positions')->with(['message' => 'Position has been updated successfully!', 'message_type' => 'success']);
    }

    public function delete_selected_positions(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No positions selected for deletion!', 'message_type' => 'danger']);
    }

    Positions::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected positions have been deleted successfully!', 'message_type' => 'success']);
}


public function export_positions(){return Excel::download(new PositionsExport,'positions.xlsx');}

}
