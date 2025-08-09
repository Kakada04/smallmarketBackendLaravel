<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all()->map(function ($categories) {
        $categories->full_banner_url = asset('storage/' . $categories->category_image);
        return $categories;
    });

        return response()->json([
            "status"=>true,
            "message"=>"Success",
            "data"=>$categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       $data= $request->validate([
            'type' => 'required',
            'category_image'=>'required'
            
        ]);
if ($request->hasFile('category_image')) {
        $data['category_image'] = $request->file('category_image')->store('product_image', 'public');
    }
    Category::create($data);
        return response()->json([
            'status' => true,
            
            'message' => 'Type Add Success',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
         Category::find($category->id);
         return response()->json([
            'status'=> true,
            'message'=> 'added success',
            'data'=> $category
         ]);

    }
   public function showProductsByCategory($categoryID)
    {
        $category = Category::where('id', $categoryID)->first();
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
                'data' => null
            ], 404);
        }

        $products = Product::where('category_id', $categoryID)->with('category')->get();
        return response()->json([
            'status' => true,
            'message' => 'Products in category',
            'data' => $products
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
{
    $data = $request->validate([
        'type' => 'required|string|max:255',
        'category_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Handle image upload
    if ($request->hasFile('category_image')) {
        // Delete the old image if it exists
        if ($category->category_image) {
            Storage::disk('public')->delete($category->category_image);
        }
        // Store the new image
        $data['category_image'] = $request->file('category_image')->store('product_image', 'public');
    }

    // Update the category with the validated data
    $category->update($data);

    // Add full_banner_url to the response
    $category->full_banner_url = $category->category_image 
        ? asset('storage/' . $category->category_image)
        : null;

    return response()->json([
        'status' => true,
        'message' => 'Category updated successfully',
        'data' => $category
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Category $category)
{
    try {
        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Category and associated products deleted successfully'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to delete category: ' . $e->getMessage()
        ], 500);
    }
}
}
