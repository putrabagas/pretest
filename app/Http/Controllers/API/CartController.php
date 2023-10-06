<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\APIResource;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carts = Cart::with('product')
                    ->where('user_id', $request->user()->id)
                    ->orderBy('updated_at', 'desc')
                    ->get();
        if ($carts->isEmpty()) {
            $message = ['message' => "cartss not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        return (new APIResource(true, 200, $carts))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return (new APIResource(false, 400, $validator->errors()))->response()->setStatusCode(400);
        }
        $input = $request->all();
        $input['user_id'] = $request->user()->id;
        $cart = Cart::create($input);
        return (new APIResource(true, 200, $cart))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return (new APIResource(false, 400, $validator->errors()))->response()->setStatusCode(400);
        }

        $cart = Cart::find($id);
        if ($cart == null) {
            $message = ['message' => "Cart not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        $cart->fill($request->all())->save();
        return (new APIResource(true, 200, $cart))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        {
            $cart = Cart::find($id);
            if ($cart == null) {
                $message = ['message' => "cart not found"];
                return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
            }
            $cart->delete();
            $message = ['message' => "Success delete cart"];
            return (new APIResource(true, 200, $message))->response()->setStatusCode(200);
        }
    }
}
