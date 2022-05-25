@extends('admin.main')
@section('title')
Product Extras
@endsection
@section('page_title')
Product Extras
@endsection
@section('page_css')
<link rel="stylesheet" href="{{asset('assets/plugins/sortable/sortable.css')}}">
<link rel="stylesheet" href="{{asset('css/productGroup.css')}}">
@endsection
@section('page_btn')
<a href="javascript:;" style="margin-right: 20px;" id="product_group_sort_model_open_btn" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-sort"></i>
        <span>Sort</span>
    </span>
</a>

<a href="#new_extra_group_add_modal" data-toggle="modal" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-plus"></i>
        <span>Extra</span>
    </span>
</a>
@endsection
@section('content')
<input type="hidden" id="current_product_id" value="{{$product->id}}">
<div class="m-portlet m-portlet--success m-portlet--head-solid-bg">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Product - {{$product->pro_name}}
				</h3>
			</div>
		</div>
	</div>
	<div class="m-portlet__body">
        <table class="table table-bordered m-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 150px;"> <img src="{{asset('uploads/products/'.$product->pro_image)}}" style="width: 100%;" alt=""> </td>
                    <td>{{$product->pro_name}}</td>
                    <td>{{$product->pro_description}}</td>
                    <td>{{$product->pro_price}}</td>
                </tr>
            </tbody>
        </table>
	</div>
    <div class="m-portlet__head thin">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Product Extra Groups
				</h3>
			</div>
		</div>
	</div>
    <div class="m-portlet__body">
        <div id="m_product_group_datatable"></div>
    </div>
</div>

<div class="modal fade m-custom-modal" id="new_extra_group_add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Add new Extra Group
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <form id="new_extra_group_add_form" action="{{route('admin.products.groupExtra.insert')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group m-form__group">
                                <label for="admin_name">
                                    Product
                                </label>
                                <input type="text" class="form-control m-input m-input--air" value="{{$product->pro_name}}" placeholder="Product Name" readonly>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="admin_name">
                                    Group Name
                                </label>
                                <input type="text" class="form-control m-input m-input--air" id="group_name" name="group_name" placeholder="Group Name" required>
                            </div>
                            <div class="form-group m-form__group" style="margin-bottom: 0;">
                                <label for="admin_name">
                                    Type
                                </label>
                                <select class="form-control m-bootstrap-select m-input--air m_selectpicker" name="group_type">
        							<option value="single">Single Select</option>
        							<option value="multi">Multi Select</option>
        						</select>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group m-form__group" style="margin-bottom: 0;">
                                <label for="admin_name">
                                    Extras
                                </label>
                                <div class="extraTable-container">
                                    <table class="table table-bordered m-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($extras as $extra)
                                                <tr>
                                                    <th scope="row" style="width: 42px;">
                                                        <span style="position: relative; width: 30px;">
                                                            <label class="m-checkbox m-checkbox--solid m-checkbox--success m-checkbox-single">
            													<input type="checkbox" class="product_extra_select_tag" name="extraIds[]" value="{{$extra->id}}">
            													<span></span>
            												</label>
                                                        </span>
                                                    </th>
                                                    <td>{{$extra->extra_name}}</td>
                                                    <td>{{$extra->extra_price}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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

<div class="modal fade m-custom-modal" id="exist_extra_group_edit_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Add new Extra Group
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <form id="exist_extra_group_edit_form" action="{{route('admin.products.groupExtra.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="modal-body">
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

<div class="modal fade m-custom-modal" id="group_extras_view_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="groupname"></span> - Extras
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered m-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody id="group_extras_view_tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--air" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade m-custom-modal" id="group_extras_sort_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Product Group Sort
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <form id="group_extras_sort_form" action="{{route('admin.categories.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="modal-body">
                    <div data-force="30" class="layer block">
                        <ul id="group_extras_sort_ul" class="block__list block__list_words">
                        </ul>
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

<div class="modal fade m-custom-modal" id="single_group_extras_sort_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="sortgroupname"></span> - Extras Sort
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <form id="single_group_extras_sort_form" action="{{route('admin.categories.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="modal-body">
                    <div data-force="30" class="layer block">
                        <ul id="single_group_extras_sort_ul" class="block__list block__list_words">
                        </ul>
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
    <script src="{{asset('assets/plugins/sortable/sortable.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/mProductGroups.js')}}" type="text/javascript"></script>
@endsection
