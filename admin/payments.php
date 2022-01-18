<?php
  require("../dbengine/dbconnect.php");

  function validate_form($pajar, $agujas)
  {
    if (count(array_intersect_key(array_flip($agujas), $pajar)) === count($agujas)) {
      return true;
    }
    return false;
  }

  if ($_POST['submit_type']) {
    if ($_POST['submit_type'] == "borrar") {
      $query = "DELETE FROM payments WHERE payment_id=$_POST[payment_id]";
      
      $deletedata = mysqli_query($conn, $query);    
      if(!$deletedata) {
        $message = "Error al borrar!";
      }
    }
  }
  $formKeys = ['payment_name', 'payment_email'];
  
  if (validate_form($_POST, $formKeys)) {
    if (!$_POST['payment_id']) {
      $query = "INSERT into payments (payment_name, payment_email) VALUES('$_POST[payment_name]', '$_POST[payment_email]')";
      
      $insertdata = mysqli_query($conn, $query);    
      if($insertdata) {
        $message = "success";
      } else { 
        $message = "Error al guardar!";
      }
    } else {
        $query = "UPDATE payments SET payment_name='$_POST[payment_name]', payment_email='$_POST[payment_email]' WHERE payment_id=$_POST[payment_id]";
      
      $insertdata = mysqli_query($conn, $query);    
      if($insertdata) {
        $message = "success";
      } else { 
        $message = "Error al guardar!";
      }
    }
  }
?>
<?php include 'partials/head.php' ?>
<body>
  <div class="ui modal" id="modal-crear">
    <i class="close icon"></i>
    <div class="header">
      Nuevo método de pago
    </div>
    <div class="content">
      <div class="description">
        <form action="" id="form-crear" method="post" class="ui form">
          <div class="ui equal width form">
            <div class="fields">
              <div class="field">
                <label>Nombre del método de pago</label>
                <input type="text" name="payment_name">
              </div>
              <div class="field">
                <label>Email</label>
                <input type="email"name="payment_email">
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
      <button class="ui positive right labeled icon button" id="crear-metodo">
        Crear
        <i class="checkmark icon"></i>
      </button>
    </div>
  </div>
  <div class="ui modal" id="modal-editar">
    <i class="close icon"></i>
    <div class="header">
      Editar método de pago
    </div>
    <div class="content">
      <div class="description">
        <form action="" id="form-editar" method="post" class="ui form">
          <input type="hidden" name="payment_id" id="edit-payment_id">
          <div class="ui equal width form">
            <div class="fields">
              <div class="field">
                <label>Nombre del método de pago</label>
                <input type="text" name="payment_name" id="edit-payment_name">
              </div>
              <div class="field">
                <label>Email</label>
                <input type="text"name="payment_email" id="edit-payment_email">
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
      <button class="ui positive right labeled icon button" id="guardar-metodo">
        Guardar
        <i class="checkmark icon"></i>
      </button>
    </div>
  </div>
  <div class="ui mini test modal" id="modal-borrar" style="width: 360px; dispplay: block;">
    <div class="header">
      Borrar método de pago
    </div>
    <div class="content">
      <p>Estas seguro de borrar la rifa?</p>
      <form action="" method="post" id="form-borrar">
        <input type="hidden" name="payment_id" id="delete-payment_id">
      </form>
    </div>
    <div class="actions">
      <div class="ui negative button">
        No
      </div>
      <div class="ui positive right labeled icon button" id="borrar-metodo">
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
              <h1 class="ui huge header">Todos los métodos de pago</h1>
            </div>
            <div class="six wide column">
              <button class="ui green right labeled icon button" id="nueva-metodo">
                <i class="right plus icon"></i>
                Nuevo método de pago
              </button>
            </div>
          </div>
          <div class="ui grid">
            <div class="row"></div>
              <div class="ui horizontal divider"> Lista de métodos de pago</div>      
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
                  <thead><tr><th>Id</th><th>Nombre de rifa</th><th>Email</th><th></th></tr></thead>
                  <tbody>
                    <?php
                    require("../dbengine/dbconnect.php");
                    if(isset($_GET['search'])) {
                      $search = $_GET['search'];
                      $data = mysqli_query($conn, "SELECT * FROM payments WHERE payment_name LIKE '%$search%' or payment_id LIKE '%$search%'");
                    } else {
                      $data = mysqli_query($conn, "SELECT * FROM payments");
                    }
                    if(($data) && (mysqli_num_rows($data) > 0)) {
                    //getting data and generating a row
                      while($row=mysqli_fetch_assoc($data)) {
                        echo("<tr><td>".$row['payment_id']."</td><td>".$row['payment_name']."</td><td>".$row['payment_email']."</td><td><div class='ui icon teal button editar-metodo' data-id='".$row['payment_id']."'><i class='pencil icon'></i></div><button class='ui icon red button borrar-metodo' data-id='".$row['payment_id']."'><i class='trash icon'></i></button></td></tr>");
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
    </div>
  </div>
</body>
</html>