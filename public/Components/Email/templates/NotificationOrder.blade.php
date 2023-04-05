<p>Status vase porudzbine je promenjeno</p>
<?php $update = $params['order_update']; ?>
<p>Status</p>
{{$update->status}}
<p>Komentar</p>
@if ($update->comment_user)
    {{$update->comment_user}}
@elseif ($update->comment_admin)
    {{$update->comment_admin}}
@endif
