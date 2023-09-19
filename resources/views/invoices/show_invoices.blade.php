@extends('layouts.master')
@section('title')
    قائمة الفواتير
@endsection
@section('css')
    <!-- Internal Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">قائمه الفواتير</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if(session('success-add-invoice'))
        <div class="alert alert-success">{{ session()->get('success-add-invoice') }}</div>
    @endif
    @if(session('success-update-invoice'))
        <div class="alert alert-success">{{ session()->get('success-update-invoice') }}</div>
    @endif
    @if(session('success-delete-invoice'))
        <div class="alert alert-success">{{ session()->get('success-delete-invoice') }}</div>
    @endif
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a  href="{{url('invoices/create')}}" class="btn btn-outline-primary btn-block" data-effect="effect-scale" >اضافة فاتوره </a>
                        </div>
                     {{--                        --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0 table table-hover" id="example1">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">رقم الفاتورة</th>
                                <th scope="col">تاريخ الفاتورة</th>
                                <th scope="col">تاريخ الاستحقاق</th>
                                <th scope="col">المنتج</th>
                                <th scope="col">القسم</th>
                                <th scope="col">الاجمالي</th>
                                <th scope="col">الحاله</th>
                                <th scope="col">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices as $invoice)

                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>  <a href="{{route('invoiceDetails.edit',$invoice->id)}}"> {{$invoice->invoice_number}}</a></td>
                                <td>{{$invoice->invoice_date}}</td>
                                <td>{{$invoice->due_date}}</td>
                                <td>{{$invoice->product}}</td>
                                <td>{{$invoice->section->section_name}}</td>
                                <td>{{$invoice->total}}</td>
                                <td>
                                    @if($invoice->value_status==0)
                                        <span class="text-danger">{{$invoice->status}}</span>
                                    @elseif($invoice->value_status==1)
                                        <span class="text-success">{{$invoice->status}}</span>
                                    @else
                                        <span class="text-warning">{{$invoice->status}}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-warning" data-toggle="dropdown" type="button">العمليات<i class="fas fa-caret-down mr-1"></i></button>
                                        <div class="dropdown-menu tx-13">
                                            <a class="dropdown-item" href="{{route('invoices.edit',$invoice->id)}}">تعديل الفاتورة</a>
                                            <a class="dropdown-item active" href="{{route('deleteInvoice',[$invoice->id,$invoice->invoice_number])}}" onclick="return confirm('هل انت متاكد من حذف الفاتورة')">حذف الفاتورة</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script>
        let alert=document.querySelectorAll('.alert');
        alert.forEach((e)=>{
            setTimeout(function (){
                e.style.display='none';
            },3000) ;
        });
    </script>
    <!-- Internal Data tables -->
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal  Datatable js -->
    <script src="{{URL::asset('assets/js/table-data.js')}}"></script>
@endsection
