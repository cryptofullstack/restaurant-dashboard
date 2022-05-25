@extends('admin.main')
@section('title')
Extras
@endsection
@section('page_title')
Extras
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/slim/slim.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('css/cupon.css')}}"> --}}
@endsection
@section('page_btn')
<a href="#new_extra_add_modal" data-toggle="modal" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-plus"></i>
        <span>Extra</span>
    </span>
</a>
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <i class="la la-gear"></i> Extra Management
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row align-items-center">
                <div class="col-xl-8 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Search..." id="generalSearch">
                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                    <span>
                                        <i class="la la-search"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--begin: Datatable -->
            <div id="m_extras_datatable"></div>
            <!--end: Datatable -->
        </div>
    </div>

    <div class="modal fade m-custom-modal" id="new_extra_add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Add new Extra
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="new_extra_add_form" action="{{route('admin.extras.insert')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Title
                            </label>
                            <input type="text" class="form-control m-input m-input--air" id="extra_name" name="extra_name" placeholder="Extra Name" required>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Price
                            </label>
                            <input type="text" class="form-control m-input m-input--air" id="extra_price" name="extra_price" placeholder="Extra Price" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--air" data-dismiss="modal">
    						Close
    					</button>
                        <button type="submit" class="btn btn-outline-accent m-btn m-btn--custom m-btn--air form-submit-btn">
    						Submit
    					</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade m-custom-modal" id="exist_extra_edit_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Update Extra
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="exist_extra_edit_form" action="{{route('admin.extras.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input type="hidden" id="edit_extra_id" name="extra_id" value="">
                    <div class="modal-body">
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Title
                            </label>
                            <input type="text" class="form-control m-input m-input--air" id="_extra_name" name="_extra_name" placeholder="Extra Name" required>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Price
                            </label>
                            <input type="text" class="form-control m-input m-input--air" id="_extra_price" name="_extra_price" placeholder="Extra Price" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--air" data-dismiss="modal">
    						Close
    					</button>
                        <button type="submit" class="btn btn-outline-accent m-btn m-btn--custom m-btn--air form-submit-btn">
    						Update
    					</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_js')
    <script src="{{asset('assets/plugins/slim/slim.kickstart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/mExtras.js')}}" type="text/javascript"></script>
@endsection
