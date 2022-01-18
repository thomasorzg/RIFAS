$(document).ready(function() {
	$('#nombre_rifa').on('change', function () {
		$('#loading').removeClass('disabled').addClass('active');
		var tickets = "";
		var id = $(this).val();
		$.get("ajax.php", {'type':'get_tickets', 'raffle_id': id}, function (res) {
			res.forEach(function (el) {
				var opt = '<option value="'+el.ticket_code+'">'+el.ticket_code+'</option>';
				tickets += opt;
			})
			$('#tickets_rifa').html(tickets);
			$('#loading').removeClass('active').addClass('disabled');
		});
	});
	$('#comprar-boleto').on('click', function () {
		$('#form-comprar-boleto').submit();
	})
	$("#content").fadeIn(800);
	setTimeout(function() {
		$("form").removeClass("loading");
	}, 2000);

	// Pagina raffles
	$("#nueva-rifa").on('click', function () {
		$('#modal-crear').modal('show');
	});
	$("#crear-rifa").on('click', function () {
		$('#form-crear').submit();
	});
	$(".editar-rifa").on('click', function () {
		var id = $(this).data("id");
		$.get("ajax.php", {'type':'get_raffle', 'raffle_id': id}, function (res) {
			console.log(res);
			$('#edit-raffle_id').val(res.raffle_id);
			$('#edit-raffle_name').val(res.raffle_name);
			$('#edit-raffle_description').val(res.raffle_description);
			$('#edit-raffle_prize').val(res.raffle_prize);
			$('#edit-raffle_price').val(res.raffle_price);
			$('#edit-raffle_sellqty').val(res.raffle_sellqty);
			$('#edit-control_qty').val(res.raffle_sellqty);
			var real_id = res.real_id;
			console.log(real_id)
			$('#edit-real_id option[value='+real_id+']').prop('selected', 'selected').change();
			$('#edit-control_real').val(real_id);
			$('#edit-raffle_buyqty').val(res.raffle_buyqty);
			$('#edit-raffle_date').prop('readonly',false).val(res.raffle_date);
			$('#edit-raffle_end').prop('readonly',false).val(res.raffle_end);

			$('#modal-editar').modal('show');
		});
	});
	$(".borrar-rifa").on('click', function () {
		var id = $(this).data("id");
		$('#borrar-rifa').data('id', id);
		$('#modal-borrar').modal('show');
	});
	$("#guardar-rifa").on('click', function () {
		$('#form-editar').submit();
	});
	$("#borrar-rifa").on('click', function () {
		var id = $(this).data("id");
		$('#form-borrar').html('<input type="hidden" name="raffle_id" value="'+id+'"><input type="hidden" name="submit_type" value="borrar">');
		$('#form-borrar').submit();
	});

	// Pagina payments
	$("#nueva-metodo").on('click', function () {
		$('#modal-crear').modal('show');
	});
	$("#crear-metodo").on('click', function () {
		$('#form-crear').submit();
	});
	$(".editar-metodo").on('click', function () {
		var id = $(this).data("id");
		$.get("ajax.php", {'type':'get_payment', 'payment_id': id}, function (res) {
			console.log(res);
			$('#edit-payment_id').val(res.payment_id);
			$('#edit-payment_name').val(res.payment_name);
			$('#edit-payment_email').val(res.payment_email);

			$('#modal-editar').modal('show');
		});
	});
	$(".borrar-metodo").on('click', function () {
		var id = $(this).data("id");
		$('#borrar-metodo').data('id', id);
		$('#modal-borrar').modal('show');
	});
	$("#guardar-metodo").on('click', function () {
		$('#form-editar').submit();
	});
	$("#borrar-metodo").on('click', function () {
		var id = $(this).data("id");
		$('#form-borrar').html('<input type="hidden" name="payment_id" value="'+id+'"><input type="hidden" name="submit_type" value="borrar">');
		$('#form-borrar').submit();
	});
});