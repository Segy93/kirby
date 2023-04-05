<div class="user_profile__div_info">
    @if (array_key_exists('error', $info))
        <p class = "user_profile__error_box">{{$info['error']}}</p>
    @elseif (array_key_exists('activation', $info))
        <p class = "user_profile__success_box">{{$info['activation']}}</p>
    @endif
</div>

{!! $tabs->renderHTML() !!}
