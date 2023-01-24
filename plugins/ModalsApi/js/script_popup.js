jQuery(function($) {
	let site_url = document.location.origin;	
	let strGET = window.location.search.replace( '?', '');
	let arrGET = strGET.split('&');
	let i = 0;

	$('[data-modal]').on('click', function(e){
		e.preventDefault();
		let carr_modal = $(this).attr('data-modal');
		$('.modal_backgroud').removeClass('hidden');
		$('.'+carr_modal).removeClass('hidden');
		$('body').css('overflow','hidden');
	});

	let url_location = location.href;
	let ck_urls = getCookie('ck_urls');
	let last_url = getCookie('last_url');

	setCookie("last_url", url_location, 1);

	const makeUniq = (arr) => [...new Set(arr)];

	ck_urls = ck_urls.split(",");
	ck_urls = makeUniq(ck_urls);
	console.log(ck_urls);
	i = 0;
	while(i < ck_urls.length){
		console.log(ck_urls[i]);
		if(ck_urls[i] == ''){
			ck_urls.splice(i, 1);
		}
		i++;
	}

	if(ck_urls.length < 10){
		// ck_urls.push(url_location);
		ck_urls.push(last_url);
		let locations_string = ck_urls.join(',');
		setCookie("ck_urls", locations_string, 1);
		console.log(locations_string);
		$('[name="url_path"]').val(locations_string);
	}else if(ck_urls.length < 1){
		$('[name="url_path"]').val(url_location);
	}else{
		ck_urls.splice(0, 1);
		ck_urls.push(last_url);
		let locations_string = ck_urls.join(',');
		console.log(locations_string);
		setCookie("ck_urls", locations_string, 1);
		$('[name="url_path"]').val(locations_string);
	}	

	$('[data-next_modal]').on('click', function(e){
		let modal = $(this).attr('data-next_modal');
		let answers_arr = [],tags_arr = [],i=0;
		$('.popup').addClass('hidden');
		$('.'+modal).toggleClass('hidden');
		$('.modals .popup .radio_box input').each(function(){
			if($(this).prop('checked') == true){
				answers_arr.push($(this).val());
				tags_arr.push($(this).attr('data-tags'));
			}
		});

		// url_location = location.href;
		// $('[name="url_path"]').val(url_location);

		$('.all_tags').val(tags_arr.join(';'));
		while(i <= answers_arr.length){
			$('#answer_'+(i+1)).html(answers_arr[i]);
			i++;
		}
	});
	$('.modals .close').on('click', function(e){
		$(this).closest('.popup').toggleClass('hidden');
		$('.modal_backgroud').toggleClass('hidden');
		$('body').css('overflow','auto');
	});

	// $(document).mouseup(function (e) {
	//     var container = $(".popup");
	//     if (container.has(e.target).length === 0){
	//         container.addClass('hidden');
	//         $('.modal_backgroud').addClass('hidden');
	//     }
	// });

	let success = '/success/';

	$('[data-modal]').on('click', function(e){
		e.preventDefault();
		let carr_modal = $(this).attr('data-modal');
		let modal_id = $(this).attr('id');
		success = success+'?quiz-id='+modal_id;
		$('.modal_backgroud').removeClass('hidden');
		$('.'+carr_modal).removeClass('hidden');
	});


	$('#convertkit_form').on('submit', function(e){
		e.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: site_url + '/wp-admin/admin-ajax.php',
			data: data,
		error: function(error){
			alert("error");
			},
		beforeSend: function(){
			$('.modals .preloader').removeClass('hidden');
			    },
		success: function(data){
			$('.modals .preloader').addClass('hidden');
			// window.location.reload();
			window.location.href = success;
			} //endsuccess
		}); //endajax	
	});

	function setCookie(cname, cvalue, exdays) {
	  const d = new Date();
	  d.setTime(d.getTime() + (exdays*24*60*60*1000));
	  let expires = "expires="+ d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
	  let name = cname + "=";
	  let decodedCookie = decodeURIComponent(document.cookie);
	  let ca = decodedCookie.split(';');
	  for(let i = 0; i <ca.length; i++) {
	    let c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	  }
	  return "";
	}

});