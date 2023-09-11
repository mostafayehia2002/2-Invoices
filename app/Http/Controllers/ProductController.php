<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sections=Section::all();
        $products=Product::with('section')->get();
        return view('products.show_products',compact('sections','products'));
    }

    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

                    'product_name'=>['required','unique:products,product_name'],
                     'section_id'=>['required'],
                  ],[
                    'product_name.required'=>'هذا الحقل مطلوب',
                    'product_name.unique'=>'هذالمنتج موجود مسبقا',
                      'section_id'=>'هذا الحقل مطلوب'
                ]);
                Product::create([
                  'product_name'=>$request->product_name,
                    'section_id'=>$request->section_id,
                    'description'=>$request->description,
                ]);

                return redirect()->back()->with('success-add-product','تم اضافه المنتج بنجاح');

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
