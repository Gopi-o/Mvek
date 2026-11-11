<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'orderCount' => Order::count(),
            'newOrderCount' => Order::where('status', 'new')->count(),
            'userCount' => User::where('role', 'user')->count(),
            'pendingReviewCount' => Review::where('is_approved', false)->count(),
            'orders' => Order::latest()->take(6)->get(),
        ]);
    }
}
