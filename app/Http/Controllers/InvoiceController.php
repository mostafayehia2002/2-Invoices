<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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

        //sending mail to admin during add new invoice
        $user=User::first();
        Notification::send($user,new AddInvoice($invoice_id));

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



   public function showStatus($id){
    $data=Invoice::find($id)->first();
return  view('invoices.change_payment_status',compact('data'));
}
public function updateStatus(Request $request){

    $request->validate([
        'Payment_Status'=>['required'],

    ],[
        'Payment_Status.required'=>'يرجي ادخال حالة الفاتورة',
        ]);
    Invoice::where('id',$request->Invoice_id)->update([
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
        'value_status'=>$request->Payment_Status,
        'status'=>$request->Payment_Status==='1'? ' مدفوعة':'مدفوعة جزئيا',
        'created_by'=>Auth::user()->name,
    ]);

    invoices_details::create([
        'invoice_id'=>$request->Invoice_id,
        'invoice_number'=>$request->Invoice_number,
        'product'=>$request->Product,
        'section'=>$request->Section,
        'value_status'=>$request->Payment_Status,
        'status'=>$request->Payment_Status==='1'? ' مدفوعة':'مدفوعة جزئيا',
        'payment_date'=>$request->Payment_Date,
        'created_by'=>Auth::user()->name,
    ]);
    return  redirect('/invoices')->with('success-update-invoice','تم تعديل حالة الفاتورة بنجاح');
}
    public function invoicePaid()
    {
        $invoices=Invoice::where('value_status',1)->get();
        //
        return view('invoices.show_invoices_paid',compact('invoices'));
    }
    public function invoiceUnpaid()
    {
        $invoices=Invoice::where('value_status',0)->get();
        //
        return view('invoices.show_invoices_unpaid',compact('invoices'));
    }
    public function invoicePartiallyPaid()
    {
        $invoices=Invoice::where('value_status',2)->get();
        //
        return view('invoices.show_invoices_partially',compact('invoices'));
    }

    public function invoiceArchive()
    {
        $invoices=Invoice::onlyTrashed()->get();
        //
        return view('invoices.show_invoices_archive',compact('invoices'));
    }
    public function delete(Request $r)
    {
        Invoice::withTrashed()->find($r->id)->forceDelete();
        Storage::disk('invoices')->deleteDirectory($r->invoice_number);
        return redirect()->back()->with('success-delete-invoice','تم حذف الفاتوة بنجاح');


    }
    public function restoreInvoice($id)
    {
        $invoices=Invoice::onlyTrashed()->where('id',$id)->first()->restore();

        return redirect()->back()->with('success-restore-invoice','تم استرجاع الفاتورة بنجاح');
    }
    public function  printInvoice($id){
        $invoices=Invoice::findOrFail($id)->first();

        return view('invoices.print_invoice',compact('invoices'));
    }


}

