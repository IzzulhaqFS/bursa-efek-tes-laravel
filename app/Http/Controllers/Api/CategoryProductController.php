<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use App\Http\Resources\CategoryProductResource;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends Controller
{
    public function index()
    {
        $categories = CategoryProduct::all();

        if (!$categories || $categories->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
                'success' => true,
                'message' => 'Get category success',
                'data'    => $categories,
            ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = CategoryProduct::create([
            'name'    => $request->name,
        ]);

        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data'    => $category,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category creation failed',
        ], 400);
    }

    public function show($id) {
        $category = CategoryProduct::find($id);

        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Get category success',
                'data'    => $category,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category not found',
        ], 404);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = CategoryProduct::find($id);

        if ($category) {
            $category->update([
                'name'    => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data'    => $category,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category not found',
        ], 404);
    }

    public function destroy($id) {
        $category = CategoryProduct::find($id);

        if ($category) {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        }    

        return response()->json([
            'success' => false,
            'message' => 'Category not found',
        ], 404);
    }
}
