<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        return response()->json(Order::with(['user', 'orderItems.product'])->get());
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        $total_price = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $total_price += $product->price * $item['quantity'];
        }

        // تطبيق الخصم في حالة وجود كوبون صالح
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = ($total_price * $coupon->discount_percentage) / 100;
                $total_price -= $discount;
            }
        }

        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $total_price,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => Product::find($item['product_id'])->price,
            ]);
        }

        return response()->json(['success' => true, 'data' => $order], 201);
    }


    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'data' => $order]);
    }


    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->orderItems()->delete();
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted']);
    }
}
