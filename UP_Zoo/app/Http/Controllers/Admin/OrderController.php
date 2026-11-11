<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index', [
            'orders' => Order::latest()->paginate(20),
            'statuses' => $this->statuses(),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('items'),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->validate([
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
        ]));

        return back()->with('success', 'Статус заказа обновлён.');
    }

    private function statuses(): array
    {
        return [
            'new' => 'Новый',
            'processing' => 'В обработке',
            'shipped' => 'Передан в доставку',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменён',
        ];
    }
}
