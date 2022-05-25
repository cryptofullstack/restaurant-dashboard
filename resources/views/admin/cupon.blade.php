@extends('admin.main')
@section('title')
Cupons
@endsection
@section('page_title')
Cupons
@endsection
@section('page_css')
<link rel="stylesheet" href="{{asset('css/cupon.css')}}">
@endsection
@section('page_btn')
<a href="#user_cupon_add_modal" data-toggle="modal" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-plus"></i>
        <span>Cupon</span>
    </span>
</a>
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <i class="la la-gear"></i> Cupon Management
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
            <div id="m_cupons_datatable"></div>
            <!--end: Datatable -->
        </div>
    </div>

    <div class="modal fade m-custom-modal" id="user_cupon_add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Add new cupon
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="user_cupon_add_form" action="{{route('admin.cupons.insert')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Cupon Name
                            </label>
                            <input type="text" class="form-control m-input m-input--air" id="cupon_name" name="cupon_name" placeholder="Cupon Name" required>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Cupon Percentage
                            </label>
                            <div class="m-input-icon m-input-icon--right m-input-air">
                                <input type="text" class="form-control m-input m-input--air" id="cupon_percent" name="cupon_percent" placeholder="Cupon percent" required>
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span>
                                        <i class="la" >%</i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="admin_name">
                                Cupon Users
                            </label>
                            <div id="m_cupon_users_datatable"></div>
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
@endsection
@section('page_js')
    <script src="{{asset('js/mCupons.js')}}" type="text/javascript"></script>
@endsection
