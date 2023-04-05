AKTIVACIONI MAIL
@if ($params['is_register'])
    Uspešno ste se registrovali, aktivirajte nalog dole
@endif
@if ($params['username'])
    Korisnicko ime: {{$params['username']}}
@endif

<a href="{{ $params['link'] }}" target="_blank">Email aktivacija</a>