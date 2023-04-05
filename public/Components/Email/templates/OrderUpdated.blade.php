<p>Izmenjena narudžbina</p>

@foreach($params as  $key => $update)
    @if (is_array($update))
        @if (array_key_exists('code', $update))
        Izmena:
            {{ $update['code'] }}
        @endif

        @if (array_key_exists('comment_user', $update))
        Komentar korisnika:
            {{ $update['comment_user'] }}
        @endif

        @if (array_key_exists('comment_admin', $update))
        Komentar admina:
            {{ $update['comment_admin'] }}
        @endif

        delivery_address_id
        @if (array_key_exists('comment_admin', $update))
            Izmenjena adresa narudzbine
        @endif
    @else
        @if ($key === 'Link narudžbine')
            {{ $key }}: <a href = "{{ $update }}">Klik na link ovde</a>
        @else
            {{ $key }}: {{ $update }}
        @endif
        <br />
    @endif


@endforeach
