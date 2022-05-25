@extends('admin.main')
@section('title')
Category Products
@endsection
@section('page_title')
Category Products
@endsection
@section('page_css')
<link rel="stylesheet" href="{{asset('assets/plugins/sortable/sortable.css')}}">
{{-- <link rel="stylesheet" href="{{asset('css/productGroup.css')}}"> --}}
@endsection
@section('page_btn')
<a href="javascript:;" style="margin-right: 20px;" id="category_product_sort_model_open_btn" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-sort"></i>
        <span>Sort</span>
    </span>
</a>

<a href="javascript:;" id="category_product_add_modal_open_btn" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-plus"></i>
        <span>Products</span>
    </span>
</a>
@endsection
@section('content')
<input type="hidden" id="current_category_id" value="{{$category->id}}">
<div class="m-portlet m-portlet--success m-portlet--head-solid-bg">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Category - {{$category->title}}
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
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 150px;"> <img src="{{asset('uploads/category/'.$category->image)}}" style="width: 100%;" alt=""> </td>
                    <td>{{$category->title}}</td>
                    <td>{{$category->description}}</td>
                </tr>
            </tbody>
        </table>
	</div>
    <div class="m-portlet__head thin">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Category Products
				</h3>
			</div>
		</div>
	</div>
    <div class="m-portlet__body">
        <div id="m_category_products_datatable"></div>
    </div>
</div>

<div class="modal fade m-custom-modal" id="new_category_product_add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Add Products
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <form id="new_category_product_add_form" action="{{route('admin.categories.products.insert')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$category->id}}">
                <div class="modal-body">
                    <div class="m-product-table-container">
                        <table class="table table-bordered m-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody id="category_unassign_products">
                            </tbody>
                        </table>
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

<div class="modal fade m-custom-modal" id="category_product_sort_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
            <form id="category_product_sort_form" action="{{route('admin.categories.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="modal-body">
                    <div data-force="30" class="layer block">
                        <ul id="category_product_sort_ul" class="block__list block__list_words">
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
    <script src="{{asset('js/mCategoryProduct.js')}}" type="text/javascript"></script>
@endsection
