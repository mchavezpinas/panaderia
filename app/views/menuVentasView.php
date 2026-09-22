<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../public/css/menuPlantilla.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Menu Ventas</title>
</head>

<body>
    <?php require_once 'nav.php' ?>
    <div class="box">
        <h2>Menu Ventas</h2>
        <a class="opcion" href="lista_clientes">
            <span class="material-icons">person</span>
            Lista de Clientes
        </a>
        <a class="opcion" href="lista_pedidos">
            <span class="material-icons">edit_square</span>
            Lista de Pedidos
        </a>
        <a class="opcion" href="registro_ventas">
            <span class="material-icons">point_of_sale</span>
            Lista de Ventas
        </a>
        <!-- <a class="opcion" href="seguimiento_ventas">
            <span class="material-icons">track_changes</span>
            Seguimiento de Ventas
        </a> -->
        <a class="opcion" href="seguimiento_cuentas">
            <span class="material-icons">track_changes</span>
            Seguimiento de Cuentas
        </a>
        <a class="opcion" href="lista_abarrotes">
            <span class="material-icons">edit_square</span>
            Lista de Abarrotes
        </a>
        <a class="opcion" href="registro_ventas_abarrotes">
            <span class="material-icons">point_of_sale</span>
            Lista de Ventas de Abarrotes
        </a>
        <a class="opcion" href="https://panificadoradelnortech.com/ventas_importacion/">
            <span class="material-icons">upload_file</span>
            Carga Masiva de Ventas
        </a>
        <!-- <a class="opcion" href="">
            <span class="material-icons">receipt_long</span>
            Generacion de Facturas/Recibos
        </a>
        <a class="opcion" href="">
            <span class="material-icons">money_off</span>
            Ventas No Pagadas
        </a> -->
    </div>
</body>

</html>