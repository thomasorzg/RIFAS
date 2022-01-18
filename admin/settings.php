<?php
if(!empty($_FILES['uploaded_file']))
{
if($_FILES['uploaded_file']['type'] != "image/png" && $_FILES['uploaded_file']['type'] != "image/jpg") {
    echo "Solo se permiten imagenes!";
    exit;
}
  $path = "../uploads/";
  $path = $path . basename( $_FILES['uploaded_file']['name']);

  if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
    require("../dbengine/dbconnect.php");
    $query = mysqli_query($conn, "INSERT INTO banners (banner_path) VALUES('$path')");
  } else{
      echo "Hubo un error al subir!";
      exit;
  }
}
?>
<?php include 'partials/head.php' ?>
<body>
  <?php include 'partials/top.php' ?>
  <div class="ui grid">
    <div class="row">
      <?php include 'partials/menu.php' ?>
      <div class="column" id="content" style="display: none">
        <div class="ui grid">
          <div class="row">
            <h1 class="ui huge header">Configuración</h1>
          </div>
          <div class="ui horizontal divider"> Subir banner</div>      
          <div class="row">
            <form action="" enctype="multipart/form-data" method="post" class="ui form">
            <input type="file" name="uploaded_file"></input><br /><br>
            <input type="submit" class="ui green submit button" value="Subir"></input>
            </form>
          </div>
        </div>	
      </div>
    </div>
  </div>
    
</body>
</html>