@component('mail::message')
# Order Confirmation Email

<p style="color: #000000; font-weight: bold; font-size:20px;" >Username: {{$order->order_username}}</p>
<br />
<p style="color: #000000; font-weight: bold; font-size:20px;" >Email: {{$order->order_email}}</p>
<br />
<p style="color: #000000; font-weight: bold; font-size:20px;" >Time: {{$order->order_time}}</p>
<br />
<p style="color: #000000; font-weight: bold; font-size:20px;" >Price: {{$order->total_price}}</p>
<br />
<p style="color: #000000; font-weight: bold; font-size:20px;" >Payment Method: @php echo $order->payment_method == 1? 'Online Payment' : 'Cash Payment' @endphp</p>
@endcomponent
