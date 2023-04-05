<?php /*Deo komponente za listanje postojecih kategorija*/ ?>
		<table class="table table-striped table-sm table-bordered table-hover" id="admin_categories__list">
		</table>
		<script type="text/html" id="admin_categories__list_tmpl">
			<thead>
				<th>Naziv</th>
				<th>Slika</th>
				<th>Otvori</th>
			@if($permissions['category_update'])
				<th>Promeni</th>
				<th>SEO</th>
			@endif
				<th>Obriši</th>
			</thead>
			<tbody id="admin__sortable_wrapper">
				<%for(var i = 0, l = categories.length; i < l; i++ ){%>
					<%var category = categories[i];%>
					<tr
						class="admin_categories__list_row"
						data-id = "<% category.id%>"
					>
						<td
							class="admin_categories__list_name"
						>
							<%= category.name %>
						</td>
						<td
							class="admin_categories__list_picture"
						>
					@if($permissions ['category_update'])
							<input type="file" class="hidden admin_categories__picture_change" data-category-id="<%= category.id %>"/>
					@endif
							<img alt = "<%= category.name %> picture" src = "/uploads_static/originals/<%=category.picture%>" class="admin_categories__image"  width="160"/>
						</td>
					<td>
                        <?php /*Otvori*/ ?>
                            <a
                            	class             = "btn btn-success admin_categories__list_open "
                            	data-machine_name = "articleCategory_<%= category.id %>"
                            	data-tag-id       = "<%= category.id %>"
                            	data-target       = "#admin_categories__open"
                            	type              = "button"
                                href 			  = "/<%= category.url %>"
                                style 			  = "
                                    text-decoration: none;
                                    color:#fff;"
                            >Otvori
                            </a>
                    </td>
					@if($permissions['category_update'])
						<?php /*Izmena*/?>
						<td>
							<button
								class       ="btn btn-warning admin_categories__list_change"
								data-target = "#admin_categories__modal_change"
								data-category-id = "<%= category.id %>"
								data-toggle = "modal"
								type        = "button"
							>Promeni
							</button>
						</td>
						 <?php /*SEO izmena*/?>
						 <td>
		                    <button
		                        class       ="btn btn-warning admin_categories__list_seo seo__invoker"
		                        data-category-id = "<%= category.id %>"
		                        data-machine_name="articleCategory_<%= category.id %>"
		                        data-target = "#admin_categories__modal_seo"
		                        data-toggle = "modal"
		                        type        = "button"
		                    >SEO
		                    </button>
		                </td>
					@endif
					@if ($permissions['category_delete'])
						<td>
			                <?php /*Obriši*/?>
			                <button
			                    class       ="btn btn-danger admin_categories__list_delete"
			                    data-target = "#admin_categories__modal_delete"
			                    data-category-id = "<%= category.id %>"
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
