<table class="atom_product_details__table" itemscope itemtype="http://schema.org/PropertyValue">
    @foreach ($details->attribute_values__order_product as $attribute_value)
        @if ($attribute_value->attribute->label !== 'Link proizvođača' || ($details->link === null && $attribute_value->attribute->label === 'Link proizvođača'))
            <tr class = "atom_product_details__table_row">
                <th class="atom_product_details__table_header" itemprop="name">{{ $attribute_value->attribute->label }}</th>
                <td class="atom_product_details__table_cell" itemprop="value">
                    @if ($attribute_value->attribute->label === "Link proizvođača")
                        <a class = "atom_product_details__product_link" href="{{ $attribute_value->value }}" rel="noopener" target = "_blank">
                            {{ $attribute_value->value }}
                        </a>
                    @else
                        {{ $attribute_value->value }}
                    @endif
                </td>
            </tr>
        @endif
    @endforeach

    @if ($details->shouldShowDimensions())
        <tr class = "atom_product_details__table_row">
            <th class="atom_product_details__table_header" itemprop="name">Dimenzije</th>
            <td class="atom_product_details__table_cell" itemprop="value">{{ $details->getDimensionsDisplay() }}</td>
        </tr>
    @endif

    @if ($details->shouldShowWeight())
        <tr class = "atom_product_details__table_row">
            <th class="atom_product_details__table_header" itemprop="name">Težina</th>
            <td class="atom_product_details__table_cell" itemprop="value">{{ $details->getWeightDisplay() }}</td>
        </tr>
    @endif
    @if ($details->ean !== null)
        <tr class = "atom_product_details__table_row">
            <th class="atom_product_details__table_header" itemprop="name">EAN</th>
            <td class="atom_product_details__table_cell" itemprop="value">{{ $details->ean }}</td>
        </tr>
    @endif
    @if ($details->link !== null)
        <tr class = "atom_product_details__table_row">
            <th class="atom_product_details__table_header" itemprop="name">Link proizvođača</th>
            <td class="atom_product_details__table_cell" itemprop="value">
                <a class = "atom_product_details__product_link" href="{{ $details->link }}" rel="noopener" target = "_blank">{{ $details->link }}</a>
            </td>
        </tr>
    @endif
</table>
