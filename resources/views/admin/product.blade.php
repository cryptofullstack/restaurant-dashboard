@extends('admin.main')
@section('title')
Products
@endsection
@section('page_title')
Products
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/slim/slim.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('css/cupon.css')}}"> --}}
@endsection
@section('page_btn')
<a href="#new_product_add_modal" data-toggle="modal" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-plus"></i>
        <span>Product</span>
    </span>
</a>
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <i class="la la-gear"></i> Product Management
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
            <div id="m_products_datatable"></div>
            <!--end: Datatable -->
        </div>
    </div>

    <div class="modal fade m-custom-modal" id="new_product_add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Add new Product
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="new_product_add_form" action="{{route('admin.products.insert')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div id="product_image_slim">
                                    <input type="file" name="slim[]" required/>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        Title
                                    </label>
                                    <input type="text" class="form-control m-input m-input--air" id="pro_name" name="pro_name" placeholder="Product Name" required>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        Price
                                    </label>
                                    <input type="text" class="form-control m-input m-input--air" id="pro_price" name="pro_price" placeholder="Product Price" required>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        Description
                                    </label>
                                    <textarea class="form-control m-input m-input--air" name="pro_description" placeholder="Description" rows="5" required></textarea>
                                </div>
                            </div>
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

    <div class="modal fade m-custom-modal" id="exist_product_edit_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Add new Category
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="exist_product_edit_form" action="{{route('admin.products.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input type="hidden" id="edit_product_id" name="product_id" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div id="edit_product_image_slim">
                                    <img src="" alt="">
                                    <input type="file" name="slim[]"/>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        Title
                                    </label>
                                    <input type="text" class="form-control m-input m-input--air" id="_pro_name" name="_pro_name" placeholder="Product Name" required>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        Price
                                    </label>
                                    <input type="text" class="form-control m-input m-input--air" id="_pro_price" name="_pro_price" placeholder="Product Price" required>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        Description
                                    </label>
                                    <textarea class="form-control m-input m-input--air" name="_pro_description" id="_pro_description" placeholder="Description" rows="5" required></textarea>
                                </div>
                            </div>
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
    <script src="{{asset('js/mProducts.js')}}" type="text/javascript"></script>
@endsection
