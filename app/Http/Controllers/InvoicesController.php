<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Models\invoices_details;
use App\Models\invoice_attachment;
use Illuminate\Http\Request;
use App\Models\section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = section::all();
    return view('invoices.add_invoice' , compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        // {
        //     "_token": "vZbr20BhQl13s9NQwZIIy7PrMubb2g2gBtgbkTjL",
        //     "invoice_number": "1121",
        //     "invoice_Date": "2024-12-29",
        //     "Due_date": "2024-12-29",
        //     "Section": "1",
        //     "product": "العقود الأجلة",
        //     "Amount_collection": "50000",
        //     "Amount_Commission": "15000",
        //     "Discount": "1000",
        //     "Rate_VAT": "5%",
        //     "Value_VAT": "700.00",
        //     "Total": "14700.00",
        //     "note": "ييبيبيبيبي",
        //     "pic": {}
        // }
        $invoice = invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' =>'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
        ]);
         $invoice_id = invoice::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
            'user' => Auth()->user()->name,
        ]);

        if($request->hasFile('pic')) {
            $this->validate($request, ['pic' => 'required|mimes:pdf,jpeg,png,jpg|max:10000'],['pic.mimes' => 'تم حفظ الفاتورة ولم يتم حظ المرفق يرجى اعادة المحاولة']);
            $invoice_id = invoice::latest()->first()->id;  // الحصول على آخر معرف فاتورة
            $image = $request->file('pic');  // الحصول على الملف المرفق
            $file_name = $image->getClientOriginalName();  // الحصول على اسم الملف الأصلي
            $invoice_number = $request->invoice_number;  // رقم الفاتورة

            $attachments = new invoice_attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number; // رقم الفاتورة
            $attachments->Created_by = Auth::user()->name;  // اسم المستخدم الحالي
            $attachments->invoice_id = $invoice_id;  // معرف الفاتورة
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
    public function getproducts($id){
        $products = DB::table('products')->where("section_id" ,$id)->pluck('Product_name' ,"id");
        return  json_encode($products);

   }
}