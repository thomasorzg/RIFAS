<?php include 'partials/head.php' ?>
<?php 
require("../dbengine/dbconnect.php");

$error_msg = "Es necesario llenar todos los campos";

if ($_POST) {
  $fullname = $_POST['fullname'];
  $cel = $_POST['contact'];
  $email = $_POST['email'];
  $raffle_id = $_POST['nombre_rifa'];
  $code = $_POST['tickets_rifa'];

  if ($fullname && $cel && $email && $raffle_id && $code) {
    // Buscamos el ticket
    $ticketdata = mysqli_query($conn, "SELECT * FROM raffle_tickets WHERE ticket_code=$code AND raffle_id=$raffle_id");
    $ticket = $sale = mysqli_fetch_array($ticketdata);
    $ticket_id = $ticket['ticket_id'];

    $insertdata = mysqli_query($conn, "INSERT INTO ticket_buy (uuid, buyer_name, buyer_cel, buyer_email, ticket_id, paid) VALUES(uuid(), '$fullname', '$cel', '$email', $ticket_id, 1)");
    if ($insertdata) {
      $updatedata = mysqli_query($conn, "UPDATE raffle_tickets SET ticket_status=1 WHERE ticket_id=$ticket_id");
    }
  } else {
    echo $error_msg;
  }
}

?>
<body>
  <style>
    #loading {
      z-index: 1001;
    }
  </style>
  <div class="ui disabled dimmer" id="loading">
    <div class="ui loader"></div>
  </div>
  <div class="ui modal" id="modal-crear">
    <i class="close icon"></i>
    <div class="header">
      Nuevo boleto
    </div>
    <div class="content">
      <div class="description">
        <form action="" id="form-comprar-boleto" method="post" class="ui form">
          <input type="hidden" value="comprar_boleto">
          <div class="ui equal width form">
            <div class="fields">
              <div class="field">
                <label>Nombre de la rifa</label>
                <select name="nombre_rifa" id="nombre_rifa">
                  <option value="">Selecciona una rifa</option>
                  <?php 
                  $data = mysqli_query($conn, "SELECT raffle_id, raffle_name FROM raffles");
                  if(($data) && (mysqli_num_rows($data) > 0)) {
                  //getting data and generating a row
                    while($row=mysqli_fetch_assoc($data)) {
                      echo("<option value='".$row['raffle_id']."'>".$row['raffle_name']."</option>");
                    }   
                  }
                  ?>
                </select>
              </div>
              <div class="field">
                <label>Numero de boleto</label>
                <select name="tickets_rifa" id="tickets_rifa">
                </select>
              </div>
            </div>
            <div class="fields">
              <div class="field">
                <label>Nombre del comprador</label>
                <input placeholder="Juan Perez" type="text" id="fullname" name="fullname" required="">
              </div>
              <div class="field">
                <label>Celular del comprador</label>
                <input placeholder="A 10 digitos" type="text" id="contact" name="contact" required="">
              </div>
              <div class="field">
                <label>Correo electrónico del comprador</label>
                <input placeholder="micorreo@gmail.com" type="email" id="email" name="email" required="">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="actions">
      <div class="ui black deny button">
        Cancelar
      </div>
      <button class="ui positive right labeled icon button" id="comprar-boleto">
        Comprar
        <i class="checkmark icon"></i>
      </button>
    </div>
  </div>
  <?php include 'partials/top.php' ?>
  <div class="ui grid">
    <div class="row">
      <?php include 'partials/menu.php' ?>

      <div class="column" id="content" style="display: none">
        
        <div class="ui grid">
            <div class="six wide column">
              <h1 class="ui huge header">Todas las compras de boletos de rifa</h1>
            </div>
            <div class="six wide column">
              <button class="ui green right labeled icon button" id="nueva-rifa">
                <i class="right plus icon"></i>
                Comprar boleto
              </button>
            </div>
          </div>
          <div class="ui grid">
            <div class="row"></div>
              <div class="ui horizontal divider"> Estos son los detalles de las rifas</div>      
              <?php 
              // Eliminar:
              if(isset($_GET['deleted'])) {
                $or = $_GET['deleted'];
                if(mysqli_query($conn, "DELETE FROM booking_details WHERE order_ref = '$or'")) {
                  echo ("<div class='ui positive message' style='margin:auto'>Orden eliminada/rechazada correctamente #$or.</div>");  
                } else {
                  echo ("<div class='ui negative message' style='margin:auto'>¡Error! No se pudo procesar la solicitud de rechazo de la orden #$or.</div>");  
                } 
              }
              ?>
              <div class="row">
                <table class="ui single line striped selectable center aligned  table">
                  <thead><tr><th>RIFA</th><th>NOMBRE DEL CLIENTE</th><th>CELULAR</th><th>CORREO DEL CLIENTE</th><th>MONTO</th><th>BOLETO</th></tr></thead>
                  <tbody>
                    <?php
                    require("../dbengine/dbconnect.php");
                    if(isset($_GET['search'])) {
                      $search = $_GET['search'];
                      $data = mysqli_query($conn, "SELECT tb.*, r.raffle_price, r.raffle_name, rt.ticket_code FROM ticket_buy tb LEFT JOIN raffle_tickets rt ON tb.ticket_id=rt.ticket_id LEFT JOIN raffles r ON r.raffle_id=rt.raffle_id WHERE ticket_code LIKE '%$search%' or buyer_name LIKE '%$search%' or buyer_email LIKE '%$search%' or buyer_cel LIKE '%$search%'");
                    } else {
                      $data = mysqli_query($conn, "SELECT tb.*, r.raffle_price, r.raffle_name, rt.ticket_code FROM ticket_buy tb LEFT JOIN raffle_tickets rt ON tb.ticket_id=rt.ticket_id LEFT JOIN raffles r ON r.raffle_id=rt.raffle_id");
                    }
                    if(($data) && (mysqli_num_rows($data) > 0)) {
                    //getting data and generating a row
                      while($row=mysqli_fetch_assoc($data)) {
                        echo("<tr><td>".$row['raffle_name']."</td><td>".$row['buyer_name']."</td><td>".$row['buyer_cel']."</td><td>".$row['buyer_email']."</td><td>"."$".sprintf('%0.2f', ($row['raffle_price']/100))."</td><td>".$row['ticket_code']."</td></tr>");
                      }   
                    } else {
                      echo "<tr><td colspan='5'>¡No se encontraron coincidencias de registros!</td></tr>";  
                    }
                    mysqli_close($conn);
                    ?> 
                  </tbody>
                </table>
              </div>
            </div>  
        </div>
    </div>
  </div>
</body>
</html>