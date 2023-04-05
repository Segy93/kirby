<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="text-center">Admin</h1>
        </div>
        <div class="modal-body">
            <form action="/admin/checkpassword" method="post" class="col-md center-block">
                {!! $csrf_field !!}
                <input name="csrf-token" type="hidden" value="{{ $_SESSION['token'] }}"/>
                <div class="form-group">
                    <input autofocus type="text" class="form-control input-lg" name="username" placeholder="Korisničko ime" required="required">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control input-lg" name="password" placeholder="Šifra" required="required">
                </div>
                @if ($unsuccesful_login_attempts > 0)
                    <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Greška!</strong> Ukucali ste pogrešne pristupne podatke.
                    </div>
                @endif
                <input name="csrf-token" type="hidden" value="{{ $_SESSION['token'] }}"/>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-lg btn-primary" value="Prijava">
                </div>
            </form>
        </div>
    </div>
</div>
