<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;
use App\Models\Clients;
use App\Models\Departments;
use App\Models\Expense;
use App\Models\Orders;
use App\Models\Planner;
use App\Models\Products;
use App\Models\Positions;
use App\Models\Staff;
use App\Models\Suppliers;
use App\Models\Settings;
class statsController extends Controller
{
    public function list(){
        



        $bsay = Brands::count();

        $csay = Clients::count();

        $dsay = Departments::count();

        $esay = Expense::count();
        $osay = Orders::count();
        $psay = Planner::count();
        $prosay = Products::count();
        $posay = Positions::count();
        $ssay = Staff::count();
        $supsay = Suppliers::count();
        $activeTasks = Planner::where('accept', '=', 0)->count();
        $completeTasks = Planner::where('accept', '=', 1)->count();
        $amountSay = Expense::sum('amount');

        $settings = Settings::first();



        return view('stats',[
            'bsay'=>$bsay,
            'csay'=>$csay,
            'dsay'=>$dsay,
            'esay'=>$esay,
            'osay'=>$osay,
            'psay'=>$psay,
            'prosay'=>$prosay,
            'posay'=>$posay,
            'ssay'=>$ssay,
            'supsay'=>$supsay,
            'activeTasks'=>$activeTasks,
            'completeTasks'=>$completeTasks,
            'amountSay'=>$amountSay,
            'settings'=>$settings,
        ]);
    }
}
