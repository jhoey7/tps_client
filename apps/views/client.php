<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Modul Integrasi TPS ONLINE</title>
	<script src="<?php echo base_url();?>assets/js/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.alerts.js" type="text/javascript"></script>
	<script type="text/javascript">
		var title_js = "Client";
		var base_url = "<?php echo base_url();?>";
		var site_url = "<?php echo site_url();?>";
	</script>
	<link href="<?php echo base_url();?>assets/css/jquery.alerts.css" type="text/css" rel="stylesheet">
	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	.content {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>
<div id="container">
	<h1>Modul Integrasi TPS ONLINE</h1>

	<div id="body">
		<div class="content">
			<form name="frmparse" id="frmparse" method="post" action="<?php echo str_replace('?','',site_url('client'));?>" autocomplete="off">
				<table>
					<tr>
						<td>Method</td>
						<td>:</td>
						<td>
							<select name="txt_method" id="txt_method">
								<option value=""></option>
								<option value="CoCoCont_Tes" selected="selected">CoCoCont_Tes</option>
								<option value="CoCoKms_Tes">CoCoKms_Tes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Username</td>
						<td>:</td>
						<td><input type="text" name="txt_username"  id="txt_username" value="TES"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td>:</td>
						<td><input type="text" name="txt_password"  id="txt_password" value="1234"></td>
					</tr>
					<tr valign="top">
						<td>XML Parse</td>
						<td>:</td>
						<td><textarea rows="20" cols="50" name="txt_xmlparse"  id="txt_xmlparse"></textarea></td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<input type="submit" name="btn_send" onClick="return send('#frmparse');" value="Kirim">&nbsp;
							<input type="reset" name="btn_cancel" value="Batal">
						</td>
					</tr>
				</table>
			</form>
			<div id="return"></div>
		</div>		
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
<script type="text/javascript">
function send(formid){
	var strError = "";
	if($("#txt_method").val()=="")
	{
		strError += "- Mohon pilih jenis modul.\n";
	}
	if($("#txt_username").val()=="")
	{
		strError += "- Mohon isi username anda.\n";
	}
	if($("#txt_password").val()=="")
	{
		strError += "- Mohon isi password anda.\n";
	}
	if($("#txt_xmlparse").val()=="")
	{
		strError += "- Mohon isi xml parse.";
	}
	if(strError.length>0)
	{
		Alert(strError);
	}
	else{
		$("#return").html("");
		$.ajax({
			type: 'POST',
			url: $(formid).attr('action'),
			data: 'ajax=1&'+$(formid).serialize(),
			success: function(html){
				$("#return").html(html);
				return false
			},
			complete:function(){
				ShowLoadingWait(false);
			},
			beforeSend:function(){
				ShowLoadingWait(true);						
			}	
		});
	}
	return false;
}
function Alert(text){
	jAlert(text,title_js);	
}
function LoadingOpen(){
	jLoadingOpen('',title_js);	
}

function LoadingClose(){
	jLoadingClose('',title_js);	
}

function Warning(text){
	jConfirm(text, title_js, 
		function(r) {
			if(r==true){
				//Alert(r);
				return true;
			}
			else if(r==false){
				//Alert(r);
				return false;
			}
		}
	);
}

function ShowLoadingWait(boolean){
	if(boolean){
		LoadingOpen();
	}
	else{
		LoadingClose();
	}	
}
</script>
</html>