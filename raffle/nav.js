$(document).ready(function() {
	setTimeout(function() {
		$("form").removeClass("loading");
	}, 2000);	
});

function nopick() {
	$("#pickbtn").addClass("disabled");	
	$("#contactbtn").removeClass("disabled");	
	$("#booking-page").hide();
	$("#billing-page").hide();
	$("#confirmdetails-page").hide();
	$("#contact-page").fadeIn("slow"); 
	
	$(".step2").html('<i class="ticket icon"></i><div class="content"><div class="title">Seleccionar boleto</div><div class="description">Elige tu boleto manualmente</div></div>');

	return false;
}

function booking() {
	$("#contact-page").hide();
	$("#billing-page").hide();
	$("#confirmdetails-page").hide();
	$("#booking-page").fadeIn("slow");

	$(".step2").html('<i class="ticket icon"></i><div class="content"><div class="title">Seleccionar boleto</div><div class="description">Elige tu boleto manualmente</div></div>');

	return false;
}

function contact() {
	$("#contactbtn").removeClass("disabled");	
	$("#booking-page").hide();
	$("#billing-page").hide();
	$("#confirmdetails-page").hide();
	$("#contact-page").fadeIn("slow"); 	

	$(".step2").html('<i class="user icon"></i> <div class="content"> <div class="title">Detalles</div> <div class="description">Información del contacto</div> </div>');

	return false;
}

function billing() {
	$("#billingbtn").removeClass("disabled");	
	$("#booking-page").hide();
	$("#confirmdetails-page").hide();
	$("#contact-page").hide();
	$("#billing-page").fadeIn("slow");
	
	$(".step2").html('<i class="money icon"></i> <div class="content"> <div class="title">Facturación</div> <div class="description">Pago y verificación</div> </div>');

	var random = $("#ticket_random").val();
	var code, contact, email, fullname, paymethod;

	if(random){
		code = random;
		contact = $("#contact2").val();
		email = $("#email2").val();
		fullname = $("#fullname2").val();
	} else {
		code = $("#ticket_code").val();
		contact = $("#contact1").val();
		email = $("#email1").val();
		fullname = $("#fullname1").val();
	}
	$("#red-ticket").html(code);
	var price = $("#price").val();

	$.ajax({
		url: "sendmail.php",
		type: "POST",
		data: "c=" + code + "&ct=" + contact + "&e=" + email + "&fn=" + fullname + "&p=" + price,
		success: function(data) {    
			if(data=='success') {
				alert("Se ha enviado un correo con la direccion para continuar esta compra");
				// setTimeout(function() {
				// 	$("#dynamic").html("<div class='ui text container'><div class='ui positive message'> Success! Your tickets are ready. Incase you misplaced your ticket, you can <a>reprint</a> it anytime. <p align='center'><a class='ui button green' href='validate.php?ticket'> Download ticket.</a></p></div></div>");
				// }, 6000); 			 
			} else {
				alert(data);
				// setTimeout(function(){$("#dynamic").html("<div class='ui text container'><div class='ui negative message'><div class='header'>Sorry Error Processing your request..!</div><div class='ui horizontal divider'>ERROR FEEDBACK</div> "+data+"<br>If you keep seeing this error, <a onclick='location.reload()'>go back</a> or contact our <a href='#0'>Support team</a> for assistance</div></div>");},8000);              
			}
		}
	});
	
	return false;
}

function confirmdetails() {
	$("#confimationbtn").removeClass("disabled");
	$("#billing-page").hide();
	$("#booking-page").hide();
	$("#contact-page").hide();

	$(".step2").html('<i class="info icon"></i> <div class="content"> <div class="title">Confirmar detalles</div> <div class="description">Verificar detalles de la orden</div> </div>');

	var random = $("#ticket_random").val();
	var code, contact, email, fullname;

	if(random){
		code = random;
		contact = $("#contact2").val();
		email = $("#email2").val();
		fullname = $("#fullname2").val();
	} else {
		code = $("#ticket_code").val();
		contact = $("#contact1").val();
		email = $("#email1").val();
		fullname = $("#fullname1").val();
		$("#red-ticket").html(code);
	}
	
	var price = $("#price").val();
	var money_price = "$" + (price*1).toFixed(2);
	console.log(money_price);
	// Resultado
	$("#details").html('<div class="ui list"><div class="item"><div class="header">Nombre completo</div>'+fullname+'</div><div class="item"><div class="header">Número de celular</div>'+contact+'</div><div class="item"><div class="header">Correo electrónico</div>'+email+'</div><div class="item"><div class="header">Total a pagar</div>'+money_price+'</div></div>');	
	$("#confirmdetails-page").fadeIn("slow");
	return false;
}

function senddata() {
	$("#confimationbtn").removeClass("disabled");
	$("#billing-page").fadeIn("slow");
	$("#booking-page").hide();
	$("#contact-page").hide();

	$("#finishbtn").removeClass("disabled");
	// Enviando datos aquí
	// Comenzando con la recolección de todo
	destination=$("#destination").val();
	travelclass=$("#travelclass").val();
	seats=$("#seats").val();
	traveldate=$("#traveldate").val();
	// Segunda página
	fullname=$("#fullname").val();
	contact=$("#contact").val();
	gender=$("#gender").val();
	// Método de pago
	amount=$("#amount").val();
	code=$("#codebox").val();
	paymethod=$("#paymentmethod").val();
	// Borrar todos los datos
	$("#dynamic").html("<div class='ui text container'><div id='err001' class='ui success icon message'><i class='notched circle loading icon'></i><div class='content'><div class='header'>Please wait....</div><p>We are doing everything for you</p></div></div>");    
	// Ahora enviando para solicitar
	$.ajax({
		url: "request.php",
		type: "POST",
		data: "d=" + destination + "&tc=" + travelclass + "&s=" + seats + "&td=" + traveldate + "&f=" + fullname + "&c=" + contact + "&g=" + gender + "&a=" + amount + "&code=" + code + "&p=" + paymethod,
		success: function(data) {    
			if(data=='success') {
				setTimeout(function() {
					$("#dynamic").html("<div class='ui text container'><div class='ui positive message'> Success! Your tickets are ready. Incase you misplaced your ticket, you can <a>reprint</a> it anytime. <p align='center'><a class='ui button green' href='validate.php?ticket'> Download ticket.</a></p></div></div>");
				}, 6000); 			 
			} else {
				setTimeout(function(){$("#dynamic").html("<div class='ui text container'><div class='ui negative message'><div class='header'>Sorry Error Processing your request..!</div><div class='ui horizontal divider'>ERROR FEEDBACK</div> "+data+"<br>If you keep seeing this error, <a onclick='location.reload()'>go back</a> or contact our <a href='#0'>Support team</a> for assistance</div></div>");},8000);              
			}
		}
	});
	return false;	
}