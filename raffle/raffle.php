<?php
session_start();
require("../dbengine/dbconnect.php");

$has_uuid = false;
$valid_uuid = false;
$paid_uuid = false;

$imagedata = mysqli_query($conn, "SELECT * FROM banners ORDER BY banner_id DESC LIMIT 1");
$image = mysqli_fetch_array($imagedata);
$image_path = $image['banner_path'];
if (array_key_exists('ticket_sale', $_GET)) {
  $has_uuid = true;
  $data = mysqli_query($conn, "SELECT tb.*, rt.ticket_code, r.raffle_date AS fecha_sorteo, r.raffle_name, r.raffle_price FROM ticket_buy tb LEFT JOIN raffle_tickets rt ON tb.ticket_id=rt.ticket_id LEFT JOIN raffles r ON r.raffle_id=rt.raffle_id WHERE tb.uuid='$_GET[ticket_sale]' LIMIT 1");

  if (($data) && (mysqli_num_rows($data) > 0)) {
    $valid_uuid = true;
    $sale = mysqli_fetch_array($data);

    setlocale(LC_ALL,"es_ES");
    $date = DateTime::createFromFormat("Y-m-d", $sale['fecha_sorteo']);
    
    $_SESSION['BOLETO']['ticket_code'] = $sale['ticket_code'];
    $_SESSION['BOLETO']['fecha_sorteo'] = strftime("%A, %d de %B del %Y",$date->getTimestamp());
    $_SESSION['BOLETO']['raffle_price'] = $sale['raffle_price'];
    
    if ($sale['paid'] == 1) {
      $paid_uuid = true;
    }
  }
} else {
  if(!array_key_exists('r', $_GET)){
    header("Location: /raffle");
  }
  $raffle_id = $_GET['r'];
  $data = mysqli_query($conn, "SELECT * FROM raffles WHERE raffle_id=$raffle_id");
  $raffle = mysqli_fetch_array($data);
  if ($raffle) {
    $_SESSION['raffle_id'] = $raffle_id;
    $_SESSION['raffle_name'] = $raffle['raffle_name'];
    $_SESSION['raffle_price'] = $raffle['raffle_price']/100;
  }
  if (array_key_exists('want', $_GET) && !array_key_exists('BOLETO', $_SESSION)) {
    $raffle_id = $_GET['r'];
    $data = mysqli_query($conn, "SELECT rt.*, r.raffle_price FROM raffle_tickets rt
    LEFT JOIN raffles r ON rt.raffle_id=r.raffle_id WHERE rt.ticket_status = 0 AND r.raffle_id=$raffle_id ORDER BY RAND() LIMIT 1");
    if(($data) && (mysqli_num_rows($data) > 0)) {
      $boleto = mysqli_fetch_array($data, MYSQLI_ASSOC);
      $_SESSION['BOLETO'] = $boleto;
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>...</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
    <link href="semantic/datepicker.css" rel="stylesheet" type="text/css">
    <!-- JavaScript -->
    <script src="semantic/jquery.min.js"> </script>
    <script src="semantic/semantic.min.js"></script>
    <script src="semantic/datepicker.js"></script>
    <script src="nav.js"></script>
    <!-- Style CSS -->
    <style type="text/css">
      body {
        background-color: #f1f1f1;
      }
      a {
        cursor: pointer;	
      }
    </style>
</head>
<body>
  <style>
  .header-banner {
    height: 10em;
    margin-top: 45px;
    background-image: url(<?php print $image_path; ?>);
    background-repeat: no-repeat;
    background-position: center;
  }
  #dynamic {
    width: 100%;
  }
  @media (max-width: 800px){
    .mobile-hidden {
      display: none !important;
    }
    .mobile-show {
      display: block !important;
    } 
  }
  @media (min-width: 800px){
    .mobile-show {
      display: none !important;
    } 
  }
  </style>
  <div class="ui inverted huge borderless fixed fluid menu">
  <center><p class="header item"> SISTEMA DE VENTA DE BOLETOS EN LÍNEA &mdash; DESARROLLADO POR VIROCKET MARKETING</p></center>
  </div>
 

  <?php if(!$has_uuid) { ?>
  <div class="ui fluid container center aligned" style="cursor:pointer;margin-top:40px;">
    <div class="ui unstackable tiny steps mobile-show" style="width:100%">
      <div class="step step2">
        
      </div>
    </div>
    <div class="ui unstackable tiny steps mobile-hidden">
      <div class="step" onclick="booking()" id="pickbtn">
        <i class="ticket icon"></i>
        <div class="content">
          <div class="title">SELECCIONAR BOLETO</div>
        </div>
      </div>
      <div class="step disabled" onclick="contact()" id="contactbtn">
        <i class="user icon"></i>
        <div class="content">
          <div class="title">DETALLES DE CONTACTO</div>
        </div>
      </div>
      <div class="disabled step" onclick="confirmdetails()" id="confimationbtn">
        <i class="info icon"></i>
        <div class="content">
          <div class="title">CONFIRMACIÓN</div>
        </div>
      </div>
      <div class="disabled step" id="billingbtn">
        <i class="money icon"></i>
        <div class="content">
          <div class="title">PAGO</div>
        </div>
      </div>
      <div class="disabled step" id="finishbtn">
        <i class="info icon"></i>
        <div class="content">
          <div class="title">TICKET DEL BOLETO</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Espacio -->
  <br>
  <!-- /espacio -->

  <div id="dynamic">
    <div class="ui container text" id="booking-page">
      <div class="ui attached message">
        <div class="ui one column stackable center aligned page grid">
          <div class="column fourteen wide">
          <h1><i class="ticket icon"></i> Escoge tu boleto</h1>
          </div>
        </div>
        <div class="header">
          <form class="ui form">
            <div class="field">
            <select name="ticket_code" class="ui dropdown" id="ticket_code" style="margin-top: 30px;">
              <?php 
                $raffle_id = $_GET['r'];
                $data2 = mysqli_query($conn, "SELECT * FROM raffle_tickets WHERE raffle_id = $raffle_id AND ticket_status = 0 ORDER BY ticket_id LIMIT 100");
        
                while($row=mysqli_fetch_assoc($data2)){
              ?>
                <option value="<?php print $row['ticket_code'] ?>"><?php print $row['ticket_code']  ?></option>
              <?php } ?>
            </select>
            </div>
          </form>
          <a href='index.php'>Cancelar orden</a>
        </div>
      </div>
      <form class="ui form attached fluid loading segment" onsubmit="return confirmdetails(this)">
        <div class="field">
          <label>Por favor, ingrese su nombre completo:</label>
          <input placeholder="Ejemplo: Juan Pérez López" type="text" id="fullname1" required>
        </div>
        <div class="field">
          <label>Por favor, ingrese su número de celular:</label>
          <input placeholder="Son 10 dígitos, ejemplo: 6441234567" type="text" id="contact1" required>
        </div>
        <div class="field">
          <label>Dirección de correo electrónico</label>
          <input placeholder="micorreo@gmail.com" type="email" id="email1" required>
        </div>
        <div style="text-align:center">
          <div><label>Asegúrese de que todos los detalles se hayan llenado correctamente</label></div>
          <button class="ui green submit button">Enviar detalles</button>
        </div>
      </form>
    </div>
    <!---->
    <div class="ui container text" id="contact-page" style="display:none">
      <div class="ui attached message">
        <div class="header"><center>&mdash; INGRESE SU INFORMACIÓN DE CONTACTO &mdash;</center></div>
        <hr>
        <center><div class="header">🎟️ Boleto seleccionado: <span style="color:red;font-size:15px">#<?php echo $_SESSION['BOLETO']['ticket_code']?></span> | <a href='index.php'> CANCELAR COMPRA ❌</a></div>
        <p>POR FAVOR A COMPLETE LOS CAMPOS SOLICITADOS REQUERIDOS</p></center>
      </div>
      <form class="ui form attached fluid loading segment" onsubmit="return confirmdetails(this)">
          <?php
            if (array_key_exists('want', $_GET)) { ?>
              <input type="hidden" id="ticket_random" value="<?php print $_SESSION['BOLETO']['ticket_code']; ?>">
          <?php } ?>
        <div class="field">
          <label>Por favor, ingrese su nombre completo:</label>
          <input placeholder="Ejemplo: Juan Pérez López" type="text" id="fullname2" required>
        </div>
        <div class="field">
          <label>Por favor, ingrese su número de celular:</label>
          <input placeholder="Son 10 dígitos, ejemplo: 6441234567" type="text" id="contact2" required>
        </div>
        <div class="field">
          <label>¿Cuál es su correo electrónico?</label>
          <input placeholder="Ejemplo: micorreo@gmail.com" type="email" id="email2" required>
        </div>
        <div style="text-align:center">
          <div><label>⚠️ Asegúrese de que todos los detalles se hayan llenado correctamente ⚠️</label></div>
          <button class="ui green submit button">CONTINUAR</button>
        </div>
      </form>
    </div>
    <!---->
    <div class="ui container text" id="billing-page" style="display:none">
      <div class="ui attached message">
        <center><div class="header">&mdash; INFORMACIÓN DE PAGO&mdash;</div></center>
        <hr>
        <center><div class="header">Referencia de boleto solicitado: <span style="color:red;font-size:15px">#<?php echo $_SESSION['BOLETO']['ticket_code']?></span> | <a href='index.php'>CANCELAR COMPRA ❌</a></span> </div></center>
      </div>
      <form class="ui form attached fluid loading segment" action="request.php" method="post">
        <div class="field"> 
          <label>Seleccione su método de pago, por favor: </label>  
          <div class="field">
            <input type="hidden" id="price" value="<?php echo $_SESSION['raffle_price']; ?>">
            <select required id="paymentmethod">
              <option value="" selected disabled>Forma de pago</option>
              <?php 
                $payments = mysqli_query($conn, "SELECT * FROM payments");
        
                while($row=mysqli_fetch_assoc($payments)){
              ?>
                <option value="<?php print $row['payment_id'] ?>"><?php print $row['payment_name']  ?></option>
              <?php } ?>
            </select>
          </div>
        </div> 
        <div style="text-align:center">
          <button class="ui green submit button">REALIZAR PAGO</button>
        </div>
      </form>
    </div>

    <div class="ui text container" id ="confirmdetails-page" style="display:none">
      <div class="ui positive message">
        <center><b>Por favor, antés de continuar vuelva a verificar los siguientes detalles que proporcionó...</b></center>
        <hr>
        <div class="ui one column stackable center aligned page grid">
          <div class="column fourteen wide">
            <h1>&mdash; <?php print $_SESSION['raffle_name'] ?> &mdash;</h1>
          </div>
        </div>
        <div class="ui one column stackable center aligned page grid">
          <div class="column fourteen wide">
            <h1 style="margin-top: 15px;"> BOLETO: <span id="red-ticket" style="color: red;">#<?php echo $_SESSION['BOLETO']['ticket_code']?></span></h1>
          </div>
        </div>
        <br>
        <div class="ui horizontal divider">Los detalles proporcionados</div>
        <div id="details">
          <div class="ui list">
            <div class="item">
              <div class="header">Nombre completo: </div>
              
            </div>
            <div class="item">
              <div class="header">Número de celular: </div>
              
            </div>
            <div class="item">
              <div class="header">Correo electrónico: </div>
              
            </div>
            <div class="item">
              <div class="header">Total a pagar: </div>
              $0.00 MXN
            </div>
          </div>
        </div>
        <div class="ui horizontal divider">¿TODO ESTÁ BIEN?</div>
        <div class="ui fluid container center aligned">
          <a class="ui button green" onclick="billing()">SI</a>
        </div>
      </div>
    </div>
  </div>
  <?php if(array_key_exists('want', $_GET)) { ?>
    <script>
      nopick();
    </script>
  <?php } // End If array_key_exists('want', $_GET)) ?>
  <?php } else { ?>
    <?php if($valid_uuid) { ?>
      <style>
        @media print
        {    
          .no-print, .no-print *
          {
              display: none !important;
          }
        }
      </style>

      <div class="ui fluid container center aligned no-print mobile-hidden" style="cursor:pointer;margin-top:40px">
        <div class="ui unstackable tiny steps mobile-show" style="width:100%">
          <div class="step step2">
            
          </div>
        </div>
        <div class="ui unstackable tiny steps mobile-hidden">
          <div class="step disabled" id="pickbtn">
            <i class="ticket icon"></i>
            <div class="content">
              <div class="title">Seleccionar boleto</div>
              <div class="description">Elige tu boleto manualmente</div>
            </div>
          </div>
          <div class="step disabled" id="contactbtn">
            <i class="user icon"></i>
            <div class="content">
              <div class="title">Detalles</div>
              <div class="description">Información del contacto</div>
            </div>
          </div>
          <div class="disabled step" id="confimationbtn">
            <i class="info icon"></i>
            <div class="content">
              <div class="title">Confirmar detalles</div>
              <div class="description">Verificar detalles de la orden</div>
            </div>
          </div>
          <div class="<?php echo $paid_uuid ? 'disabled':'';  ?> step" id="billingbtn">
            <i class="money icon"></i>
            <div class="content">
              <div class="title">Facturación</div>
              <div class="description">Pago y verificación</div>
            </div>
          </div>
          <div class="<?php echo $paid_uuid ? '':'disabled';  ?> step" id="finishbtn">
            <i class="info icon"></i>
            <div class="content">
              <div class="title">Terminar e imprimir</div>
              <div class="description">Ticket de impresión</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Espacio -->
      <br>
      <!-- /espacio -->
      <?php if($paid_uuid) { ?>
        <div class="ui text container">
          <div class="ui positive message">
            <div class="ui one column stackable center aligned page grid">
              <div class="column fourteen wide">
                <h1 style="margin-top: 15px;"> Boleto: <span id="red-ticket" style="color: red;"><?php print $_SESSION['BOLETO']['ticket_code'] ?></span></h1>
              </div>
            </div>
            <br>
            <div class="ui one column stackable center aligned page grid">
              <div class="column fourteen wide">
                <h1><?php print $_SESSION['raffle_name'] ?></h1>
              </div>
            </div>
            <br>
            <div class="ui horizontal divider">Detalles de su compra</div>
            <div id="details">
              <div class="ui list">
                <div class="item">
                  <div class="header">Fecha de sorteo: <span style="font-weight:200"><?php print $_SESSION['BOLETO']['fecha_sorteo']; ?></span> </div>
                </div>
                <div class="item">
                  <div class="header">Costo de boleto: <span style="font-weight:200"><?php print "$".number_format(($_SESSION['BOLETO']['raffle_price']/100), 2, '.', ''); ?></span> </div>
                </div>
              </div>
            </div>
            <div class="ui horizontal divider no-print">Imprimir Ticket</div>
            <div class="ui fluid container center aligned">
              <a class="ui button green no-print" onclick="window.print();">SÍ | Imprimir</a>
            </div>
          </div>
        </div>
      <?php } else { ?>
        <div id="dynamic">
          <div class="ui container text" id="billing-page">
            <div class="ui attached message">
              <center><div class="header">INFORMACIÓN DE PAGO</div></center>
              <hr>
              <center><div class="header">Boleto #: <span style="color:red;font-size:15px"><?php echo $_SESSION['BOLETO']['ticket_code']?> <a href='index.php'>Cancelar</a></span> </div> 
              <p>Ingrese Detalles de pago para continuar</p>
            </div></center>
            <form class="ui form attached fluid loading segment" action="request.php" method="post">
              <div class="field"> 
                <label>Método de pago</label>  
                <div class="field">
                  <input type="hidden" id="price" value="<?php echo $_SESSION['raffle_price']; ?>">
                  <select required id="paymentmethod">
                    <option value="" selected disabled>Forma de pago</option>
                    <?php 
                      $payments = mysqli_query($conn, "SELECT * FROM payments");
              
                      while($row=mysqli_fetch_assoc($payments)){
                    ?>
                      <option value="<?php print $row['payment_id'] ?>"><?php print $row['payment_name']  ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div> 
              <div style="text-align:center">
                <button class="ui green submit button">Proceder</button>
              </div>
            </form>
          </div>
        </div>
      <?php } ?>
      <?php if(array_key_exists('want', $_GET)) { ?>
        <script>
          nopick();
        </script>
      <?php } // End If array_key_exists('want', $_GET)) ?>
    <?php } else { ?>
      <div class="ui text container" style="margin-top: 4em;">
        <div class="ui red message">
          <div class="ui one column stackable center aligned page grid">
            <div class="column fourteen wide">
              <h1 style="margin-top: 15px;"> No existe compra con el código proporcionado</h1>
            </div>
          </div>
          <br>
          <div class="ui fluid container center aligned">
            <a class="ui button green" href="/raffle">Ir a inicio</a>
          </div>
        </div>
      </div>
    <?php } //End If Else valid_uuid ?>
  <?php } //End If Else !has_uuid ?>

</body>
</html>