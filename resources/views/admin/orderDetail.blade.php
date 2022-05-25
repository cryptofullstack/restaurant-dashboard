@extends('admin.main')
@section('title')
Order Detail
@endsection
@section('page_title')
Order Detail
@endsection
@section('page_css')
    <style media="screen">
        .m-portlet .m-portlet__head.thin {
            height: 3rem;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile m-portlet--accent m-portlet--head-solid-bg">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <i class="la la-gear"></i> View Order
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-bordered m-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Total Price</th>
                        <th>Payment method</th>
                        <th>Payment status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$order->order_username}}</td>
                        <td>{{$order->order_email}}</td>
                        <td>{{$order->order_phone}}</td>
                        <td>{{$order->order_street}}, {{$order->order_city}} {{$order->order_zip}}</td>
                        <td>{{$order->total_price}} €</td>
                        <td>
                            @php
                                echo $order->payment_method == 0? 'Online payment' : 'Cash payment';
                            @endphp
                        </td>
                        <td>
                            @php
                                echo $order->is_payed == 0? 'Not Payed' : 'Payed';
                            @endphp
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="m-portlet__head thin">
    		<div class="m-portlet__head-caption">
    			<div class="m-portlet__head-title">
    				<h3 class="m-portlet__head-text">
    					Foods
    				</h3>
    			</div>
    		</div>
    	</div>
        <div class="m-portlet__body">
                <table class="table table-bordered m-table">
                    <thead>
                        <tr>
                            <th style="width: 30%">Food Name</th>
                            <th style="width: 30%">Food Price</th>
                            <th style="width: 40%">Food Extras</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->foods as $key => $food)
                            <tr>
                                <td style="width: 30%">{{$food->pro_name}}</td>
                                <td style="width: 30%">{{$food->pro_price}} €</td>
                                <td style="width: 60%">
                                    @if (count($food->extras) > 0)
                                        <table style="width: 100%;">
                                            @if ($key == 0)
                                                <thead>
                                                    <th style="width: 40%">Extra Group</th>
                                                    <th style="width: 40%">Extra Name</th>
                                                    <th style="width: 20%">Extra Price</th>
                                                </thead>
                                            @endif
                                            <tbody>
                                                @foreach ($food->extras as $extra)
                                                    <tr>
                                                        <td style="width: 40%">{{$extra->group_name}}</td>
                                                        <td style="width: 40%">{{$extra->extra_name}}</td>
                                                        <td style="width: 20%">{{$extra->extra_price}} €</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        No Extra
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
@section('page_js')
    {{-- <script src="{{asset('js/mOrders.js')}}" type="text/javascript"></script> --}}
@endsection
