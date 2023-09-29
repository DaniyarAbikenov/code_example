<?php

namespace App\Http\Controllers;

use App\Http\Middleware\isAdmin;
use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $meals = Meal::all();
        $orders = Order::on()->where("user_id", "=", Auth::id())->get();
        $users = [];
        return view("order.index", compact("orders", "meals", "users"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "meal"=>["required", "int"],
            "count"=>["required", "int"],
        ]);
        Order::create([
            "product"=>$request->meal,
            "count"=>$request->count,
            "user_id"=>Auth::id()
        ]);
        return back();
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            "meal"=>["required", "int"],
            "count"=>["required", "int"],
        ]);
        $order->update([
            "product"=>$request["meal"],
            "count"=>$request["count"]
        ]);
        if (isAdmin::class){
            $order->update([
                "user_id"=>$request["user"],
            ]);
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back();
    }
}
