<?php
session_start();

function send_correo($url, $to)
{
    $subject = 'Rifa!';
    $body = 'Estas a punto de finalizar tu compra, puedes continuar tu proceso aquí: <br><b>'.$url.'</b>';
    $headers = 'From: Rifas Online'."\r\n";
    $headers .= 'Reply-To: from@example.com'."\r\n";
    $headers .= 'Return-Path: from@example.com'."\r\n";
    $headers .= 'X-Mailer: PHP5'."\n";
    $headers .= 'MIME-Version: 1.0'."\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
    $mailed = mail($to, $subject, $body, $headers);

    if($mailed){
        return true;
    }
    return false;
}
if($_POST) {
    // Comenzando con la recolección de todo
	$raffle_id = $_SESSION['raffle_id'];    
	$code = $_POST['c'];
	$cel = $_POST['ct'];
	$email = $_POST['e'];
	$price = $_POST['p'];
	$fullname = $_POST['fn'];

	// PROCESAMIENTO DE RESERVA DE BOLETOS
	$message = "";
	require("../dbengine/dbconnect.php");

    // Buscamos el ticket
    $ticketdata = mysqli_query($conn, "SELECT * FROM raffle_tickets WHERE ticket_code=$code AND raffle_id=$raffle_id");
    $ticket = $sale = mysqli_fetch_array($ticketdata);
    $ticket_id = $ticket['ticket_id'];

    $insertdata = mysqli_query($conn, "INSERT INTO ticket_buy (uuid, buyer_name, buyer_cel, buyer_email, ticket_id) VALUES(uuid(), '$fullname', '$cel', '$email', $ticket_id)");
    // Si pudimos insertar el ticket buy
    if($insertdata) {
        $updatedata = mysqli_query($conn, "UPDATE raffle_tickets SET ticket_status=1 WHERE ticket_id=$ticket_id");
        $saledata = mysqli_query($conn, "SELECT uuid FROM ticket_buy WHERE ticket_id=$ticket_id LIMIT 1");
        $sale = mysqli_fetch_array($saledata);
        $uuid = $sale['uuid'];
        $_SESSION['sale_uuid'] = $uuid;
        $url = $_SERVER['SERVER_NAME']."/raffle/raffle.php?ticket_sale=".$uuid;
        $sended = send_correo($url, $email);

        if ($sended) {
            $message = 'success';
        }else{
            $message = 'Error al enviar notificacion por correo';
        }
    } else { 
        $message = "No se pudo realizar la venta";
    }    
	// Finalmente
	echo $message;    
}
?>