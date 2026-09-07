<!DOCTYPE html>
<html lang="en">

<head>
    ../public/css/usuariosView.css

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    ://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Carga Masiva de Ventas</title>
</head>

<body>

    <?php require_once 'nav.php'; ?>

    ventas
        Volver
    </a>

    <div class="cuerpo">

        <h2 class="titulo-general">
            Carga Masiva de Ventas
        </h2>

        <p class="subtitulo-general">
            Importa ventas de forma masiva mediante una plantilla Excel
        </p>

        <div class="concepto">

            <div class="box-concepto">

                <div class="box-content" style="width:auto;">

                    <span
                        class="material-icons iconcepto"
                        style="color:#0869fa;">

                        info

                    </span>

                    <div>

                        Descarga la plantilla oficial y no modifiques
                        los nombres de las columnas.

                    </div>

                </div>

            </div>

        </div>

        <div class="table-container">

            /panaderia/public/importar_ventas_excel

                <table>

                    <thead>

                        <tr>
                            <th colspan="2">
                                Importación de Ventas
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td style="width:30%;">
                                Archivo Excel
                            </td>

                            <td>

                                <input
                                    type="file"
                                    name="archivo_excel"
                                    accept=".xlsx,.xls"
                                    required>

                            </td>

                        </tr>

                        <tr>

                            <td>

                                /panaderia/public/plantilla_ventas.xlsx

                                    <span class="material-icons">
                                        download
                                    </span>

                                    Descargar Plantilla

                                </a>

                            </td>

                            <td>

                                <button
                                    type="submit"
                                    class="create">

                                    <span
                                        class="material-icons"
                                        style="color:white;">

                                        upload_file

                                    </span>

                                    Importar Ventas

                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </form>

        </div>

        <br>

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>COD_CUENTA</th>
                        <th>FECHA</th>
                        <th>COD_EMPLEADO</th>
                        <th>MET_PAG</th>
                        <th>PRODUCTO</th>
                        <th>DESCRIPCIÓN</th>
                        <th>TAMAÑO</th>
                        <th>CANTIDAD</th>
                        <th>PRECIO</th>
                        <th>ABONO</th>
                        <th>FECHA_ABONO</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>25</td>
                        <td>2026-08-20</td>
                        <td>1</td>
                        <td>YAPE</td>
                        <td>TORTA CHOCOLATE</td>
                        <td>CUMPLEAÑOS</td>
                        <td>GRANDE</td>
                        <td>1</td>
                        <td>120.00</td>
                        <td>50.00</td>
                        <td>2026-08-20</td>

                    </tr>

                    <tr>

                        <td>25</td>
                        <td>2026-08-20</td>
                        <td>1</td>
                        <td>YAPE</td>
                        <td>PAN INTEGRAL</td>
                        <td>BOLSA</td>
                        <td>MEDIANO</td>
                        <td>3</td>
                        <td>15.00</td>
                        <td></td>
                        <td></td>

                    </tr>

                </tbody>

            </table>

        </div>

        <div class="concepto">

            <div class="box-concepto">

                <div class="box-content" style="width:auto;">

                    <span
                        class="material-icons iconcepto"
                        style="color:green;">

                        check_circle

                    </span>

                    <div>

                        <strong>Columnas obligatorias:</strong><br>

                        COD_CUENTA,
                        FECHA,
                        COD_EMPLEADO,
                        MET_PAG,
                        PRODUCTO,
                        CANTIDAD,
                        PRECIO

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>