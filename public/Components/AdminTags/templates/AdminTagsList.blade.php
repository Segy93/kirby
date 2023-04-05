<table class="admin_tags__list_table table table-striped table-sm table-bordered table-hover">
    <thead>
        <th>Naziv</th>
        <th>Otvori</th>
        <th>Promeni</th>
        <th>SEO</th>
        <th>Obriši</th>
    </thead>
    <tbody id="admin_tags__list_content">
    </tbody>
</table>

<script type="text/html" id="admin_tags__list_tmpl">
    @if($permissions['tag_read'])
        <%for(var i = 0, l = tags.length; i < l; i++) {%>
            <%var tag = tags[i];%>
            <tr>
                <td>
                    <?php /*Ime*/?>
                    <%= tag.name %>
                </td>
                    <td>
                        <?php /*Otvori*/ ?>
                            <a
                                class             = "btn btn-success admin_tag__list_open "
                                data-machine_name = "tag_<%= tag.id %>"
                                data-tag-id       = "<%= tag.id %>"
                                data-target       = "#admin_tags__open"
                                href              = "/<%= tag.url %>"
                                type              = "button"
                                style             = "
                                    text-decoration: none;
                                    color:#fff;"
                            >Otvori
                            </a>
                    </td>
                @if($permissions['tag_update'])
                    <td>
                        <?php /*Izmena*/?>
                        <button
                            class       = "btn btn-warning admin_tags__list_change"
                            data-target = "#admin_tags__modal_change"
                            data-tag-id = "<%= tag.id %>"
                            data-toggle = "modal"
                            type        = "button"
                        >Promeni
                        </button>
                    </td>
                    <td>
                        <?php /*SEO izmena*/?>
                        <button
                            class             = "btn btn-warning admin_tags__list_seo seo__invoker"
                            data-target       = "#admin_tags__modal_seo"
                            data-tag-id       = "<%= tag.id %>"
                            data-toggle       = "modal"
                            data-machine_name = "tag_<%= tag.id %>"
                            type              = "button"
                        >SEO
                        </button>
                    </td>
                @endif
                @if($permissions['tag_delete'])
                    <td>
                        <?php /*Obriši*/?>
                        <button
                            class       ="btn btn-danger admin_tags__list_delete"
                            data-target = "#admin_tags__modal_delete"
                            data-tag-id = "<%= tag.id %>"
                            type        = "button"
                            data-toggle = "modal"
                        >Obriši
                        </button>
                    </td>
                @endif
            </tr>
        <%}%>
    @endif
</script>
