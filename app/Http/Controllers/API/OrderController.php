<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\APIResource;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $carts = Cart::with('product')
                    ->where('user_id', $request->user()->id)
                    ->orderBy('updated_at', 'desc')
                    ->get();
        if ($carts->isEmpty()) {
            $message = ['message' => "cartss not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }

        $totalAmount = 0;

        foreach ($carts as $cart) {
            $cart->total_amount = $cart->quantity * $cart->product->price;
            $totalAmount += $cart->total_amount;
        }

        return response([
            'code' => 200,
            'total_amount' => $totalAmount,
            'data' => $carts,
        ], 200);
    }

    public function createOrder(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'total_amount' => 'required|numeric|min:1',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return (new APIResource(false, 400, $validator->errors()))->response()->setStatusCode(400);
        }

        try {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_amount' => $request->total_amount,
            ]);

            foreach ($request->products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ]);
            }

            Cart::where('user_id', $request->user()->id)->delete();

            return (new APIResource(true, 200, ['message' => 'Successfully placed an order']))->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return (new APIResource(false, 500, ['message' => 'An error occurred while creating the order']))->response()->setStatusCode(500);
        }
    }

    public function getOrders(Request $request)
    {
        if ($request->user()->isAdmin === 1) {
            $orders = Order::with('orders','orders.product')
                        ->orderBy('updated_at', 'desc')
                        ->get();
        } else {
            $orders = Order::with('orders','orders.product')
                        ->where('user_id', $request->user()->id)
                        ->orderBy('updated_at', 'desc')
                        ->get();
        }
        if ($orders->isEmpty()) {
            $message = ['message' => "Orders not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        return (new APIResource(true, 200, $orders))->response()->setStatusCode(200);
    }

    public function payment(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return (new APIResource(false, 400, $validator->errors()))->response()->setStatusCode(400);
        }
        
        $order = Order::find($id);
        if ($order == null || $order->status == "completed") {
            $message = ['message' => "Order not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        $imageName = time() . '_' . $id . '.' . $request->file('payment_proof')->getClientOriginalExtension();
        $imagePath = $request->file('payment_proof')->storeAs('payment', $imageName, 'public');
        
        $input = [
            'payment_proof' => $imagePath,
            'status' => 'completed'
        ];

        $order->fill($input)->save();

        return (new APIResource(true, 200, $order))->response()->setStatusCode(200);
    }
}
