<?php
  require("../dbengine/dbconnect.php");

  function generateRandomString($length = 3) {
      $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }
  function dbdate($dateString) 
  {
    $myDateTime = DateTime::createFromFormat('d-m-Y', $dateString);
    $newDateString = $myDateTime->format('Y-m-d');
    return $newDateString;
  }
  function formdate($dateString) 
    {
        $myDateTime = DateTime::createFromFormat('Y-m-d', $dateString);
        $newDateString = $myDateTime->format('d-m-Y');
        return $newDateString;
    }
  function validate_form($pajar, $agujas)
  {
    if (count(array_intersect_key(array_flip($agujas), $pajar)) === count($agujas)) {
      return true;
    }
    return false;
  }

  if (array_key_exists('submit_type', $_POST)) {
    if ($_POST['submit_type'] == "borrar") {
      $query = "DELETE FROM raffles WHERE raffle_id=$_POST[raffle_id]";
      
      $deletedata = mysqli_query($conn, $query);    
      if(!$deletedata) {
        $message = "Error al borrar!";
      }
    }
  }
  $formKeys = ['raffle_name', 'raffle_prize', 'raffle_price', 'raffle_sellqty', 'raffle_buyqty', 'raffle_date', 'raffle_end', 'real_id'];
  
  if (validate_form($_POST, $formKeys)) {
  	// If create
    if (!array_key_exists('raffle_id', $_POST)) {
      $raffle_date = dbdate($_POST['raffle_date']);
      $raffle_end = dbdate($_POST['raffle_end']);
      $raffle_price = $_POST['raffle_price'] * 100;

      do {
        $code = generateRandomString();
        $data = mysqli_query($conn, "SELECT * FROM raffles WHERE raffle_code = '$code'");

      } while (mysqli_num_rows($data) > 0);
  
      $query = "INSERT into raffles (raffle_name, raffle_prize, raffle_date, raffle_price, raffle_sellqty, raffle_buyqty, raffle_end, raffle_code, real_id, raffle_description) VALUES('$_POST[raffle_name]', '$_POST[raffle_prize]', '$raffle_date', '$raffle_price', '$_POST[raffle_sellqty]', '$_POST[raffle_buyqty]', '$raffle_end', '$code', '$_POST[real_id]', '$_POST[raffle_description]')";
      
      $insertdata = mysqli_query($conn, $query);    
      if($insertdata) {
        $raffle_id = mysqli_insert_id($conn);

        // Trae la cantidad de cifras
        $query = "SELECT real_digits FROM raffle_tickets WHERE real_id = $_POST[real_id] LIMIT 1";
        $getcifra = mysqli_query($conn, $query);
        $cifra = mysqli_fetch_assoc($getcifra);

        if ($cifra['real_digits'] == 2) {
        	$inicio = 0;
	        $fin = $_POST['raffle_sellqty']-1;
        } else {
        	$inicio = 1;
	        $fin = $_POST['raffle_sellqty'];
        }

        $padqty = $cifra['real_digits'];
        
        for ($i=$inicio; $i < $fin; $i++) {
          $ticket_code = str_pad($i, $padqty, "0", STR_PAD_LEFT);
          $query2 = "INSERT into raffle_tickets (raffle_id, ticket_code) VALUES($raffle_id, '$ticket_code')";
          $insertdata2 = mysqli_query($conn, $query2);
        }
      } else { 
        $message = "Error al guardar!";
      }
    } else {
    	// If update
      $raffle_date = dbdate($_POST[raffle_date]);
      $raffle_end = dbdate($_POST[raffle_end]);
      $raffle_price = $_POST[raffle_price] * 100;
  
      $query2 = "UPDATE raffles SET raffle_name='$_POST[raffle_name]', raffle_prize='$_POST[raffle_prize]', raffle_date='$raffle_date', raffle_price='$raffle_price', raffle_sellqty=$_POST[raffle_sellqty], raffle_buyqty=$_POST[raffle_buyqty], raffle_end='$raffle_end', real_id=$_POST[real_id], raffle_description='$_POST[raffle_description]' WHERE raffle_id=$_POST[raffle_id]";

      $updatedata = mysqli_query($conn, $query2); 

      if($updatedata) {
        if ($_POST[raffle_sellqty] != $_POST[control_qty] || $_POST[real_id] != $_POST[control_real]) {
        	$raffle_id = $_POST[raffle_id];

          $query = "DELETE FROM raffle_tickets WHERE raffle_id=$raffle_id";
          $deletedata = mysqli_query($conn, $query);

          // Trae la cantidad de cifras
	        $query = "SELECT real_digits FROM raffle_tickets WHERE real_id = $_POST[real_id] LIMIT 1";
	        $getcifra = mysqli_query($conn, $query);
	        $cifra = mysqli_fetch_assoc($getcifra);

	        if ($cifra['real_digits'] == 2) {
	        	$inicio = 0;
		        $fin = $_POST['raffle_sellqty'];
	        } else {
	        	$inicio = 1;
		        $fin = $_POST['raffle_sellqty'];
	        }

	        $padqty = $cifra['real_digits'];
          for ($i=$inicio; $i < $fin; $i++) {
              $ticket_code = str_pad($i, $padqty, "0", STR_PAD_LEFT);
              $query2 = "INSERT into raffle_tickets (raffle_id, ticket_code) VALUES($raffle_id, '$ticket_code')";
              $insertdata2 = mysqli_query($conn, $query2);
          }
        }
        $message = "success";
      } else { 
        $message = "Error al guardar!";
      }
    }
  }
?>
<?php include 'partials/head.php' ?>
<body>
	<!-- Modal crear -->
  <div class="ui modal" id="modal-crear">
    <i class="close icon"></i>
    <div class="header">
      Nueva rifa
    </div>
    <div class="content">
      <div class="description">
        <form action="" id="form-crear" method="post" class="ui form">
          <div class="ui equal width form">
            <div class="fields">
              <div class="field">
                <label>Nombre de la rifa</label>
                <input type="text" name="raffle_name">
              </div>
              <div class="field">
                <label>Nombre del premio</label>
                <input type="text" name="raffle_prize">
              </div>
              <div class="field">
                <label>Descripcion</label>
                <input type="text" name="raffle_description">
              </div>
            </div>
            <div class="fields">
              <div class="field">
                <label>Precio del boleto</label>
                <input type="number" placeholder="" name="raffle_price">
              </div>
              <div class="field">
                <label>Boletos por sorteo</label>
                <input type="number" placeholder="Min. 1 | Max. 2500" name="raffle_sellqty" min="1" max="2500">
              </div>
              <div class="field">
                <label>Boletos reales</label>
                <select name="real_id" id="real_id">
                  <option value="">Selecciona una cantidad</option>
                  <?php 
                  $data = mysqli_query($conn, "SELECT * FROM raffle_tickets");
                  if(($data) && (mysqli_num_rows($data) > 0)) {
                  //getting data and generating a row
                    while($row=mysqli_fetch_assoc($data)) {
                      echo("<option value='".$row['real_id']."'>".$row['real_number']."</option>");
                    }   
                  }
                  ?>
                </select>
              </div>
              <div class="field">
                <label>Boletos por cliente</label>
                <input type="number" placeholder="Min. 1 | Max. 10" name="raffle_buyqty" min="1" max="10">
              </div>
            </div>
            <div class="fields">
            <div class="field">
              <label>Fin de venta de boletos</label>
                <input type="text" readonly required id="raffle_end" class="datepicker-here form-control" placeholder="ex. August 03, 1998" name="raffle_end">
              </div>
              <div class="field">
                <label>Fecha del sorteo</label>
                <input type="text" readonly required id="raffle_date" class="datepicker-here form-control" placeholder="ex. August 03, 1998" name="raffle_date">
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
      <button class="ui positive right labeled icon button" id="crear-rifa">
        Crear
        <i class="checkmark icon"></i>
      </button>
    </div>
  </div>
  <!-- Modal editar -->
  <div class="ui modal" id="modal-editar">
    <i class="close icon"></i>
    <div class="header">
      Editar rifa
    </div>
    <div class="content">
      <div class="description">
        <form action="" id="form-editar" method="post" class="ui form">
        	<input type="hidden" name="raffle_id" id="edit-raffle_id">
          <input type="hidden" name="control_qty" id="edit-control_qty">
          <input type="hidden" name="control_real" id="edit-control_real">
          <div class="ui equal width form">
            <div class="fields">
              <div class="field">
                <label>Nombre de la rifa</label>
                <input type="text" name="raffle_name" id="edit-raffle_name">
              </div>
              <div class="field">
                <label>Nombre del premio</label>
                <input type="text" name="raffle_prize" id="edit-raffle_prize">
              </div>
              <div class="field">
                <label>Descripcion</label>
                <input type="text" name="raffle_description" id="edit-raffle_description">
              </div>
            </div>
            <div class="fields">
              <div class="field">
                <label>Precio del boleto</label>
                <input type="number" placeholder="" name="raffle_price" id="edit-raffle_price">
              </div>
              <div class="field">
                <label>Boletos por sorteo</label>
                <input type="number" placeholder="Min. 1 | Max. 2500" name="raffle_sellqty" min="1" max="2500" id="edit-raffle_sellqty">
              </div>
              <div class="field">
                <label>Boletos reales</label>
                <select name="real_id" id="edit-real_id">
                  <option value="">Selecciona una cantidad</option>
                  <?php 
                  $data = mysqli_query($conn, "SELECT * FROM raffle_tickets");
                  if(($data) && (mysqli_num_rows($data) > 0)) {
                  //getting data and generating a row
                    while($row=mysqli_fetch_assoc($data)) {
                      echo("<option value='".$row['real_id']."'>".$row['real_number']."</option>");
                    }   
                  }
                  ?>
                </select>
              </div>
              <div class="field">
                <label>Boletos por cliente</label>
                <input type="number" placeholder="Min. 1 | Max. 10" name="raffle_buyqty" min="1" max="10" id="edit-raffle_buyqty">
              </div>
            </div>
            <div class="fields">
            <div class="field">
              <label>Fin de venta de boletos</label>
                <input type="text" readonly required id="edit-raffle_end" class="datepicker-here form-control" placeholder="ex. August 03, 1998" name="raffle_end">
              </div>
              <div class="field">
                <label>Fecha del sorteo</label>
                <input type="text" readonly required id="edit-raffle_date" class="datepicker-here form-control" placeholder="ex. August 03, 1998" name="raffle_date">
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
      <button class="ui positive right labeled icon button" id="guardar-rifa">
        Guardar
        <i class="checkmark icon"></i>
      </button>
    </div>
  </div>
 <!--  <div class="ui modal" id="modal-editar"
    <i class="close icon"></i>
    <div class="header">
      Editar rifa
    </div>
    <div class="content">
      <div class="description">
        <form action="" id="form-editar" method="post" class="ui form">
          <input type="hidden" name="raffle_id" id="edit-raffle_id">
            <input type="hidden" name="control_qty" id="edit-control_qty">
          <div class="ui equal width form">
            <div class="fields">
              <div class="field">
                <label>Nombre de la rifa</label>
                <input type="text" name="raffle_name" id="edit-raffle_name">
              </div>
              <div class="field">
                <label>Nombre del premio</label>
                <input type="text"name="raffle_prize" id="edit-raffle_prize">
              </div>
            </div>
            <div class="fields">
              <div class="field">
                <label>Precio del boleto</label>
                <input type="number" placeholder="" name="raffle_price" id="edit-raffle_price">
              </div>
              <div class="field">
                <label>Boletos por sorteo</label>
                <input type="number" placeholder="Min. 100 | Max. 2500" name="raffle_sellqty" id="edit-raffle_sellqty">
              </div>
              <div class="field">
                <label>Boletos por cliente</label>
                <input type="number" placeholder="Min. 1 | Max. 10" name="raffle_buyqty" id="edit-raffle_buyqty">
              </div>
            </div>
            <div class="fields">
              <div class="field">
                <label>Fin de venta de boletos</label>
                <input type="text" readonly required id="edit-raffle_end" class="datepicker-here form-control" placeholder="ej. 01-01-2019" name="raffle_end">
              </div>
              <div class="field">
                <label>Fecha del sorteo</label>
                <input type="text" readonly required id="edit-raffle_date" class="datepicker-here form-control" placeholder="ex. August 03, 1998" name="raffle_date">
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
      <button class="ui positive right labeled icon button" id="guardar-rifa">
        Guardar
        <i class="checkmark icon"></i>
      </button>
    </div>
  </div> -->
  <div class="ui mini test modal" id="modal-borrar" style="width: 360px; dispplay: block;">
    <div class="header">
      Borrar rifa
    </div>
    <div class="content">
      <p>Estas seguro de borrar la rifa?</p>
      <form action="" method="post" id="form-borrar">
        <input type="hidden" name="raffle_id" id="delete-raffle_id">
      </form>
    </div>
    <div class="actions">
      <div class="ui negative button">
        No
      </div>
      <div class="ui positive right labeled icon button" id="borrar-rifa">
        Si
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div>
  <!-- End modals -->
  <?php include 'partials/top.php' ?>
    <div class="ui grid">
      <div class="row">
        <?php include 'partials/menu.php' ?>
        <div class="column" id="content" style="display:none">
          <div class="ui grid">
            <div class="six wide column">
              <h1 class="ui huge header">Todas las rifas</h1>
            </div>
            <div class="six wide column">
              <button class="ui green right labeled icon button" id="nueva-rifa">
                <i class="right plus icon"></i>
                Nueva rifa
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
                require("../dbengine/dbconnect.php");
                if(mysqli_query($conn, "DELETE FROM booking_details WHERE order_ref = '$or'")) {
                  echo ("<div class='ui positive message' style='margin:auto'>Orden eliminada/rechazada correctamente #$or.</div>");	
                } else {
                  echo ("<div class='ui negative message' style='margin:auto'>¡Error! No se pudo procesar la solicitud de rechazo de la orden #$or.</div>");	
                }	
              }
              ?>
              <div class="row">
                <table class="ui single line striped selectable center aligned  table">
                  <thead><tr><th>Codigo</th><th>Nombre de rifa</th><th>Premio</th><th>Precio</th><th>Cantidad de boletos</th><th>Maximo de boletos por cliente</th><th>Fecha de bloqueo</th><th>Fecha de rifa</th><th></th></tr></thead>
                  <tbody>
                    <?php
                    require("../dbengine/dbconnect.php");
                    if(isset($_GET['search'])) {
                      $search = $_GET['search'];
                      $data = mysqli_query($conn, "SELECT * FROM raffles WHERE raffle_name LIKE '%$search%' or raffle_id LIKE '%$search%'");
                    } else {
                      $data = mysqli_query($conn, "SELECT * FROM raffles");
                    }
                    if(($data) && (mysqli_num_rows($data) > 0)) {
                    //getting data and generating a row
                      while($row=mysqli_fetch_assoc($data)) {
                        echo("<tr><td>".$row['raffle_code']."</td><td>".$row['raffle_name']."</td><td>".$row['raffle_prize']."</td><td>"."$".sprintf('%0.2f', ($row['raffle_price']/100))."</td><td>".$row['raffle_sellqty']."</td><td>".$row['raffle_buyqty']."</td><td>".formdate($row['raffle_end'])."</td><td>".formdate($row['raffle_date'])."</td><td><div class='ui icon teal button editar-rifa' data-id='".$row['raffle_id']."'><i class='pencil icon'></i></div><button class='ui icon red button borrar-rifa' data-id='".$row['raffle_id']."'><i class='trash icon'></i></button></td></tr>");
                      }		
                    } else {
                      echo "<tr><td colspan='9'>¡No se encontraron coincidencias de registros!</td></tr>";	
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
    </div>
  </div>
</body>
</html>