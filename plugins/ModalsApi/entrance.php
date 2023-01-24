<?php

	$show_form = get_option('show_form');
	if($show_form == 'off'){
		$checked = '';
	}else if($show_form == 'on'){
		$checked = 'checked';
	}
?>
<h1>Modals-Api</h1>
<form id="get" action="javascript:void(0)" method="POST">

	<table>
		<tr>
			<td>Mailchimp settings</td>
		</tr>
		<tr>
			<td>
				API Key
			</td>
			<td colspan="2">
					<input type="text" name="api_key" value="<?php echo get_option('api_key'); ?>">
			</td>
			<td></td>
		</tr>
		<tr>
			<td>
				API Secret
			</td>
			<td colspan="2">
					<input type="text" name="api_secret" value="<?php echo get_option('api_secret'); ?>">
			</td>
			<td></td>
		</tr>
		<tr>
			<td>
				Form ID
			</td>
			<td colspan="2">
					<input type="text" name="form_id" value="<?php echo get_option('form_id'); ?>">
			</td>
			<td></td>
		</tr>
	</table>

	<table>
	<thead>
		<th>Show form ids:</th>
	</thead>
		<tbody>
			<tr>
				<td>
					<div style="position: relative; width: 83vw; overflow: auto;" class="textarea">
						<button type="button" class="button_show">Show convertkit forms</button>
					<pre>
						<div class="forms_ids"></div>
					</pre>
				</td>
				<td></td>
					
			</tr>
		</tbody>
	</table>

	<table>
		<tr>
			<td>Show form</td>
			<td>
				<label class="checkbox-green">
					<input type="checkbox" name="show_form" <?php echo $checked; ?>>
					<span class="checkbox-green-switch" data-label-on="On" data-label-off="Off"></span>
				</label>
			</td>
		</tr>

	</table>

	<!-- <table>
		<thead>
			<th>Modal text field</th>
		</thead>
		<tbody>
			<tr>
				<td>
					<div style="position: relative; width: 83vw; overflow: auto;" class="textarea">
						<textarea style="" name="textarea" id="code" cols="200" rows="30">
							<?php 
							// echo get_option('textarea_opt');
							 ?>
						</textarea></td>
					</div>
					
			</tr>
		</tbody>
	</table> -->
	<input type="hidden" name="action" value="chekoptions">
	<button class="save_mailchimp" type="submit">Save</button>
</form>
<p id="loading"></p>

<script type="text/javascript">



var site_url = document.location.origin;	

jQuery(function($) {

	$(document).ready(function(){
		// Define an extended mixed-mode that understands vbscript and
		// leaves mustache/handlebars embedded templates in html mode
		// var mixedMode = {
		// name: "htmlmixed",
		// scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
		//                mode: null},
		//               {matches: /(text|application)\/(x-)?vb(a|script)/i,
		//                mode: "vbscript"}]
		// };
		// var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		// mode: mixedMode,
		// selectionPointer: true
		// });
		$('#get').on('submit', function(){
			var show_form = $('[name="show_form"]').attr('checked');
			var api_key = $('[name="api_key"]').val();
			var api_secret = $('[name="api_secret"]').val();
			// var textarea = editor.getValue();
			// var textarea = $('[name="textarea"]').val();
			var data = $(this).serialize();
			if(show_form === undefined){
				show_form = 'off';
			}else{
				show_form = 'on';
			}
			// console.log(show_form);
			$.ajax({
				type: 'POST',
				url: site_url + '/wp-admin/admin-ajax.php',
				data: data,
			error: function(error){
				alert("error");
				},
			beforeSend: function(){
				    },
			success: function(data){
				window.location.reload();
				} //endsuccess
			}); //endajax	
		});

		$('.button_show').on('click', function(){
			var api_key = $('[name="api_key"]').val();
			var api_secret = $('[name="api_secret"]').val();
			$.ajax({
				type: 'POST',
				url: site_url + '/wp-admin/admin-ajax.php',
				data: {
					api_key:api_key,
					api_secret:api_secret,
					action: 'show_forms'
				},
			error: function(error){
				alert("error");
				},
			beforeSend: function(){
				    },
			success: function(data){
				$('.forms_ids').html(data);
				} //endsuccess
			}); //endajax	
		});
	});
	
});

</script>
<style>
	label{
		width: 100%;
		display: block;
	}
</style>
