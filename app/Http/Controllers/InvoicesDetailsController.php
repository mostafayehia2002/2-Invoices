<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
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

           'file_name'=>['required','mimes:pdf,jpg,png,jpeg'],
        ],[
            'file_name.required' =>'يجب ارفاق ملف',
            'file_name.mimes' =>'  يجب ارفاق الملف بالصيغه المطلوبة',
    ]);
       $file_name=time().''. $request->file('file_name')->getClientOriginalName();
        $request->file('file_name')->storeAs("$request->invoice_number/",$file_name,'invoices');
        invoices_attachments::create([
            'file_name'=>$file_name,
            'invoice_number'=>$request->invoice_number,
            'invoice_id'=>$request->invoice_id,
            'created_by'=>Auth::user()->name,
        ]);

        return redirect()->back()->with('add-attachment','تم اضافة المرفق بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       $invoices= Invoice::with(['details','attachment'])->where('id',$id)->first();

        return view('invoices.details_invoice',compact('invoices'));
    }

    public function download($invoice_number, $file_name)
    {

       $file="./Attachment/$invoice_number/$file_name";

        return response()->download($file);

    }

public  function  deleteFile(Request $r){
     $file=invoices_attachments::find($r->id_file);
     $file->delete();
       Storage::disk('invoices')->delete("$r->invoice_number/$r->file_name");
        return redirect()->back()->with('delete-attachment','تم حذف المرفق بنجاح');
}
    /**
     *
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_details $invoices_details)
    {
        //


    }
}
