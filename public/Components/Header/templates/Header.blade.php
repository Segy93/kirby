{{-- spoljni daje pozadinu a unutrasnji centrira sadrzaj --}}
<div class = "header">
    <div class="header__inner">
        @if ($header_logo !== null)
            {!! $header_logo->renderHTML() !!}
        @endif
        @if ($search !== null)
            {!! $search->renderHTML() !!}
        @endif
        @if ($user_menu !== null)
            {!! $user_menu->renderHTML() !!}
        @endif
        @if ($company_info !== null)
            {!! $company_info->renderHTML() !!}
        @endif
    </div>
</div>
