<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();

        if (!$products || $products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
                'success' => true,
                'message' => 'Get products success',
                'data'    => $products,
            ], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_id'   => 'required|exists:category_products,id',
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $imagePath = $request->file('image')->store('product_images', 'public');

        $product = Product::create([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'price'         => $request->price,
            'image'         => $imagePath,
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data'    => $product,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product creation failed',
        ], 400);
    }

    public function show($id) {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Get product success',
                'data'    => $product,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ], 404);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->only(['category_id', 'name', 'price', 'image']), [
            'category_id'   => 'required|exists:category_products,id',
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
                'data'    => $request->all()
            ], 422);
        }

        $product = Product::find($id);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $imagePath = $request->file('image')->store('product_images', 'public');
            $product->image = $imagePath;
        }

        if ($product) {
            $product->update([
                'category_id'   => $request->category_id,
                'name'          => $request->name,
                'price'         => $request->price,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data'    => $product,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ], 404);
    }

    public function destroy($id) {
        $product = Product::find($id);

        if ($product) {
            Storage::disk('public')->delete($product->image);

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ], 200);
        }    

        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ], 404);
    }

}
