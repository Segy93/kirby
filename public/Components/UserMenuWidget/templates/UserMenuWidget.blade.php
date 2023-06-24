<svg class = "common_landings__display_none" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    <defs>
        <symbol id="user_menu__icon--cart" viewBox="0 0 32 32">
            <title>Korpa</title>
            <path d="M20.756 5.345c-0.191-0.219-0.466-0.345-0.756-0.345h-13.819l-0.195-1.164c-0.080-0.482-0.497-0.836-0.986-0.836h-2.25c-0.553 0-1 0.447-1 1s0.447 1 1 1h1.403l1.86 11.164c0.008 0.045 0.031 0.082 0.045 0.124 0.016 0.053 0.029 0.103 0.054 0.151 0.032 0.066 0.075 0.122 0.12 0.179 0.031 0.039 0.059 0.078 0.095 0.112 0.058 0.054 0.125 0.092 0.193 0.13 0.038 0.021 0.071 0.049 0.112 0.065 0.116 0.047 0.238 0.075 0.367 0.075 0.001 0 11.001 0 11.001 0 0.553 0 1-0.447 1-1s-0.447-1-1-1h-10.153l-0.166-1h11.319c0.498 0 0.92-0.366 0.99-0.858l1-7c0.041-0.288-0.045-0.579-0.234-0.797zM18.847 7l-0.285 2h-3.562v-2h3.847zM14 7v2h-3v-2h3zM14 10v2h-3v-2h3zM10 7v2h-3c-0.053 0-0.101 0.015-0.148 0.030l-0.338-2.030h3.486zM7.014 10h2.986v2h-2.653l-0.333-2zM15 12v-2h3.418l-0.285 2h-3.133z"></path>
            <path d="M10 19.5c0 0.828-0.672 1.5-1.5 1.5s-1.5-0.672-1.5-1.5c0-0.828 0.672-1.5 1.5-1.5s1.5 0.672 1.5 1.5z"></path>
            <path d="M19 19.5c0 0.828-0.672 1.5-1.5 1.5s-1.5-0.672-1.5-1.5c0-0.828 0.672-1.5 1.5-1.5s1.5 0.672 1.5 1.5z"></path>
        </symbol>
    </defs>
</svg>

<nav class = "user_menu" role = "menu">
    @if ($isLoggedIn)
        <a class = "user_menu__item" href = "/logout" role = "menuitem">Odjavi se</a>
        <a
            class = "user_menu__item"
            href  = "/korisnik/{{$user->username}}"
            role  = "menuitem"
        >
            Profil
            @if ($notify)
                <span class="user_menu__notification_dot"></span>
            @endif
        </a>
    @else
        <a class = "user_menu__item" href = "/prijava" role = "menuitem">Prijavi se</a>
        <a class = "user_menu__item" href = "/registracija" role = "menuitem">Registruj se</a>
    @endif

    <a class = "user_menu__item user_menu__cart" href = "/korpa" role = "menuitem">
        <svg class="common_landings__icon user_menu__icon--cart">
            <use xlink:href="#user_menu__icon--cart"></use>
        </svg>
        {!! $cart->renderHTML() !!}
    </a>

    <a class = "user_menu__item" href = "/lista-zelja" role = "menuitem">{!! $wishList->renderHTML() !!}</a>
</nav>
