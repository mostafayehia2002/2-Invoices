@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    تعديل الفاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تعديل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>

        @endforeach
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('invoices.update',$data->id)}}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @method('PUT')
                        {{ csrf_field() }}
                        {{-- 1 --}}
                        <div class="row">
                            <input type="hidden" name="old_invoice_number" value="{{$data->invoice_number}}" readonly>
                            <div class="col">
                                <label for="Invoice_number" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="Invoice_number" name="Invoice_number"
                                    title="يرجي ادخال رقم الفاتورة" value="{{$data->invoice_number}}">
                            </div>

                            <div class="col">
                                <label>تاريخ الفاتورة</label>
                                <input class="form-control fc-datepicker" name="Invoice_Date" placeholder="YYYY-MM-DD"
                                    type="text" value="{{$data->invoice_date}}">
                            </div>

                            <div class="col">
                                <label>تاريخ الاستحقاق</label>
                                <input class="form-control fc-datepicker" name="Due_date" placeholder="YYYY-MM-DD"
                                    type="text" value="{{$data->due_date}}">
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row">
                            <div class="col">
                                <label for="Section" class="control-label">القسم</label>
                                <select name="Section" class="form-control SlectBox" id="Section">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد القسم</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"  {{$data->section_id==$section->id? 'selected':''}} >{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="product" class="control-label">المنتج</label>
                                <select id="product" name="Product" class="form-control">
                                 <option value="{{$data->product}}">{{$data->product}}</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="Amount_Collection" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="Amount_Collection" name="Amount_collection" value="{{$data->amount_collection}}">
                            </div>
                        </div>

                        {{-- 3 --}}

                        <div class="row">

                            <div class="col">
                                <label for="Amount_Commission" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                    name="Amount_Commission" title="يرجي ادخال مبلغ العمولة " value="{{$data->amount_commission}}">
                            </div>

                            <div class="col">
                                <label for="Discount" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="Discount" name="Discount"
                                    title="يرجي ادخال مبلغ الخصم " value="{{$data->discount? $data->discount :0}}" >

                            </div>

                            <div class="col">
                                <label for="Rate_VAT" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select name="Rate_VAT" id="Rate_VAT" class="form-control" onchange="myFunction()" value=" ">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد نسبة الضريبة</option>
                                    <option value="5" {{$data->rate_vat==5? 'selected':''}}>5%</option>
                                    <option value="10" {{$data->rate_vat==10? 'selected':''}}>10%</option>
                                    <option value="15"{{$data->rate_vat==15? 'selected':''}}>15%</option>
                                    <option value="20"{{$data->rate_vat==20? 'selected':''}}>20%</option>
                                </select>
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row">
                            <div class="col">
                                <label for="Value_VAT" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="Value_VAT" name="Value_VAT" readonly value="{{$data->value_vat}}">
                            </div>

                            <div class="col">
                                <label for="Total" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="Total" name="Total" readonly value="{{$data->total}}">
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="Note">ملاحظات</label>
                                <textarea class="form-control" id="Note" name="Note" rows="3" value="{{$data->note}} "></textarea>
                            </div>
                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>

                    </form>
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
            },5000) ;
        });
    </script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>

        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-cc-cc'
        }).val();


    </script>

    <script>


        $('select[name="Section"]').on('change', function() {
            let SectionId = $(this).val();
            if (SectionId) {
                $.ajax({
                    url: "{{ URL::to('section') }}/"+SectionId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="Product"]').empty();
                        $.each(data, function(key, value) {


                            $('select[name="Product"]').append('<option value="' +
                                value + ' " >' + value + '</option>');
                        });
                    },


                });

            } else {
                console.log('AJAX load did not work');
            }
        });



    </script>

    <script>
        function myFunction() {

            let Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
            let Discount = parseFloat(document.getElementById("Discount").value);
            let Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value);
            let Value_VAT = parseFloat(document.getElementById("Value_VAT").value);

            let Amount_Commission2 = Amount_Commission - Discount;


            if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {

                alert('يرجي ادخال مبلغ العمولة ');

            } else {
                let intResults = Amount_Commission2 * Rate_VAT / 100;

                let intResults2 = parseFloat(intResults + Amount_Commission2);

                sumq = parseFloat(intResults).toFixed(2);

                sumt = parseFloat(intResults2).toFixed(2);

                document.getElementById("Value_VAT").value = sumq;

                document.getElementById("Total").value = sumt;

            }

        }

    </script>


@endsection
