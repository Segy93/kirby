<?php $order = $params['order']; ?>
<h1>NARUDŽBINA BROJ {{$order->id}} JE KREIRANA</h1>
<table>
    <thead>
        <th>Naziv proizvoda</th>
        <th>Cena</th>
        <th>Količina</th>
    </thead>
    @foreach ($params['cart'] as $cart)
        <tr>
            <td>{{$cart->product->name}}</td>
            <td>
                @if ($order->payment_method->method === 'Keš'
                || $order->payment_method->method === 'Virmanski'
                )
                    {{$cart->product->discount_format}} RSD
                @else
                    {{$cart->product->retail_format}} RSD
                @endif
            </td>
            <td>{{$cart->quantity}}</td>
        </tr>
    @endforeach
</table>
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
            <td>{{date_format($order->date_delivery, 'd.m.Y')}}</td>
            <td>{{$order->shipping_fee_formatted}} RSD</td>
            <td>{{$order->delivery_address->address}}</td>
            <td>{{$order->billing_address->address}}</td>
            <td>{{$params['order_update']->status}}</td>
            <td>{{$order->payment_method->method}}</td>
            <td>{{$order->total_price_formatted}} RSD</td>
        </tr>
    </tbody>
</table>

@if ($order->note !== null)
    Poruka:
    {{$order->note}}
@endif
