<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);

        if ($products->count() > 0) {
            return response()->json([
                'status' => 200,
                'products' => $products
            ], 200);
        }

        return response()->json([
            'status' => 404,
            'message' => "No records Found!"
        ], 404);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30',
            'slug' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $product = Product::create($request->all());

            if ($product) {
                return response()->json([
                    'status' => 200,
                    'message' => "Product Created Successfully!"
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Something went wwrong creating Product!"
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Product Found!"
            ], 404);
        }
    }

    /**
     * Search for a specified set of record(s).
     */
    public function search(string $query)
    {
        $result = Product::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'status' => 200,
            'result' => $result,
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => "Product record Not Found!"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30',
            'slug' => 'required|string|max:30',
            'description' => 'required|string|max:30',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => $validator->messages()
            ], 404);
        }

        if ($product && !$validator->fails()) {
            if ($product->update($request->all())) {
                return response()->json([
                    'status' => 200,
                    'product' => "Product record updated Successfully!"
                ], 200);
            }
            return response()->json([
                'status' => 404,
                'message' => "Something went wrong Updating Product record!"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product) {
            $product->delete();

            return response()->json([
                'status' => 200,
                'message' => "Product record deleted successfully!"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Product Found!"
            ], 404);
        }
    }
}
