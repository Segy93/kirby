<section class = "configuration_list__page">
    <table class = "configuration_list__wrapper">
        @if (!empty($configurations))
            <thead>
                <th class = "configuration_list__table_heading configuration_list__table_heading--date">Datum kreiranja</th>
                <th class = "configuration_list__table_heading configuration_list__table_heading--date">Datum izmene</th>
                <th class = "configuration_list__table_heading">Naziv konfiguracije</th>
                <th class = "configuration_list__table_heading configuration_list__table_heading--details">Kontrole</th>
            </thead>
            <tbody class = "configuration_list__content">
                @foreach ($configurations as $configuration)
                    <tr class = "configuration_list__single_wrapper configuration_list__single_wrapper--{{ $configuration->id }}">
                        <td class = "configuration_list__table_cell configuration_list__table_cell--date">
                            {{ $configuration->date_created->format('d.m.Y.') }}
                        </td>
                        <td class= "configuration_list__table_cell configuration_list__table_cell--date">
                            {{ $configuration->date_updated !== null ? $configuration->date_updated->format('d.m.Y.') : null }}
                        </td>
                        <td class = "configuration_list__table_cell">
                            {{ $configuration->name }}
                        </td>
                        <td class = "configuration_list__table_cell configuration_list__table_cell--details">
                            <a
                                class = "configuration_list__details"
                                href  = "{{ route('configurator', ['username' => urlencode($username), 'name' => urlencode($configuration->name)]) }}"
                            >
                                Detalji
                            </a>
                            <a
                                class = "configuration_list__button_order"
                                href  = "{{ route('checkoutConfigurator', ['name' => $configuration->name]) }}"
                            >
                                Naruči
                            </a>
                            <form
                                action = "konfiguracija-brisanje"
                                class  = "configuration_list__delete_form"
                                method = "post"
                            >
                                {!! $csrf_field !!}
                                <input name = "configuration_id" type = "hidden" value = "{{ $configuration->id }}"/>
                                <button
                                    class    = "configuration_list__delete_button common_landings__button_remove"
                                    type     = "submit"
                                >
                                    Obriši
                                </a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</section>
