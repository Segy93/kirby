<section class=" columns columns">
    <?php $i = 0; ?>
    @foreach ($columns as $column)
        @if($bigger === $i)
            <div            
                class = 
                    "
                    @if($bigger === $i)
                        columns__single--bigger
                    @endif
                    columns__single columns__single--{{ $i++ }}
                    "
            >
        @else 
            <aside            
                class = 
                    "
                    @if($bigger === $i)
                        columns__single--bigger
                    @endif
                    columns__single columns__single--{{ $i++ }}
                    "
            >
        @endif

            @if (is_array($column))

                <?php $j = 0; ?>
                @foreach ($column as $child)
                    <div class="columns__child columns__child--{{ $j++ }}">
                        {!! $child->renderHTML() !!}
                    </div>
                @endforeach

            @else

                {!! $column->renderHTML() !!}

            @endif
        <?php 
            // ovde proveravam $i - 1 zato sto gore se $i poveca za 1 pa imam los html ako radi proveru da li je trenutni veci 
        ?>
        @if($bigger === $i - 1)
            </div>
        @else
            </aside>
        @endif
    @endforeach
</section>
