<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\APIResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderByDesc("created_at")->get();
        if ($product->isEmpty()) {
            $message = ['message' => "Products not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        return (new APIResource(true, 200, $product))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return (new APIResource(false, 400, $validator->errors()))->response()->setStatusCode(400);
        }

        $product = Product::create($request->all());
        return (new APIResource(true, 200, $product))->response()->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            $message = ['message' => "Product not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        return (new APIResource(true, 200, $product))->response()->setStatusCode(200);
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
        dd($request->name);
        $product = Product::find($id);
        if ($product == null) {
            return response([
                'code' => 404,
                'errors' => [
                    'message' => "Product not found"
                ]
            ],404);
        }
        $update = $request->post();
        dd($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            $message = ['message' => "Product not found"];
            return (new APIResource(false, 404, $message))->response()->setStatusCode(404);
        }
        $product->delete();
        $message = ['message' => "Success delete product"];
        return (new APIResource(true, 200, $message))->response()->setStatusCode(200);
    }
}
