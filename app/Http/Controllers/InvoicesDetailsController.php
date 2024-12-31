<?php

namespace App\Http\Controllers;

use App\Models\invoices_details;
use App\Models\invoice;
use App\Models\invoice_attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $invoices = invoice::where('id', $id)->first();
        $details = invoices_details::where('id_invoice', $id)->get();
        $attachments = invoice_attachment::where('invoice_id', $id)->get();
        return view('invoices.details_invoice', compact('invoices','details','attachments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $invoices = invoice_attachment::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();

    }
    public function open_file($invoice_number, $file_name)
    {
        $files = public_path() . '/Attachments/' . $invoice_number . '/' . $file_name;

        // تحقق من وجود الملف
        if (!file_exists($files)) {
            abort(404, 'File not found');
        }

        return Storage::file($files);
    }

    public function get_file($invoice_number, $file_name)
    {
        $files = public_path() . '/Attachments/' . $invoice_number . '/' . $file_name;

        // تحقق من وجود الملف
        if (!file_exists($files)) {
            abort(404, 'File not found');
        }

        return Storage::download($files);
    }

}