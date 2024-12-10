<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['brands'] = Brand::all();
        $data['sizes'] = Size::all();
        $data['colors'] = Color::all();
        $data['categories'] = Category::all();
        return view('products.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required | image | mimes:jpg,bmp,png',
        ]);

        $file = $request->file('image');

        if (isset($file)) {
            $imageName = strtolower(str_replace(' ', '_', $request->name)) . '.'
                . $file->getClientOriginalExtension();

            Storage::putFileAs(
                'products',
                $request->file('image'),
                $imageName
            );
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->color_id = $request->color_id;
        $product->size_id = $request->size_id;
        $product->image = $imageName;

        $product->save();

        $product->categories()->attach($request->categories);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $data['product']    = $product;
        $data['brands']     = Brand::all();
        $data['sizes']      = Size::all();
        $data['colors']     = Color::all();
        $data['categories'] = Category::all();
        return view('products.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'image | mimes:jpg,bmp,png',
        ]);

        $file = $request->file('image');

        if (isset($file)) {
            $imageName = strtolower(str_replace(' ', '_', $request->name)) . '.'
                . $file->getClientOriginalExtension();

            Storage::putFileAs(
                'products',
                $request->file('image'),
                $imageName
            );
        } else {
            $imageName = $product->image;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->color_id = $request->color_id;
        $product->size_id = $request->size_id;
        $product->image = $imageName;

        $product->save();

        $product->categories()->attach($request->categories);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back();
    }
}
