Hello <i>{{ $order_data['user_name'] }}</i>,
<p>We are writing to let you know that the status of your order associated with your participated challenge has been changed.</p>
 
<p><u>Order Information: Order ID - #{{ $order_data['order_id'] }}</u></p>
 
<div>
<p><b>Product Name:</b>&nbsp;{{ $order_data['product_name'] }}</p>
<p><b>Associated Challenge:</b>&nbsp;{{ $order_data['associated_challenge'] }}</p>
<p><b>Order status:</b>&nbsp;{{ $order_data['status_name'] }}</p>
</div>
 
Thank You,
<br/>
<i>Virtual Challenge Team</i>