<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use App\Models\section;
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections =section::all();
        // $products = Product::with('section')->get();
        $products = product::all();
        return view('products.products',compact('sections' ,'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                // التحقق من صحة المدخلات
                $request->validate([
                    'Product_name' => 'required|string|max:255|unique:products,Product_name',
                    'section_id' => 'required',
                    'description' => 'nullable|string|max:1000',
                ], [
                    'Product_name.required' => 'يرجي إدخال إسم المنتج',
                    'Product_name.unique' => 'إسم المنتج مسجل مسبقا',
                    'description.required' => 'يرجي إدخال الوصــف',
                    'section_id.required' => 'يرجي إدخال المنتج',
                ]);

                // التحقق مما إذا كان المنتج موجودًا مسبقًا
                if (Product::where('Product_name', $request->Product_name)->exists()) {
                    // تخزين رسالة في الجلسة
                    session()->flash('Error', 'خطأ: المنتج موجود مسبقا....');
                } else {
                    Product::create([
                        'Product_name' => $request->Product_name, // Corrected field
                        'description' => $request->description,
                        'section_id' => $request->section_id,
                    ]);
                    session()->flash('Add', 'تم إضافة المنتج بنجــاح.....');
                }

                return redirect('/products');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request;
            $id = section::where('section_name' , $request->section_name)->first()->id;
             $product = Product::findOrFail($request->pro_id);
            $product ->update([
                'product_name' => $request -> product_name,
                'description'  => $request -> description ,
                'section_id'   => $id,
            ]);

            session() -> flash('Add' , 'تم التعديل بنجاح ');
            return redirect('/products');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      // return $request;
       $id = $request->pro_id;
       $product = Product::findorfail($id);
       if (!$product) {
        return redirect('/products')->with('error', 'المنتج غير موجود.');
    }else{
        $product->delete();
        session() -> flash('Add' , 'تم الحذف بنجاح ');
        return redirect('/products');
    }
    }
}