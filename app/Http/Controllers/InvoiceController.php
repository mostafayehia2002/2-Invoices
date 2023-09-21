<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices=Invoice::all();
        //
        return view('invoices.show_invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sections=Section::all();
        return view('invoices.add_invoices',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function  getProducts($id)
    {
        $products = Product::where('section_id', $id)->pluck('product_name', 'id');

        return response()->json($products);

    }
    public function store(Request $request)
    {
         $request->validate([
             'Invoice_number'=>['required','unique:invoices,invoice_number'],
             'Due_date'=>['required'],
             'Section'=>['required'],
             'Amount_collection'=>['required'],
             'Amount_Commission'=>['required'],
             'Rate_VAT'=>['required'],
             'Pic'=>['required']
         ],[
             'Invoice_number.required'=>'يرجي ادخال رقم الفاتورة',
             'Invoice_number.unique'=>'رقم الفاتورة موجوده مسبقا',
             'Due_date.required'=>'يرجي ادخال تاريخ الاستحقاق',
             'Section.required'=>'يرجي اختيار القسم',
             'Amount_collection.required'=>'يرجي ادخال مبلغ التحصيل',
             'Amount_Commission.required'=>'يرجي ادخال مبلغ العموله',
             'Rate_VAT.required'=>'يرجي اختيار قيمه الضريبة المضافة',
             'Pic.required'=>'يرجي ارسال مرفقات الفاتوره'
         ]);
        Invoice::create([
         'invoice_number' =>$request->Invoice_number,
         'invoice_date' =>$request->Invoice_Date,
            'due_date' =>$request->Due_date,
            'product' =>$request->Product,
            'section_id'=>$request->Section,
          'amount_collection'=>$request->Amount_collection,
          'amount_commission'=>$request->Amount_Commission,
            'discount'=>$request->Discount,
            'rate_vat'=>$request->Rate_VAT,
            'value_vat'=>$request->Value_VAT,
            'total'=>$request->Total,
            'note'=>$request->Note,
            'created_by'=>Auth::user()->name,

        ]);

             $invoice_id=Invoice::latest()->first()->id;
               invoices_details::create([
               'invoice_id'=>$invoice_id,
            'invoice_number'=>$request->Invoice_number,
            'product'=>$request->Product,
            'section'=>$request->Section,
            'note'=>$request->Note,
            'created_by'=>Auth::user()->name,
        ]);
        if($request->hasFile('Pic')){
            $fileName=time().''.$request->file('Pic')->getClientOriginalName();
           $request->file('Pic')->storeAs($request->Invoice_number,$fileName,'invoices');

            invoices_attachments::create([

                'file_name'=>$fileName,
                'invoice_number'=>$request->Invoice_number,
                'invoice_id'=> $invoice_id,
                'created_by'=>Auth::user()->name,
            ]);
        }

        return  redirect('/invoices')->with('success-add-invoice','تم اضافه الفاتوره بنجاح');
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
     $data=Invoice::find($id)->first();
     $sections=Section::all();
      return  view('invoices.update_invoices',compact('data','sections'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'Invoice_number'=>['required','unique:invoices,invoice_number,'.$id],
            'Due_date'=>['required'],
            'Section'=>['required'],
            'Amount_collection'=>['required'],
            'Amount_Commission'=>['required'],
            'Rate_VAT'=>['required'],
        ],[
            'Invoice_number.required'=>'يرجي ادخال رقم الفاتورة',
            'Invoice_number.unique'=>'رقم الفاتورة موجوده مسبقا',
            'Due_date.required'=>'يرجي ادخال تاريخ الاستحقاق',
            'Section.required'=>'يرجي اختيار القسم',
            'Amount_collection.required'=>'يرجي ادخال مبلغ التحصيل',
            'Amount_Commission.required'=>'يرجي ادخال مبلغ العموله',
            'Rate_VAT.required'=>'يرجي اختيار قيمه الضريبة المضافة',
        ]);

        $old = $request->old_invoice_number;
        $new = $request->Invoice_number;
      Storage::disk('invoices')->move("$old","$new");
        Invoice::where('id',$id)->update([
         'invoice_number' =>$request->Invoice_number,
         'invoice_date' =>$request->Invoice_Date,
         'due_date' =>$request->Due_date,
         'product' =>$request->Product,
         'section_id'=>$request->Section,
         'amount_collection'=>$request->Amount_collection,
         'amount_commission'=>$request->Amount_Commission,
         'discount'=>$request->Discount,
         'rate_vat'=>$request->Rate_VAT,
         'value_vat'=>$request->Value_VAT,
         'total'=>$request->Total,
         'note'=>$request->Note,
         'created_by'=>Auth::user()->name,
     ]);

        return  redirect('/invoices')->with('success-update-invoice','تم تعديل الفاتورة بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     */

    //archive invoice
    public function archive(Request $r)
    {
         Invoice::find($r->id)->delete();
       return redirect()->back()->with('success-delete-invoice','تم ارشفة الفاتوة بنجاح');

    }

    public function delete(Request $r)
    {
        Invoice::find($r->id)->forceDelete();
        Storage::disk('invoices')->deleteDirectory($r->invoice_number);
        return redirect()->back()->with('success-delete-invoice','تم حذف الفاتوة بنجاح');

    }
}
