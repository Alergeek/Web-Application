$(document).ready(function(){
	$('#button_blacklist').click(function(e){
	
		e.preventDefault();
		
		$('.div_new_devices').hide();
		$('.content').children().hide();
		$('.blacklist').show();
		$('#button_blacklist').attr('disabled','disabled');
		$('#button_profile').attr('disabled',null);
		$('#button_impressum').attr('disabled',null);
		$('#button_privacy').attr('disabled',null);
	});
	
	$('#button_profile').click(function(e){
	
		e.preventDefault();
		
		var device;
		var devices = [	{token:"blabla", until:"blabla", name:"Vuzix1", email:"bla@bla.bla"},
						{token:"blabla", until:"blabla", name:"Vuzix2", email:"bla@bla.bla"},
						{token:"blabla", until:"blabla", name:"Vuzix3", email:"bla@bla.bla"}];
		
		for(device in devices){
			$('.table_devices').append('<tr class="device"><th>'+devices[device].name+'</th><td><button class="button_delete">X</button></td><tr>');
		}
		
		// User.getDevices().then(function(result){
			// console.log(result);
		// }).catch(function( err ) {
			// if ( err.status >= 400 ) {
				// window.alert("Fehler beim laden der Geräte!");
			// }
		// });
		$('.content').children().hide();
		$('.profile').show();
		$('#button_profile').attr('disabled','disabled');
		$('#button_blacklist').attr('disabled',null);
		$('#button_impressum').attr('disabled',null);
		$('#button_privacy').attr('disabled',null);
	});
	
	$('#button_privacy').click(function(e){
	
		e.preventDefault();
	
		$('.content').children().hide();
		$('.privacy').show();
		$('#button_privacy').attr('disabled','disabled');
		$('#button_impressum').attr('disabled',null);
		$('#button_blacklist').attr('disabled',null);
		$('#button_profile').attr('disabled',null);
	});
	
	$('#button_impressum').click(function(e){
	
		e.preventDefault();
		
		$('.content').children().hide();
		$('.impressum').show();
		$('#button_impressum').attr('disabled','disabled');
		$('#button_privacy').attr('disabled',null);
		$('#button_blacklist').attr('disabled',null);
		$('#button_profile').attr('disabled',null);
	});
	
	$('#button_filter').click(function(e){
	
		e.preventDefault();
	
		$('.div_filter').toggle();
	});
	
	$('.button_delete').click(function(e){
	
		e.preventDefault();
	
		$(this).parent().remove();
	});
	
	$('#button_new_device').click(function(e){
	
		e.preventDefault();
		
		var bcType = "ean13";
		var getBarcode = "4002627400177";
	
		if($('.div_new_device').css('display')=='none'){
			window.alert('Bitte starten Sie jetzt die Edible-App auf Ihrer Vuzix!');
		}else{
			window.alert('Sie sind bereits dabei, ein neues Gerät zu koppeln');
		}
		$('#div_barcode').barcode(getBarcode, bcType, {barWidth:2, barHeight:100});
		$('.div_new_device').show();

	});
	
	$('#button_finished').click(function(e){
	
		e.preventDefault();
		
		$('.div_new_device').hide();
		$('#div_barode').empty();
	});
	
	$('#button_cancel').click(function(e){
	
		e.preventDefault();
	
		$('.div_new_device').hide();
		$('#div_barode').empty();
	});
	
	$('#save_profile').click(function(e){
		
		e.preventDefault();
		
		var email   = $('input[id="email"]').val();
		var new_pw  = $('input[id="new"]').val();
		var confirm = $('input[id="confirm"]').val();
		var old_pw  = $('input[id="old"]').val();
		
		if(old_pw==""){
			window.alert("Bitte geben Sie ihr altes Passwort an!");
			return;
		}
		if(email=="" && new_pw=="" && confirm==""){
			window.alert("Sie müssen erst Daten eingeben, um diese abspeichern zu können!");
			return;
		}
		if(email != ""){
			User.changeEmail(email, old_pw).then(function(result) {
				console.log(result);
				window.alert("Email wurde geändert!");
			}).catch( function( err ) {
				if ( err.status >= 400 ) {
					window.alert("Fehler beim ändern der Email!");
				}
			});
		}
		if(new_pw!=""){
			if(new_pw!=confirm){
				window.alert("Das neue Passwort stimmt nicht mit der Bestätigung überein!");
			}else{
				User.changePassword(old_pw,new_pw).then(function(result) {
					console.log(result);
					window.alert("Passwort wurde geändert!");
				}).catch( function( err ) {
					if ( err.status >= 400 ) {
						window.alert("Fehler beim ändern des Passworts!");
					}
				});
			}
		}
	});
});