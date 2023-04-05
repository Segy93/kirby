<section class="common_landing__main_bg_pattern panes panes--{{ $pane_count }}">
    <style nonce = "{{$_SESSION['token']}}">
        @if ($pane_count > 0)
            .panes__single {
                width: {{ 100/$pane_count }}%;
            }

            @for ($i = 0; $i < $pane_count; $i++)
                @media (max-width: {{ ($pane_count-$i+1)*360 }}px) {
                    .panes__single {
                        width: {{ 100/($pane_count - $i) }}%;
                    }
                }
            @endfor
        @endif
    </style>


    <?php $i = 0; ?>
    @foreach ($panes as $pane)
        <div class="panes__single panes__single--{{ $i++ }}">
            @if (is_array($pane))

                <?php $j = 0; ?>
                @foreach ($pane as $child)
                    <div class="panes__child panes__child--{{ $i++ }}">
                        {!! $child->renderHTML() !!}
                    </div>
                @endforeach

            @else

                {!! $pane->renderHTML() !!}

            @endif
        </div>
    @endforeach
</section>