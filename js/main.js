$(document).ready(function(){
	$('#button_blacklist').click(function(){
		$('.div_new_devices').hide();
		$('.content').children().hide();
		$('.blacklist').show();
		$('#button_blacklist').attr('disabled','disabled');
		$('#button_profile').attr('disabled',null);
		$('#button_impressum').attr('disabled',null);
		$('#button_privacy').attr('disabled',null);
	});
	$('#button_profile').click(function(){
		$('.content').children().hide();
		$('.profile').show();
		$('#button_profile').attr('disabled','disabled');
		$('#button_blacklist').attr('disabled',null);
		$('#button_impressum').attr('disabled',null);
		$('#button_privacy').attr('disabled',null);
	});
	$('#button_privacy').click(function(){
		$('.content').children().hide();
		$('.privacy').show();
		$('#button_privacy').attr('disabled','disabled');
		$('#button_impressum').attr('disabled',null);
		$('#button_blacklist').attr('disabled',null);
		$('#button_profile').attr('disabled',null);
	});
	$('#button_impressum').click(function(){
		$('.content').children().hide();
		$('.impressum').show();
		$('#button_impressum').attr('disabled','disabled');
		$('#button_privacy').attr('disabled',null);
		$('#button_blacklist').attr('disabled',null);
		$('#button_profile').attr('disabled',null);
	});
	$('#button_filter').click(function(){
		$('.div_filter').toggle();
	});
	$('.button_new_device').click(function(){
		
	});
	$('.button_delete').click(function(){
		$(this).parent().hide();
	});
	$('#button_new_device').click(function(){
		if($('.div_new_device').css('display')=='none'){
			window.alert('Bitte starten Sie jetzt die Edible-App auf Ihrer Vuzix!');
		}else{
			window.alert('Sie sind bereits dabei, ein neues Gerät zu koppeln');
		}
		$('.div_new_device').show();
	});
	$('#button_finished').click(function(){
		$('.div_new_device').hide();
	});
	$('#button_cancel').click(function(){
		$('.div_new_device').hide();
	});
	$('#save_profile').click(function(){
		
		var email   = $('input[id="email"]').val();
		var new_pw  = $('input[id="new"]').val();
		var confirm = $('input[id="confirm"]').val();
		var old_pw  = $('input[id="old"]').val();
		
		
	});
});