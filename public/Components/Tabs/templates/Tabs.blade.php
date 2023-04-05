<style nonce = "{{$_SESSION['token']}}">
    <?php $selector = ''; ?>
    @for ($i = 0, $l = count($tabs); $i < $l; $i++)
        .tabs__radio--{{ $i }}:checked~.tabs__content .tabs__single--{{ $i }}{{ $i === $l-1 ? '' : ',' }}
    @endfor

    {{ $selector }} {
        display: block;
    }
</style>

<section class="tabs">
    @foreach ($tabs as $index => $tab)
        <input
            @if ($active !== '')
                {{ $tab['label'] === $active ? 'checked' : '' }}
            @elseif ($index === 0)
                checked
            @endif
            class="common_landings__visually_hidden tabs__radio tabs__radio--{{ $index }}"
            id="tabs__radio--{{ $index }}"
            name="tabs__radio"
            type="radio"
        />

        <h2 class = "tabs__heading">
            <label class="tabs__label" for="tabs__radio--{{ $index }}">
                {{ $tab['label'] }}
                @if (array_key_exists('has_notifications', $tab) && $tab['has_notifications'])
                    <span class="tabs__notification">
                        
                    </span>
                @endif
            </label>
        </h2>
    @endforeach

    <div class="tabs__content">
        @foreach ($tabs as $index => $tab)
            <div class="tabs__single tabs__single--{{ $index }}">
                {!! $tab['component']->renderHTML(
                    empty($children_args) ? null : $children_args[$index]
                ) !!}
            </div>
        @endforeach
    </div>
</section>