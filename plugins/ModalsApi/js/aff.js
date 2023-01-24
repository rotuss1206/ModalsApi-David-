jQuery(function($) {
	let site_url = document.location.origin;	
	let strGET = window.location.search.replace( '?', '');
	let arrGET = strGET.split('&');
	let i = 0;
	let aff = '';
	
	while(i < arrGET.length){
		let arrGET_value = arrGET[i].split('=');
		if(arrGET_value[0] == 'aff'){
			aff = arrGET_value[1];
			$.ajax({
				type: 'POST',
				url: site_url + '/wp-admin/admin-ajax.php',
				data: {
					aff:aff,
					action: 'add_aff'
				},
				dataType: "json",
				cache: false,
			error: function(error){
				alert("error");
				},
			success: function(data){
					console.log('ss');
				} //endsuccess
			}); //endajax	
		}
		i++;
	}
});