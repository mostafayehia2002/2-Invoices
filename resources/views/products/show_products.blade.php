@extends('layouts.master')
@section('title')
    المنتجات
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
                <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> المنتجات </span>
            </div>

        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    @if(session('success-add-product'))
        <div class="alert alert-success">{{ session()->get('success-add-product') }}</div>
    @endif
    @if(session('success-update-product'))
        <div class="alert alert-success">{{ session()->get('success-update-product') }}</div>
    @endif
    @if(session('success-delete-product'))
        <div class="alert alert-success">{{ session()->get('success-delete-product') }}</div>
    @endif
    @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
    @endif
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#AddModel">اضافة منتج</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">#</th>
                                <th class="wd-15p border-bottom-0">اسم المنتج </th>
                                <th class="wd-20p border-bottom-0">القسم </th>
                                <th class="wd-20p border-bottom-0">الملاحظات</th>
                                <th class="wd-15p border-bottom-0">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$product->product_name}}</td>
                                    <td>{{$product->section->section_name}}</td>
                                    <td>{{$product->description}}</td>
                                      <td>
                                          <a class="modal-effect btn btn-sm btn-info"
                                             data-effect="effect-scale"
                                              data-id="{{$product->id}}"
                                             data-product_name="{{$product->product_name}}"
                                             data-description="{{$product->description}}"
                                             data-section_name="{{$product->section->section_name}}"
                                             data-section_id="{{$product->section->id}}"
                                             data-toggle="modal"
                                             href="#EditModel"
                                             title="تعديل">
                                              <i class="las la-pen"></i>
                                          </a>

                                          <a class="modal-effect btn btn-sm btn-danger"
                                             data-effect="effect-scale"
                                             data-id="{{$product->id}}"
                                             data-product_name="{{$product->product_name}}"
                                             data-toggle="modal"
                                             href="#DeleteModel"
                                             title="حذف">
                                              <i class="las la-trash"></i>
                                          </a>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--   add section -->
        <div class="modal" id="AddModel">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">اضافة منتج</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{route('products.store')}}" method="POST">
                        @csrf
                    <div class="modal-body">
                     <div class="form-group">
                         <label for="product_name">اسم المنتج</label>
                         <input type="text" class="form-control" id="product_name" name="product_name">
                     </div>
                        <label for="section_id"> القسم</label>
                        <select  name="section_id" id="section_id" class="form-control">
                            <option value="" selected disabled >--- حدد القسم ---</option>
                            @foreach($sections as $section)
                                <option value="{{$section->id}}"> {{$section->section_name}}</option>
                            @endforeach
                        </select>
                        <div class="form-group">
                            <label for="description">ملاحظات</label>
                            <textarea class="form-control" id="description" name="description">

                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" type="submit">انشاء</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">اغلاق</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- end add section -->

        <!--  Edit section -->
        <div class="modal" id="EditModel">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">تعديل المنتج</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="products/update" method="POST">
                        {{method_field('PUT')}}
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <input  type="hidden" id="cases_id" name="id" value="">
                                <label for="product_name">اسم المنتج</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" value="">
                            </div>
                            <label for="section_id">القسم</label>
                                  <select  name="section_id" id="section_id" class="custom-select my-1 mr-sm-2">
                                    @foreach($sections as $section)
                                        <option value="{{$section->id}}">{{$section->section_name}}</option>
                                    @endforeach
                                   </select>
                            <div class="form-group">
                                <label for="description">ملاحظات</label>
                                <textarea class="form-control" id="description" name="description" value="">

                            </textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn ripple btn-primary" type="submit">تاكيد</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">اغلاق</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- end Edit section -->


        <!-- delete section -->
        <div class="modal" id="DeleteModel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="products/destroy" method="post">
                        {{method_field('Delete')}}
                     @csrf
                        <div class="modal-body">
                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                            <input type="hidden" name="id" id="id" value="">
                            <input class="form-control" name="section_name" id="section_name" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                    </form>
                 </div>
            </div>
        </div>
        <!--end  delete section -->

    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
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
    <script src="{{URL::asset('assets/js/modal.js')}}"></script>
    <script>
        let alert=document.querySelectorAll('.alert');
        alert.forEach((e)=>{
            setTimeout(function (){
                e.style.display='none';
            },3000) ;
        });
    </script>
    <script>
        $('#EditModel').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget)
            let id = button.data('id')
            let product_name = button.data('product_name')
            let description = button.data('description')
            let section_name=button.data('section_name')
            let section_id=button.data('section_id')
            let modal = $(this)
            modal.find('.modal-body #cases_id').val(id);
            modal.find('.modal-body #product_name').val(product_name);
            modal.find('.modal-body #section_name').val(section_id);
            modal.find('.modal-body #description').val(description);
        })
    </script>

    <script>
        $("#DeleteModel").on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var product_name = button.data('product_name')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #section_name').val(product_name);
        })

    </script>
@endsection
