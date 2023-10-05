<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function index(){
        return view('reports.invoices_report');
    }
    public function searchInvoice(Request $r){
        $chose=$r->rdio;
        $type=$r->type;
        $start_at=$r->start_at;
        $end_at=$r->end_at;
        $invoice_number=$r->invoice_number;
        if($chose==1){
            if($chose && $start_at=='' && $end_at==''){
                $data=  Invoice::where('value_status',$type)->get();
                return  view('reports.invoices_report',compact('data','type'));
            }
            else{

                $end_at=$r->end_at==''?date('Y-m-d'):$r->end_at;
                $data=  Invoice::withTrashed()->where('value_status',$type)->whereBetween('invoice_date',[$start_at,$end_at])->get();
                return  view('reports.invoices_report',compact('data','type','start_at','end_at'));
            }

        }else{
            $data=  Invoice::where('invoice_number',$invoice_number)->get();

            return  view('reports.invoices_report',compact('data','invoice_number','chose'));

        }

    }


    public function showCustomer(){

        $sections=Section::all();
        return view('reports.customers_report',compact('sections'));
    }

    public  function searchCustomer(Request $r){
        $section_id=$r->section;
        $product=$r->product;
        $start_at=$r->start_at;
        $end_at=$r->end_at;
        $sections=Section::all();
        if($section_id&& $start_at==''&& $end_at==''){

            $data=  Invoice::where('section_id', $section_id)->where('product',$product)->get();
            return  view('reports.customers_report',compact('data','sections','start_at','end_at','section_id','product'));
        }else{
            $end_at=$r->end_at==''?date('Y-m-d'):$r->end_at;
            $data=  Invoice::withTrashed()->where('section_id',$section_id)->where('product',$product)->whereBetween('invoice_date',[$start_at,$end_at])->get();
            return  view('reports.customers_report',compact('data','sections','start_at','end_at','section_id','product'));
        }


    }
}
