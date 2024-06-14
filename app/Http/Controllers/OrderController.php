<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function showAllOrders()
    {
        return response()->json(Order::with('products')->get());
        =

    public function showOneOrder($id)
    {
        return response()->json(Order::with('products')->find($id));
    
    }

    public function create(Request $request)
    {
        $orderData = $request->only(['buyer_name', 'email', 'contact_number', 'order_date', 'delivery_address', 'order_status']);
        $productsData = $request->input('products');

        $order = Order::create($orderData);

        foreach ($productsData as $productData) {
            $productData['order_id'] = $order->id;
            Product::create($productData);
        }

        return response()->json($order->load('products'), 201);

   =
    }

    public function update($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only(['buyer_name', 'email', 'contact_number', 'order_date', 'delivery_address', 'order_status']));

        // Update products
        $productsData = $request->input('products');
        foreach ($productsData as $productData) {
            if (isset($productData['id'])) {
                $product = Product::findOrFail($productData['id']);
                $product->update($productData);
            } else {
                $productData['order_id'] = $order->id;
                Product::create($productData);
            }
        }

        return response()->json($order->load('products'), 200);
    

    public function delete($id)
    {
        Order::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
        
}

