<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\plannerRequest;
use App\Models\Planner;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use App\Exports\PlannerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
class plannerController extends Controller
{
    public function send(plannerRequest $post){

        $user_id = Auth::id();
        $con = new Planner;
        $con->user_id = Auth::id();
        $con->staff_id = $post->staff_id;
        $con->task = $post->task;
        $con->dtask = $post->dtask;
        $con->ttask = $post->ttask;
        $con->accept = 0;

        $con->save();

        return redirect()->route('planner')->with(['message' => 'Task has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request)
    {
        $user = Auth::user();
    
        $sdata = Staff::orderBy('id', 'asc')->get();
        $say = Planner::count();
        $activeTasks = Planner::where('accept', '=', 0)->count();
        $completeTasks = Planner::where('accept', '=', 1)->count();
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }
    
    
        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Planner::join('staff', 'planners.staff_id', '=', 'staff.id')
            ->select('*', 'staff.id as sid','planners.id as pid')
            ->where('planners.task', 'LIKE', "%$search%")
                ->orWhere('staff.name', 'LIKE', "%$search%")
                ->orWhere('staff.surname', 'LIKE', "%$search%")
                ->orderBy('planners.id', 'desc')
                ->get();
        }
        else
        {
                        $data = Planner::join('staff', 'planners.staff_id', '=', 'staff.id')
            ->select('*', 'staff.id as sid','planners.id as pid')                
            ->orderBy('pid', 'desc')
            ->get();
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No tasks records found with the given search query' : null;

        if (!$data->isEmpty()) {
            foreach ($data as $task) {
                $deadline = new \DateTime($task->dtask . ' ' . $task->ttask);
                $now = new \DateTime();
                $remainingTime = $now->diff($deadline)->format('%a days, %h hours, %i minutes');
                $task->remainingTime = $remainingTime;
            }
        }
    
        return view('planner', [
            'data' => $data,
            'say' => $say,
            'activeTasks' => $activeTasks,
            'completeTasks' => $completeTasks,
            'user' => $user,
            'image' => $user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'userMenu' => $userMenu,
            'settings' => $settings,
            'sdata' => $sdata,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }
    

    public function delete($id){
        $data = Planner::find($id)->delete();
        return redirect()->route('planner')->with(['message' => 'Task has been deleted successfully!', 'message_type' => 'success']);
    }


    public function edit(Request $request, $id)
    {
        $user = Auth::user();

    
        $say = Planner::count();
        $activeTasks = Planner::where('accept', '=', 0)->count();
        $completeTasks = Planner::where('accept', '=', 1)->count();
        $edit = Planner::find($id);
        $sdata = Staff::orderBy('id','asc')->get();
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }    
        if (!$edit) {
            return redirect()->route('planner')->with(['message' => 'Task not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('planner');
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Planner::join('staff', 'planners.staff_id', '=', 'staff.id')
            ->select('*', 'staff.id as sid','planners.id as pid')
            ->where('planners.task', 'LIKE', "%$search%")
                ->orWhere('staff.name', 'LIKE', "%$search%")
                ->orWhere('staff.surname', 'LIKE', "%$search%")
                ->orderBy('planners.id', 'desc')
                ->get();
        }
        else
        {
                        $data = Planner::join('staff', 'planners.staff_id', '=', 'staff.id')
            ->select('*', 'staff.id as sid','planners.id as pid')                
            ->orderBy('pid', 'desc')
            ->get();
        }
    
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No tasks records found with the given search query' : null;

        if (!$data->isEmpty()) {
            foreach ($data as $task) {
                $deadline = new \DateTime($task->dtask . ' ' . $task->ttask);
                $now = new \DateTime();
                $remainingTime = $now->diff($deadline)->format('%a days, %h hours, %i minutes');
                $task->remainingTime = $remainingTime;
            }
        }

    
        return view('planner', [
            'data' => $data,
            'say'=>$say,
            'settings'=>$settings,
            'activeTasks'=>$activeTasks,
            'completeTasks'=>$completeTasks,
            'edit' => $edit,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'userMenu' => $userMenu,
            'sdata'=>$sdata,
            'search'=>$search,
            'notFoundMessage'=>$notFoundMessage,
        ]);
    }

    public function update(plannerRequest $post)
    {
        $yoxla = Planner::where('task', '=', $post->task)
            ->where('id', '!=', $post->id)
            ->orwhere('dtask', '=', $post->dtask)
            ->where('id', '!=', $post->id)
            ->orwhere('ttask', '=', $post->ttask)
            ->where('id', '!=', $post->id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Planner::find($post->id);
    
            $con->task = $post->task;
            $con->dtask = $post->dtask;
            $con->ttask = $post->ttask;

            $con->save();
    
            return redirect()->route('planner')->with(['message' => 'Task has been updated successfully!', 'message_type' => 'success']);
        }
    }

    public function accept($id)
    {
        $task = Planner::find($id);

        if ($task) {
            $task->accept = 1;
            $task->save();

            return redirect()->route('planner')->with(['message' => 'Task has been accepted successfully!', 'message_type' => 'success']);
        }

        return redirect()->route('planner')->with(['message' => 'Task not found', 'message_type' => 'danger']);
    }

    public function cancel($id)
    {
        $task = Planner::find($id);

        if ($task->accept == 1) {
            $task->accept = 0;
            $task->save();
            return redirect()->route('planner')->with(['message' => 'Task has been canceled successfully!', 'message_type' => 'success']);
        }

        return redirect()->route('planner')->with(['message' => 'Task not found', 'message_type' => 'danger']);
    }

    public function delete_selected_planner(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No tasks selected for deletion!', 'message_type' => 'danger']);
    }

    Planner::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected tasks have been deleted successfully!', 'message_type' => 'success']);
}

public function export_planner(){return Excel::download(new PlannerExport,'planner.xlsx');}

}

