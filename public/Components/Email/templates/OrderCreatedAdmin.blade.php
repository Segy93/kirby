<p>NARUDŽBINA KREIRANA</p>
<?php $total = 0; ?>
<p>Korisnik {{$params['order']->user->email}} je kreirao <a href = "{{$params['link'].$params['order']->id}}">narudžbinu</a></p>
<table>
    <thead>
        <th>Naziv proizvoda</th>
        <th>Cena</th>
        <th>Količina</th>
    </thead>
    @foreach($params['cart'] as $cart)
        <tr>
                <?php $total += $cart->product->price_discount; ?>
                <td>{{$cart->product->name}}</td>
                <td>{{$cart->product->price_discount}}</td>
                <td>{{$cart->quantity}}</td>
        </tr>
    @endforeach
</table>
<?php $order = $params['order']; ?>
<table>
    <thead>
        <th>Datum isporuke</th>
        <th>Poštarina</th>
        <th>Adresa dostave</th>
        <th>Adresa plaćanja</th>
        <th>Status</th>
        <th>Tip plaćanja</th>
        <th>Ukupna cena</th>
    </thead>
    <tbody>
        <tr>
            <td>{{date_format($order->date_delivery, 'Y-m-d H:i:s')}}</td>
            <td>{{$order->shipping_fee}}</td>
            <td>{{$order->delivery_address->address}}</td>
            <td>{{$order->billing_address->address}}</td>
            <td>{{$order->last_update}}</td>
            <td>{{$order->payment_method->method}}</td>
            <td>{{$total}}</td>
        </tr>
    </tbody>
</table>
Poruka:
    {{$order->note}}

