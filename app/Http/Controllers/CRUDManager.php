<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Products;

class CRUDManager extends Controller
{
    function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->messages()]);
        }
        $product = new Products;
        $product->name = $request->input('name');
        $product->user_id = auth()->user()->id;
        $product->description = $request->input('description');
        $product->price = $request->input('price');

        if($product->save()){
            return response()->json([
                'status' => 'success',
                'product' => $product,
                'message' => 'Product created'
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'Product not created']);

    }
    
    function read(){
        $products = Products::all();
        return response()->json(['status' => 'success', 'product' => $products, 'message' => 'Products read']);

    }
    function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>'error', 'message' => $validator->messages()]);
        }
        $validator = $validator->validated();
        $product = Products::where('id', $id)->where("user_id", auth()->user()->id)->first();
        if(!$product){
            return response()->json(['status'=> 'error', 'message' => 'Product not found']);
        }
        $product-> name = $validator['name'];
        $product-> description = $validator['description'];
        $product-> price = $validator['price'];
        if($product->save()){
            return response()->json(['status' => 'success', 'product' => $product, 'message' => 'Product updated']);

        }
        return response()->json(['status' => 'error', 'message' => 'Product updated failed return']);


        

    }
    function delete($id){
        if(Products::where('id', $id)->where ("user_id",auth()->user()->id)->delete()){
        return response()->json(['status' => 'success', 'message' => 'Prodoct has been deleted']);
        }
    }
}
