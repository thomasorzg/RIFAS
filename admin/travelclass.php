<?php include 'partials/head.php' ?>
<body>
  <?php include 'partials/top.php' ?>
  <div class="ui grid">
    <div class="row">
      <?php include 'partials/menu.php' ?>
      <div class="column" id="content" style="display: none">
        <div class="ui grid">
          <div class="row">
            <h1 class="ui huge header">Todas las clases de viaje</h1>
          </div>
          <div class="ui horizontal divider"> Clases de viaje disponibles</div>      
          <div class="row">
            <table class="ui single line striped selectable center aligned  table">
              <thead><tr><th>ID</th><th>Clase de viaje</th><th>Capacidad de clase</th><th>Precio de clase</th><th>Descripción / Oferta</th></tr></thead>
              <tbody>
                <?php
                require("../dbengine/dbconnect.php");
                if(isset($_GET['search'])) {
                  $search = $_GET['search'];
                  $data = mysqli_query($conn, "SELECT * FROM available_class WHERE class_id LIKE '%$search%' or class_name LIKE '%$search%'");
                } else {
                  $data = mysqli_query($conn, "SELECT * FROM available_class");
                }
                if(($data) && (mysqli_num_rows($data) > 0)){
                //getting data and generating a row
                  while($row = mysqli_fetch_assoc($data)) {
                    echo("<tr><td>".$row['class_id']."</td><td>".$row['class_name']."</td><td>".$row['class_capacity']."</td><td>".$row['class_price']."</td><td>".$row['description']."</td></tr>");
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