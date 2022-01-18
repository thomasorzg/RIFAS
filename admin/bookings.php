<?php include 'partials/head.php' ?>
<body>
	<?php include 'partials/top.php' ?>
    <div class="ui grid">
      <div class="row">
        <?php include 'partials/menu.php' ?>
        <div class="column" id="content" style="display:none">
          <div class="ui grid">
            <div class="row">
              <h1 class="ui huge header">Todas las reservas</h1></div>
              <div class="ui horizontal divider"> Estos son los detalles de las reservas</div>      
              <?php 
              // Eliminar:
              if(isset($_GET['reject'])) {
                $or = $_GET['reject'];
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
                  <thead><tr><th>Orden</th><th>Nombre del cliente</th><th>Contacto</th><th>Clase reservada</th><th>Destino</th><th>Número de asientos</th><th>Fecha de viaje</th><th>Acción</th></tr></thead>
                  <tbody>
                    <?php
                    require("../dbengine/dbconnect.php");
                    if(isset($_GET['search'])) {
                      $search = $_GET['search'];
                      $data = mysqli_query($conn, "SELECT * FROM booking_details WHERE order_ref LIKE '%$search%' or fullname LIKE '%$search%' or class_reserved LIKE '%$search%' or destination LIKE '%$search%' or date_reserved LIKE '%$search%'");
                    } else {
                      $data=mysqli_query($conn,"SELECT * FROM booking_details");
                    }
                    if(($data) && (mysqli_num_rows($data) > 0)) {
                    // Obtener datos y generar una fila
                      while($row=mysqli_fetch_assoc($data)) {
                        $order = $row['order_ref'];
                        echo("<tr><td>".$row['order_ref']."</td><td>".$row['fullname']."</td><td>".$row['contact']."</td><td>".$row['class_reserved']."</td><td>".$row['destination']."</td><td>".$row['seats_reserved']."</td><td>".$row['date_reserved']."</td><td><a class='ui tiny button orange' href='bookings.php?reject=$order'>Rechazar</a></td></tr>");
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