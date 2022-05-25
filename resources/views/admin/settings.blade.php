@extends('admin.main')
@section('title')
Settings
@endsection
@section('page_title')
Settings
@endsection
@section('page_css')
<link rel="stylesheet" href="{{asset('assets/plugins/slim/slim.min.css')}}">
<link rel="stylesheet" href="{{asset('css/setting.css')}}">
@endsection
@section('content')
    <div class="m-portlet m-portlet--full-height">
        <div class="m-portlet__body">
            <div class="listing-add-form-container">
                <div class="listing-form-sidebar-container">
                    <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active tab-passed" data-toggle="tab" href="#tabpanel1">Basic Detail</a>
                        <a class="list-group-item list-group-item-action tab-passed" data-toggle="tab" href="#tabpanel2">Location</a>
                        <a class="list-group-item list-group-item-action tab-passed" data-toggle="tab" href="#tabpanel3">Open Time</a>
                        <a class="list-group-item list-group-item-action tab-passed" data-toggle="tab" href="#tabpanel4">Main Image</a>
                        <a class="list-group-item list-group-item-action tab-passed" data-toggle="tab" href="#tabpanel5">Description</a>
                    </div>
                </div>
                <div class="listing-form-main-contents-container">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tabpanel1" role="tabpanel">
                            <form id="store_basic_detail_form" action="{{route('admin.settings.update.basic')}}" method="post">
                                <div class="form-group m-form__group row">
                                    <h5 class="col-xl-12 property-title-label">Store Name</h5>
                                    <div class="col-xl-9 col-lg-12">
                                        <input type="text" class="form-control m-input m-input--air" value="{{$setting->store_name}}" name="store_name" placeholder="Store Name">
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--md"></div>
                                <div class="form-group m-form__group row">
                                    <h5 class="col-xl-12 property-title-label">Business Name</h5>
                                    <div class="col-xl-9 col-lg-12">
                                        <input type="text" class="form-control m-input m-input--air" value="{{$setting->business_name}}" name="business_name" placeholder="Business name">
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--md"></div>
                                <div class="form-group m-form__group row">
                                    <h5 class="col-xl-12 property-title-label">Phone Number</h5>
                                    <div class="col-xl-9 col-lg-12">
                                        <input type="text" class="form-control m-input m-input--air" value="{{$setting->phone}}" name="phone" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--md"></div>
                                <div class="form-group m-form__group row">
                                    <h5 class="col-xl-12 property-title-label">Delivery Cost</h5>
                                    <div class="col-xl-9 col-lg-12">
                                        <input type="text" class="form-control m-input m-input--air" value="{{$setting->deliver_cost}}" name="deliver_cost" placeholder="Delivery Cost">
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>
                                <div class="form-group m-form__group">
                                    <button type="submit" class="submit-btn btn m-btn--air btn-accent m-btn m-btn--custom">Update</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tabpanel2" role="tabpanel">
                            <form id="store_location_form" action="{{route('admin.settings.update.location')}}" method="post">
                                <input type="hidden" name="workspace_id" value="">
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-9">
                                        <h5 class="property-title-label">Address:</h5>
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="hidden" name="latitude" id="store_latitude" value="{{$setting->latitude}}">
                                            <input type="hidden" name="longitude" id="store_longitude" value="{{$setting->longitude}}">
                                            <input type="text" class="form-control m-input m-input--air" name="store_address" value="{{$setting->address}}" id="pac-input" placeholder="Enter your address">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-map-marker"></i></span></span>
                                        </div>
                                        <span class="m-form__help">Write complete address and choose from the dropdown</span>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-9">
                                        <h5 class="property-title-label">Map Pin:</h5>
                                        <div id="map" style="width: 100%; height: 400px"></div>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>
                                <div class="form-group m-form__group">
                                    <button type="submit" class="submit-btn btn m-btn--air btn-accent m-btn m-btn--custom">Update</button>
                                </div>
                            </form>
                        </div>


                        
                        <!-- Times -->
                        <div class="tab-pane fade" id="tabpanel3" role="tabpanel">
                            <form id="store_open_time_form" action="{{route('admin.settings.update.time')}}" method="post">
                                <div class="row">
                                    <!-- Minimal waiting time -->
                                    <div class="col-lg-5 col-xl-4">
                                        <h5 class="property-title-label">Minimale wachttijd:</h5>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="minimal_delivery_time" value="{{$setting->minimal_delivery_time}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                                <!-- Monday -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Monday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input type="checkbox" @if($setting->mon_opened == 1) checked @endif name="mon_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="mon_open_set" @if ($setting->mon_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="mon_open" value="{{$setting->mon_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="mon_close" value="{{$setting->mon_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                                
                                <!-- Tuesday -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Tuesday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input type="checkbox" @if($setting->tue_opened == 1) checked @endif name="tue_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="tue_open_set" @if ($setting->tue_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="tue_open" value="{{$setting->tue_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="tue_close" value="{{$setting->tue_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                                <!-- Wednesday -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Wednesday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input type="checkbox" @if($setting->wed_opened == 1) checked @endif name="wed_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="wed_open_set" @if ($setting->wed_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="wed_open" value="{{$setting->wed_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="wed_close" value="{{$setting->wed_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                                <!-- Thursday -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Thursday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input type="checkbox" @if($setting->thu_opened == 1) checked @endif name="thu_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="thu_open_set" @if ($setting->thu_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="thu_open" value="{{$setting->thu_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="thu_close" value="{{$setting->thu_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                                <!-- Firday -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Friday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input type="checkbox" @if($setting->fri_opened == 1) checked @endif name="fri_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="fri_open_set" @if ($setting->fri_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="fri_open" value="{{$setting->fri_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="fri_close" value="{{$setting->fri_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="m-separator m-separator--dashed m-separator--sm"></div>'
                                
                                
                                <!-- SATURDAY -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Saturday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input type="checkbox" @if($setting->sat_opened == 1) checked @endif name="sat_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="sat_open_set" @if ($setting->sat_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="sat_open" value="{{$setting->sat_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="sat_close" value="{{$setting->sat_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                                <!-- Sunday -->
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12" style="display: flex;align-items: center;">
                                        <h5 class="property-title-label" style="margin-bottom: 5px;">Sunday:&nbsp;&nbsp;&nbsp;</h5>
                                        <span class="m-switch m-switch--icon" style="margin-top:0;">
                                            <label style="margin-bottom: 0;display: flex;align-items: center;cursor:pointer;">
                                                <input class="pull-right" type="checkbox" @if($setting->sun_opened == 1) checked @endif name="sun_opened">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row" id="sun_open_set" @if ($setting->sun_opened == 0) style="display: none" @endif>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Opening time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="sun_open" value="{{$setting->sun_open}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xl-4">
                                        <span class="property-title-label">Closing time:</span>
                                        <div class="form-group m-form__group row">
                                            <div class="col-12">
                                                <input type="text" class="form-control m-input m-input--air m-timepicker" name="sun_close" value="{{$setting->sun_close}}" placeholder="Select time" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="m-separator m-separator--dashed m-separator--sm"></div>

                            
                                <div class="form-group m-form__group">
                                    <button type="submit" class="submit-btn btn m-btn--air btn-accent m-btn m-btn--custom">Update</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tabpanel4" role="tabpanel">
                            <form id="store_main_img_form" action="{{route('admin.settings.update.mainimg')}}" method="post">
                                <div class="row">
                                    <div class="col-md-10 col-lg-8">
                                        <div class="main-img-container">
                                            <input type="hidden" name="store_main_image" value="{{$setting->home_header}}">
                                            <img src="{{asset('uploads/store/'.$setting->home_header)}}" id="store_main_header_img" alt="">
                                            <a href="#store_main_slim_image_modal" data-toggle="modal" class="image-change-btn btn m-btn--air btn-accent m-btn m-btn--custom">Change</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>
                                <div class="form-group m-form__group">
                                    <button type="submit" class="submit-btn btn m-btn--air btn-accent m-btn m-btn--custom">Update</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tabpanel5" role="tabpanel">
                            <form id="store_description_form" action="{{route('admin.settings.update.description')}}" method="post">
                                <div class="form-group m-form__group row" style="min-height: 166px;">
                                    <div class="col-lg-8">
                                        <textarea class="form-control m-input m-input--air property-description-text-input normall" name="store_description" rows="6">{{$setting->description}}</textarea>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m-separator--sm"></div>
                                <div class="form-group m-form__group">
                                    <button type="submit" class="submit-btn btn m-btn--air btn-accent m-btn m-btn--custom">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade m-custom-modal" id="store_main_slim_image_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
    					Choose Image
    				</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form id="store_main_slim_image_form" action="{{route('admin.settings.upload.main')}}" role="form" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="modal-body" style="padding: 10px;">
                        <div id="store_main_slim_image_slim">
                            <input type="file" name="slim[]" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--air" data-dismiss="modal">
    						Close
    					</button>
                        <button type="submit" class="btn btn-outline-accent m-btn m-btn--custom m-btn--air form-submit-btn">
    						Upload
    					</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_js')
<script src="{{asset('assets/plugins/slim/slim.kickstart.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/setting.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyB-n6wvcCPpgMSidW6LZo768P7ultCIMqI&libraries=places"></script>
@endsection
