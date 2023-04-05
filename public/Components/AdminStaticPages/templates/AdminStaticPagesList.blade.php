<?php /*Deo komponente za listanje postojecih kategorija*/ ?>
		<a
			class             = "btn btn-success "
			type              = "button"
			href 			  = "/admin/staticne/strana"
			style 			  = "
				text-decoration: none;
				color:#fff;"
		>Kreiraj novu stranu
		</a>
		<table class="table table-striped table-sm table-bordered table-hover" id="admin_pages__static_list">
		</table>
		<script type="text/html" id="admin_pages__static_list__tmpl">
			<thead>
				<th>Naziv</th>
				<th>Otvori</th>
			@if($permissions['page_static_update'])
				<th>Promeni</th>
				<th>SEO</th>
			@endif
				<th>Obriši</th>
			</thead>
			<tbody id="admin__sortable_wrapper">
				<%for(var i = 0, l = pages.length; i < l; i++ ){%>
					<%var page = pages[i];%>
					<tr
						class="admin_pages__list_row"
						data-id = "<% page.id%>"
					>
						<td
							class="admin_pages__list_name"
						>
							<%= page.title %>
						</td>
					<td>
                        <?php /*Otvori*/ ?>
                            <a
                            	class             = "btn btn-success admin_pages__list_open "
                            	data-machine_name = "static_<%= page.id %>"
                            	data-tag-id       = "<%= page.id %>"
                            	data-target       = "#admin_pages__open"
                            	type              = "button"
                                href 			  = "/<%= page.url %>"
                                style 			  = "
                                    text-decoration: none;
                                    color:#fff;"
                            >Otvori
                            </a>
                    </td>
					@if($permissions['page_static_update'])
						<?php /*Izmena*/?>
						<td>
							<a
								class       		="btn btn-warning admin_pages__list_change"
								href 			  	= "/admin/staticne/strana/<%= page.id %>"
                                style 			  	= "
                                    text-decoration: none;
                                    color:#fff;"
							>Promeni
							</a>
						</td>
						 <?php /*SEO izmena*/?>
						 <td>
		                    <button
		                        class       ="btn btn-warning admin_pages__list_seo seo__invoker"
		                        data-page-id = "<%= page.id %>"
		                        data-machine_name="static_<%= page.id %>"
		                        data-target = "#admin_pages__static_modal__seo"
		                        data-toggle = "modal"
		                        type        = "button"
		                    >SEO
		                    </button>
		                </td>
					@endif
					@if ($permissions['page_static_delete'])
						<td>
			                <?php /*Obriši*/?>
			                <button
			                    class       ="btn btn-danger admin_pages__list_delete"
			                    data-target = "#admin_page__static_modal__delete"
			                    data-page-id = "<%= page.id %>"
			                    type        = "button"
			                    data-toggle = "modal"
			                >Obriši
			                </button>
			            </td>
					@endif
					</tr>

				<%}%>
			</tbody>
		</script>
