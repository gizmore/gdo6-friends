<div>
Dear <?=$friend->displayNameLabel()?><br/>
<br/>
<?=$user->displayNameLabel()?> requested to add you as their <?=$relation?> on <?=sitename()?>.<br/>
<br/>
You can confirm this request by visiting this link.<br/>
<br/>
<?=$link_accept?><br/>
<br/>
You can deny this request by visiting this link.<br/>
<br/>
<?=$link_deny?><br/>
<br/>
It is safe to ignore the request.<br/>
<br/>
Kind Regards,<br/>
The <?=sitename()?> Team',<br/>
</div>
