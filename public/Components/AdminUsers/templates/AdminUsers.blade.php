<?php /*Forma za kreiranje korisnika */ ?>
@if ($permissions['user_create'])
<form action="" enctype="multipart/form-data" id="admin_users__create__form" method="post">
    {!! $csrf_field !!}
    <div class="form-group">
        <label for="admin_users__create__input">Korisničko ime</label>
        <input class="form-control" id="admin_users__create__input" maxlength="63" name="username" placeholder="Korisničko ime" required="required" type="text">

        <label for="admin_users__create__email">Email</label>
        <input class="form-control" id="admin_users__create__email" maxlength="63" name="email" placeholder="Email " required="required" type="email">

        <label for="admin_users__create__password">Šifra</label>
        <input autocomplete="new-password" class="form-control" id="admin_users__create__password" maxlength="63" min="6" name="password" placeholder="Šifra" required="required" type="password">

        <label for="admin_users__create__image">Slika</label>
        <input id="admin_users__create__image" type="file" class="form-control" name="image"/>

        <label for="admin_users__create__name">Ime</label>
        <input class="form-control" id="admin_users__create__name" maxlength="63" name="name" placeholder="Ime" type="text">

        <label for="admin_users__create__surname">Prezime</label>
        <input class="form-control" id="admin_users__create__surname" maxlength="127" name="surname" placeholder="Prezime" type="text">

        <label for="admin_users__create__address_of_living">Adresa stanovanja</label>
        <input class="form-control" id="admin_users__create__address_of_living" maxlength="255" name="address_of_living" placeholder="Adresa stanovanja" type="text">

        <label for="admin_users__create__address_of_delivery">Adresa isporuke</label>
        <input class="form-control" id="admin_users__create__address_of_delivery" maxlength="255" name="name" placeholder="Adresa isporuke" type="text">

        <label for="admin_users__create__home_phone">Fiksni telefon</label>
        <input
            class="form-control"
            id="admin_users__create__home_phone"
            maxlength="63"
            name="name"
            pattern="^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
            placeholder="Fiksni telefon"
            type="tel"
        />

        <label for="admin_users__create__mobile_phone">Mobilni telefon</label>
        <input
            class="form-control"
            id="admin_users__create__mobile_phone"
            maxlength="63"
            name="name"
            pattern="^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
            placeholder="Mobilni telefon"
            type="tel"
        />

        <input id="admin_users__create__submit" type="submit" class="btn btn-default" value="Napravi">
    </div>
</form>
@endif


<div class="row" >
    <div class="col-md-4">
            <button type="button" class="admin_users__export_tabl_to_csv btn  btn-success">Prebaci u tabelu</button>
    </div>
    <div class="col-md-4">
            <div class="input-group">
                 <input type="text" class="form-control admin_users__search_box" placeholder="Pretraga korisnika po email-u">
                    <span class="input-group-btn">
                        <button class="btn btn-success admin_users__search_button" type="button">Pretraga</button>
                    </span>
            </div>
    </div>
    <div class="col-md-4" id="admin_users__user_info">
    </div>
</div>





<?php /* Tabela za korisnike */ ?>
<table class="table table-striped table-sm table-bordered table-hover" id="admin_users__list">










<?php /* Statistika za korisnika, sablon */ ?>
<script type="text/html" id="admin_users__users_info__tmpl">
     <h3>Broj korisnika: <%= nrUsers %></h3>
     <h3>Broj trenutno aktivnih korisnika: <%= nrUsersCurrent %></h3>
     <h3>Broj banovanih korisnika: <%= nrUsersBanned %></h3>
</script>










<?php /* Lista korisnika sa mogucnoscu...*/ ?>
@if ($permissions['user_read'])
    <script type="text/html" id="admin_users__list__tmpl">
        <thead>
                @if ($permissions['user_update'])
                <th class="col-md-1">Slika</th>
                <th class="col-md-1">Korisničko ime</th>
                <th class="col-md-1">Statistika</th>
                <th class="col-md-1">Informacije</th>
                <th class="col-md-1">Kvizovi</th>
                <th class="col-md-1">Testovi</th>
                <th class="col-md-1">Nagrade</th>
                <th class="col-md-1">Ban</th>
                <th class="col-md-1">Tabela prijava</th>
                <th class="col-md-1">Promena šifre</th>
            @endif
            @if ($permissions['user_delete'])
                <th class="col-md-1">Brisanje Korisnika</th>
            @endif
        </thead>
        <tbody>
        	<%for(var i = 0, l = users.length; i < l; i++) {%>
                <%var user = users[i];%>
                <%var badge = badges[i];%>
                <tr>
                    @if ($permissions['user_update'])

                    <?php /* Slika */ ?>
                    <td class="vert-align">
                             @if ($permissions['user_update'])
                                     <input  class="admin_users__change_image" data-user-id="<%= user.id %>" type="file">
                            @endif
                            <img alt="<%= user.name %>"  class="admin_users__image" src="/uploads_user/original/<%= user.profile_picture %>" />
                    </td>
                    <td class="admin_users__username admin_users__username--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.username %>
                    </td>
                    <td class="admin_users__statistics vert-align">
                         <button type="button" class="admin_users__statistic btn btn-warning vert-align" data-user_id="<%= user.id %>" data-toggle="modal" data-target="#admin_users__modal_statistic">Otvori</button>
                    </td>
                    <td class="admin_users__information vert-align">
                         <button type="button" class="admin_users__edit__button_change btn btn-warning vert-align"
                           data-user_id="<%= user.id%>"
                           data-toggle="modal"
                           data-target="#admin_users__modal"
                          >
                          Otvori
                          </button>
                    </td>
                    <?php /* Kvizovi */ ?>
                    <td class="vert-align">
                    </td>
                    <?php /* Testovi */ ?>
                    <td class="vert-align">
                    </td>
                    <?php /* Nagrade */ ?>
                    <td class="vert-align">
                    </td>
                     <?php /* Banuj*/ ?>

                    <td class="vert-align">
                            <!-- Ban lista -->
                            <div class="btn-group admin_users__btn_group">
                                <% if (user.status === 1 && user.banned === null || user.banned !== null && new Date(user.banned.date).getTime() > new Date().getTime()) { %>
                                    <button type="button" class="btn btn-danger admin_users__permanent_ban_set" data-ban-length="0" data-user-id="<%= user.id %>">Skini ban</button>
                                    <% } else { %>
                                        <button type="button" class="btn btn-danger admin_users__permanent_ban_set" data-ban-length="1" data-user-id="<%= user.id %>">Definitivan ban</button>
                                        <button type="button" class="btn  btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="admin_users__ban_set" data-ban-length="1" data-user-id="<%= user.id %>">1 dan</a></li>
                                            <li><a href="#" class="admin_users__ban_set" data-ban-length="7" data-user-id="<%= user.id %>">7 dana</a></li>
                                            <li><a href="#" class="admin_users__ban_set" data-ban-length="30" data-user-id="<%= user.id %>">1 mesec</a></li>
                                        </ul>
                                        <% } %>
                                        <div>
                                <% if (user.status === 1 && user.banned === null) {%>
                                    <span class="label label-danger">Banovan je za stalno</span>
                                <% } else if(user.banned !== null && new Date(user.banned.date).getTime() > new Date().getTime()) { %>
                                    <span class="label label-danger">Korisnik je banovan do: <%= user.banned %></span>
                                <% } %>
                            </div>
                            </div>
                           
                      </td>
                         <?php /* Tabela prijava*/ ?>
                        <td class="vert-align">
                            <button type="button" class="admin_users__edit__button_change_login_table btn btn-warning vert-align" data-user_id="<%= user.id %>" data-toggle="modal" data-target="#admin_users__modal_login_table">Otvori</button>
                        </td>
                    @endif
                           <td class="vert-align">
                            <button class="btn btn-warning admin_users__edit__button_reset admin_users__edit__button_reset--<%= user.id %> vert-align" data-toggle="modal" data-target="#admin_users__modal_password" data-user_id="<%= user.id %>" name="reset" type="button">Promeni</button>
                        </td>
                    @if ($permissions['user_delete'])
                        <td class="vert-align">
                            <button class="btn btn-danger admin_users__edit__button_delete vert-align" data-user_id="<%= user.id %>" name="delete" type="button" data-toggle="modal" data-target="#admin_users__modal_delete">Obriši</button>
                        </td>

                    @endif
                    <td class="admin_users__more_information admin_users__email admin_users__email--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.email %>
                    </td>
                    <td class="admin_users__optional_information admin_users__name admin_users__name--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.name %>
                    </td>
                    <td class="admin_users__optional_information admin_users__surname admin_users__surname--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.surname %>
                    </td>
                    <td class="admin_users__optional_information admin_users__address_of_living admin_users__address_of_living--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.address_of_living %>
                    </td>
                    <td class="admin_users__optional_information admin_users__address_of_delivery admin_users__address_of_delivery--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.address_of_delivery %>
                    </td>
                    <td class="admin_users__optional_information admin_users__home_phone admin_users__home_phone--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.home_phone %>
                    </td>
                    <td class="admin_users__optional_information admin_users__mobile_phone admin_users__mobile_phone--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%= user.mobile_phone %>
                    </td>
                    <?php /* XP */ ?>
                    <td class="admin_users__more_information admin_users__scale_knowledge admin_users__scale_knowledge--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%=user.scale_knowledge %>
                    </td>
                    <?php /* Nivo */ ?>
                    <td class="admin_users__more_information admin_users__badges--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <% if (badge !==undefined) { %>
                        <%= badge.picture %>
                                <% } else { %>
                                    <p>Nije dostigao nijedan nivo</p>
                                    <% } %>
                    </td>
                    <?php /* Bodovi */ ?>
                    <td class="admin_users__more_information admin_users__points admin_users__points--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%=user.points %>
                    </td>
                    <?php /* Energija*/ ?>
                    <td class="admin_users__more_information admin_users__energy admin_users__energy--<%= user.id %> vert-align" data-user_id="<%= user.id %>">
                        <%=user.energy_relation.quantity %>
                    </td>
        		</tr>
        	<%};%>
        </tbody>
    </script>
@endif
</table>
<nav  class="admin_users__pagination_nav" aria-label="Page navigation">
      <ul class="pagination">
            <li class="page-item admin_users__pagination_previous">
                  <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                  </a>
            </li>
            <li class="page-item admin_users__pagination_next">
                  <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                  </a>
            </li>
      </ul>
</nav>









<!-- Popup(Modal) za izmenu -->
<div class="modal fade" id="admin_users__modal" tabindex="-1" role="dialog" aria-labelledby="admin_users__modal__label">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
    <form role="form" id="admin_users__form_edit" method="post" action="">
        {!! $csrf_field !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="admin_users__modal__label">Promeni</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="admin_users__edit__input_change_username">Korisničko ime</label>
                <input autofocus="autofocus" class="form-control" id="admin_users__edit__input_change_username" maxlength="63" name="username" placeholder="Korisničko ime" required="required" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_email">Email</label>
                <input class="form-control" id="admin_users__edit__input_change_email" maxlength="63" name="email" placeholder="Email" required="required" type="email">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_name">Ime</label>
                <input class="form-control" id="admin_users__edit__input_change_name" maxlength="63" name="name" placeholder="Ime" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_surname">Prezime</label>
                <input class="form-control" id="admin_users__edit__input_change_surname" maxlength="63" name="surname" placeholder="Prezime" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_address_of_living">Adresa stanovanja</label>
                <input class="form-control" id="admin_users__edit__input_change_address_of_living" maxlength="63" name="name" placeholder="Korisničko ime" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_address_of_delivery">Adresa isporuke</label>
                <input class="form-control" id="admin_users__edit__input_change_address_of_delivery" maxlength="63" name="name" placeholder="Korisničko ime" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_home_phone">Fiksni telefon</label>
                <input class="form-control" id="admin_users__edit__input_change_home_phone" maxlength="63" name="name" placeholder="Korisničko ime" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_mobile_phone">MObilni telefon</label>
                <input class="form-control" id="admin_users__edit__input_change_mobile_phone" maxlength="63" name="name" placeholder="Korisničko ime" type="text">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            <input id="admin_users__edit__save" type="submit" class="btn btn-primary" value="Sačuvaj">
        </div>
    </form>
</div>
    </div>
</div>

<!-- Popup(Modal) za brisanje korisnika -->
<div class="modal fade" id="admin_users__modal_delete" tabindex="-1" role="dialog" aria-labelledby="admin_users__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="admin_users__modal__label">Brisanje administratora</h4>
            </div>
            <div class="modal-body">
                <p>Ovime ćete obrisati administratora. Podatke nije moguće povratiti. Da li želite da nastavite?</p>
            </div>
            <div class="modal-footer">
                <button autofocus="autofocus" type="button" class="btn btn-default" data-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="admin_users__edit__delete">Obriši</button>
            </div>
        </div>
    </div>
</div>

<!-- Popup(Modal) za izmenu sifre -->
<div class="modal fade" id="admin_users__modal_password" tabindex="-1" role="dialog" aria-labelledby="admin_users__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" id="admin_users__form_edit_password" method="post" action="">
                {!! $csrf_field !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="admin_users__modal__label">Promeni</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_users__edit__input_change_password">Nova šifra</label>
                        <input autofocus="autofocus" class="form-control" id="admin_users__edit__input_change_password" maxlength="63" name="password" placeholder="Nova šifra" required="required" type="password">
                    </div>
                    <div class="form-group">
                        <label for="admin_users__edit__input_validate_password">Potvrda šifre</label>
                        <input class="form-control" id="admin_users__edit__input_validate_password" maxlength="63" name="password" placeholder="Potvrda šifre" required="required" type="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input id="admin_users__edit__save" type="submit" class="btn btn-primary" value="Sačuvaj">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Popup(Modal) za statistiku -->
<div class="modal fade" id="admin_users__modal_statistic" tabindex="-1" role="dialog" aria-labelledby="admin_users__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" id="admin_users__form_statistic" method="post" action="">
            {!! $csrf_field !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="admin_users__modal__label">Promeni</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="admin_users__edit__input_change_xp">XP</label>
                <input autofocus="autofocus" class="form-control" id="admin_users__edit__input_xp" maxlength="63" name="xp" placeholder="XP" required="required" type="text">
            </div>
             <div class="form-group" >
                <img height="100px" width="100px" id="admin_users__edit__input_badge"/>
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_points">Bodovi</label>
                <input class="form-control" id="admin_users__edit__input_points" maxlength="63" name="points" placeholder="Bodovi" required="required" type="text">
            </div>
            <div class="form-group">
                <label for="admin_users__edit__input_change_energy">Energija</label>
                <input class="form-control" id="admin_users__edit__input_energy" maxlength="63" name="energy" placeholder="Energija" required="required" type="text">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            <input id="admin_users__edit__save" type="submit" class="btn btn-primary" value="Sačuvaj">
        </div>
    </form>
        </div>
    </div>
</div>

<!-- Popup(Modal) za login tabelu -->
<div class="modal fade" id="admin_users__modal_login_table" tabindex="-1" role="dialog" aria-labelledby="admin_users__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <table class="table table-striped table-sm table-bordered table-hover" >
                <thead>
                    <th>Ip</th>
                    <th>Datum</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


