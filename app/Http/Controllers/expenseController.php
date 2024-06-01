<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\expenseRequest;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExpenseExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
class expenseController extends Controller
{
    public function send(expenseRequest $post){

        $con = new Expense;

        $con->user_id = Auth::id();
        $con->appointment = $post->appointment;
        $con->amount = $post->amount;

        $con->save();

        return redirect()->route('expense')->with(['message' => 'Expense has been added successfully!', 'message_type' => 'success']);

    }

    public function list(Request $request){
        $user = Auth::user();
        $say = Expense::count();
        $asay = Expense::sum('amount');
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Expense::where('appointment', 'LIKE', "%$search%")
            ->orWhere('amount', 'LIKE', "%$search%")
            ->orderBy('id','desc')
            ->get();
        } else {
            $data = Expense::orderBy('id', 'desc')->get();
        }
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No expenses found with the given search query' : null;
    

        return view('expense',[
            'data'=>$data,
            'say'=>$say,
            'asay'=>$asay,
            'user' => $user,
            'image'=>$user->image,
            'name' => $user->name,
            'userMenu' => $userMenu,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'settings'=>$settings,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function delete($id){
        $data = Expense::find($id)->delete();
        return redirect()->route('expense')->with(['message' => 'Exoense has been deleted successfully!', 'message_type' => 'success']);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $data = Expense::orderBy('id', 'desc')->get();
        $say = Expense::count();
        $asay = Expense::sum('amount');
        $edit = Expense::find($id);
        $settings = Settings::first();
        try {
            $userMenu = unserialize(Auth::user()->menu);
        } catch (\Exception $e) {
            $userMenu = [];
        }    
        if (!$edit) {
            return redirect()->route('expense')->with(['message' => 'Expense not found!', 'message_type' => 'danger']);
        }

        if (request()->has('cancel_clicked')) {
            return redirect()->route('expense');
        }

        $search = $request->input('search', '');
    
        if ($search !== "") {
            $data = Expense::where('appointment', 'LIKE', "%$search%")
            ->orWhere('amount', 'LIKE', "%$search%")
            ->orderBy('id','desc')
            ->get();
        } else {
            $data = Expense::orderBy('id', 'desc')->get();
        }
        $notFoundMessage = ($search !== "" && $data->isEmpty()) ? 'No expenses found with the given search query' : null;
    
        return view('expense', [
            'data' => $data,
            'say' => $say,
            'settings'=>$settings,
            'edit' => $edit,
            'asay'=>$asay,
            'user' => $user,
            'image'=>$user->image,
            'userMenu' => $userMenu,
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
            'search' => $search,
            'notFoundMessage' => $notFoundMessage,
        ]);
    }

    public function update(expenseRequest $post)
    {
        $user_id = Auth::id();
        $yoxla = Expense::where('appointment', '=', $post->appointment)
            ->where('id', '!=', $post->id)
            ->orWhere('amount', '=', $post->amount)
            ->where('id', '!=', $post->id)
            ->count();
    
        if ($yoxla == 0) {
            $con = Expense::find($post->id);
    
            $con->appointment = $post->appointment;
            $con->amount = $post->amount;

            $con->save();
    
            return redirect()->route('expense')->with(['message' => 'Expense has been updated successfully!', 'message_type' => 'success']);
        }
    }


    public function delete_selected_expense(Request $request)
{
    $secim = $request->secim;

    // Check if $secim is an array and not null before using it in the whereIn method
    if (!is_array($secim) || empty($secim)) {
        // Handle the case where $secim is not an array or is empty
        return redirect()->back()->with(['message' => 'No expenses selected for deletion!', 'message_type' => 'danger']);
    }

    Expense::whereIn('id', $secim)->delete();

    return redirect()->back()->with(['message' => 'Selected expenses have been deleted successfully!', 'message_type' => 'success']);
}

public function export_expense(){return Excel::download(new ExpenseExport,'expense.xlsx');}
}
