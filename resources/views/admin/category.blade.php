@extends('admin.main')
@section('title')
Categories
@endsection
@section('page_title')
Categories
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/slim/slim.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sortable/sortable.css')}}">
{{-- <link rel="stylesheet" href="{{asset('css/cupon.css')}}"> --}}
@endsection
@section('page_btn')
<a href="javascript:;" style="margin-right: 20px;" id="category_sort_model_open_btn" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-sort"></i>
        <span>{{ __('category.sort') }}</span>
    </span>
</a>

<a href="#new_category_add_modal" data-toggle="modal" class="btn m-btn--air btn-accent m-btn m-btn--custom">
    <span>
        <i class="la la-plus"></i>
        <span>{{ __('category.title') }}</span>
    </span>
</a>
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <i class="la la-gear"></i> {{ __('category.category_management') }}
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
            <div id="m_categories_datatable"></div>
            <!--end: Datatable -->
        </div>
    </div>


    <!-- NEW CATEGORY -->
    <div class="modal fade m-custom-modal" id="new_category_add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('category.add_category_header') }}
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="new_category_add_form" action="{{route('admin.categories.insert')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div id="category_image_slim">
                                    <input type="file" name="slim[]" required/>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        {{ __('category.category_title') }}
                                    </label>
                                    <input type="text" class="form-control m-input m-input--air" id="cat_title" name="cat_title" placeholder="{{ __('category.category_name_placeholder') }}" required>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        {{ __('category.category_description') }}
                                    </label>
                                    <textarea class="form-control m-input m-input--air" name="cat_description" placeholder="{{ __('category.category_description_placeholder') }}" rows="10" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--air" data-dismiss="modal">
                            {{ __('category.close') }}
    					</button>
                        <button type="submit" class="btn btn-outline-accent m-btn m-btn--custom m-btn--air form-submit-btn">
                            {{ __('category.new_submit') }}
    					</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- UPDATE CATEGORY -->
    <div class="modal fade m-custom-modal" id="exist_category_edit_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('category.update_category_header') }}
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="exist_category_edit_form" action="{{route('admin.categories.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input type="hidden" id="edit_category_id" name="category_id" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div id="edit_category_image_slim">
                                    <img src="" alt="">
                                    <input type="file" name="slim[]"/>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                        {{ __('category.category_title') }}
                                    </label>
                                    <input type="text" class="form-control m-input m-input--air" id="_cat_title" name="_cat_title" placeholder="{{ __('category.category_name_placeholder') }}" required>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="admin_name">
                                    {{ __('category.category_description') }}
                                    </label>
                                    <textarea class="form-control m-input m-input--air" id="_cat_description" name="_cat_description" placeholder="{{ __('category.category_description_placeholder') }}" rows="10" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--air" data-dismiss="modal">
                            {{ __('category.close') }}
    					</button>
                        <button type="submit" class="btn btn-outline-accent m-btn m-btn--custom m-btn--air form-submit-btn">
    						{{ __('category.update_category') }}
    					</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade m-custom-modal" id="category_sortable_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Change Order
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="category_sortable_form" action="{{route('admin.categories.update')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div data-force="30" class="layer block">
                            <ul id="category_sortable_ul" class="block__list block__list_words">
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
    <script src="{{asset('assets/plugins/slim/slim.kickstart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/sortable/sortable.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/mCategory.js')}}" type="text/javascript"></script>
@endsection
