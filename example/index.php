<?php    
function encabezadoTabla(){
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tabla con columnas de diferentes anchos</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border-top: 0px!important;
	  border-left: 0px!important;
	  border-right: 0px!important;
	  border-bottom: 1px solid #ddd!important;
      padding: 8px;
      text-align: left;
	  background-color: transparent!important;
    }

    /* Ancho de las columnas */
    th:nth-child(1),
    td:nth-child(1) {
      width: 60%;
    }

    th:nth-child(2),
    td:nth-child(2),
    th:nth-child(3),
    td:nth-child(3) {
      width: 20%;
    }
  </style>
</head>
<body>

<table>
  <tr>
    <th>Comprobante</th>
    <th>Fecha</th>
    <th>Archivo</th>
  </tr>
  <?php } ?>
  <?php
function filaTabla($parametros) {
  
  foreach ($parametros as $parametro) {
    $linkEliminar='';
  if (is_admin()) {
    $linkEliminar='| <a href="'.$parametro['dato4'].'" class="download" >Eliminar PDF</a>';
  }
    ?>
    <tr>
      <td><?php echo $parametro['dato1']; ?></td>
      <td><?php echo $parametro['dato2']; ?></td>
      <td><?php echo "<a href='".$parametro['dato3']."' class='download' target='_blank'>Descargar PDF</a>  ".$linkEliminar ?></td>
    </tr>
    <?php
  }
} ?>
  <?php function footerTabla(){ ?>
</table>

</body>
</html>
<?php } 
