Narudžbina <a href = "{{$params['link']}}"> {{$params['order']->id}}</a> je potvrđena od strane korisnika {{$params['user_email']}}
<?php $total = 0; ?>
<table>
    <thead>
        <th>Artid</th>
        <th>Naziv proizvoda</th>
        <th>Cena</th>
        <th>Količina</th>
    </thead>
    @foreach($params['order']->order_products as $order_product)
        <tr>
                <?php $total += $order_product->product->price_discount; ?>
                <td>{{$order_product->product->artid}}</td>
                <td>{{$order_product->product->name}}</td>
                <td>{{$order_product->product->price_discount}}</td>
                <td>{{$order_product->quantity}}</td>
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
            <td>{{date_format($order->date_delivery, 'd.m.Y')}}</td>
            <td>{{$order->shipping_fee}}</td>
            <td>{{$order->delivery_address->address}}</td>
            <td>{{$order->billing_address->address}}</td>
            <td>{{$params['order_update']->status}}</td>
            <td>{{$order->payment_method->method}}</td>
            <td>{{$total}}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <th>Ime korisnika</th>
        <th>Prezime korisnika</th>
        <th>Broj telefona</th>
    </thead>
    <tbody>
        <tr>
            <td>{{$order->user->name}}</td>
            <td>{{$order->user->surname}}</td>
            <td>{{$order->user->phone_nr}}</td>
        </tr>
    </tbody>
</table>

