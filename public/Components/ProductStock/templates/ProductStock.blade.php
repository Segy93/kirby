<table
    class = "product_stock"
    itemscope
    href = "http://schema.org/ItemAvailability"
>
    @foreach ($status as $label => $info)
        <tr class="product_stock__row">
            <th class="product_stock__key">
                {{ $label }}
            </th>

            <td class = "product_stock__value">
                @if ($js_template)
                    <% if (product.{{ $info }}) { %>
                        <link
                            itemprop="availability"
                            href="http://schema.org/InStock"
                        />
                        raspoloživo
                    <% } %>
                @elseif ($info)
                    <link
                        itemprop="availability"
                        href="http://schema.org/InStock"
                    />
                    raspoloživo
                @else
                    <link itemprop="availability" href="http://schema.org/OutOfStock" />
                    nedostupno
                @endif
            </td>
        </tr>
    @endforeach
</table>
