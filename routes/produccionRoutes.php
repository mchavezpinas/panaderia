<?php
require_once '../app/controllers/produccionController.php';
require_once '../app/controllers/petroleoController.php';
require_once '../app/models/Producto.php';
require_once '../app/models/Insumo.php';
require_once '../app/models/Produccion.php';
require_once '../app/models/Consumo_Petroleo.php';
$produccionController = new ProduccionController();
$petroleoController = new PetroleoController();

$request = str_replace('/panaderia/public', '', $_SERVER['REQUEST_URI']);
$request = parse_url($request);
$path = $request['path'];
$query = [];
if (isset($request['query'])) {
    parse_str($request['query'], $query);
}
switch ($path) {
    case '/coches_produccion':
        if (isset($_SESSION['user_id'])) {
            $productos = $produccionController->obtenerCochesProduccion();
            $rutaDelete = 'eliminar_coche';
            require_once '../app/views/listaCochesProduccionView.php';
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/agregar_coche':
        if (isset($_SESSION['user_id'])) {
            require_once '../app/views/createCocheView.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'guardar') {
                    $producto = new Producto($_POST['nombre'], 'S/D', 'S/D', 'S/D', $_POST['coches']);
                    $produccionController->agregarCoche($producto);
                    header("Location: /panaderia/public/coches_produccion");
                    exit();
                } else {
                    header("Location: /panaderia/public/coches_produccion");
                }
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/editar_coche':
        if (isset($_SESSION['user_id'])) {
            if (isset($query['id'])) {
                $coche = $produccionController->obtenerCoche($query['id']);
                $coche->setCod_prod($_GET['id']);
                require_once '../app/views/editCocheView.php';
            } else {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($_POST['action'] == 'update') {
                        $producto = new Producto($_POST['nombre'], 'S/D', 'S/D', 'S/D', $_POST['coches']);
                        $producto->setCod_prod($_POST['cod_coche']);
                        $produccionController->editarCoche($producto);
                        header("Location: /panaderia/public/coches_produccion");
                        exit();
                    } else {
                        header("Location: /panaderia/public/coches_produccion");
                        exit();
                    }
                }
                header("Location: /panaderia/public/usuarios");
                exit();
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/eliminar_coche':
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'eliminar') {
                    $produccionController->eliminarCoche($_POST['id']);
                    $_SESSION['success_msg'] = "Coche eliminado correctamente. Los insumos calculados deberán reiniciarse y calcularse nuevamente.";
                    header("Location: /panaderia/public/coches_produccion");
                    exit();
                } else {
                    header("Location: /panaderia/public/coches_produccion");
                    exit();
                }
            } else {
                header("Location: /panaderia/public/login");
            }
        }
        break;
    case '/productos_produccion':
        if (isset($_SESSION['user_id'])) {
            if (isset($query['id'])) {
                #$productos = $produccionController->obtenerProductosProduccion();
                $_SESSION['cod_coche'] = $query['id'];
                $productos = $produccionController->obtenerProductosProduccionporCoches($query['id']);
                $rutaDelete = 'eliminar_producto_produccion';
                require_once '../app/views/produccionProductosView.php';
                exit();
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/agregar_produccion':
        if (isset($_SESSION['user_id'])) {
            require_once '../app/views/createProduccionView.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'guardar') {
                    $cod_coche = $_SESSION['cod_coche'];
                    $producto = new Producto($_POST['nombre'], $_POST['desc'], 'S/D', $_POST['tamano'], 'S/D');
                    $currentDateTime = date('Y-m-d H:i:s');
                    switch ($producto->getNom_prod()) {
                        case 'Pan':
                            switch ($producto->getTam_prod()) {
                                case 'Pequeño':
                                    $unidades = ($_POST['latas'] * 40) + $_POST['extra'];
                                    $bolsas = intdiv($unidades, 42);
                                    $extra = $unidades % 42;
                                    break;
                                case 'Mediano':
                                    $unidades = ($_POST['latas'] * 24) + $_POST['extra'];
                                    $bolsas = intdiv($unidades, 21);
                                    $extra = $unidades % 21;
                                    break;
                                case 'Grande':
                                    $unidades = ($_POST['latas'] * 15) + $_POST['extra'];
                                    $bolsas = intdiv($unidades, 18);
                                    $extra = $unidades % 18;
                                    break;
                            }
                            break;
                        case 'Bizcocho':
                            switch ($producto->getTam_prod()) {
                                case 'Pequeño':
                                    $unidades = ($_POST['latas'] * 88) + $_POST['extra'];
                                    $bolsas = intdiv($unidades, 42);
                                    $extra = $unidades % 42;
                                    break;
                                case 'Grande':
                                    $unidades = ($_POST['latas'] * 20) + $_POST['extra'];
                                    $bolsas = intdiv($unidades, 18);
                                    $extra = $unidades % 18;
                                    break;
                            }
                            break;
                    }
                    $produccion = new Produccion($_POST['latas'], $bolsas, $extra, $unidades, $currentDateTime, 'S/D', 0);
                    $produccionController->agregarProduccion($cod_coche, $produccion, $producto);
                    header("Location: /panaderia/public/productos_produccion?id=" . $_SESSION['cod_coche']);
                    exit();
                } else {
                    header("Location: /panaderia/public/productos_produccion?id=" . $_SESSION['cod_coche']);
                }
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/editar_produccion':
        if (isset($_SESSION['user_id'])) {
            require_once '../app/views/editProduccionView.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'guardar') {
                    /* if (isset($_POST['extraExist'])) {
                        $producto = new Producto($_POST['nombre'], $_POST['desc'], 'S/D', $_POST['tamano'], 'S/D');
                        $currentDateTime = date('Y-m-d H:i:s');
                        $produccion = new Produccion($_POST['cant'], $_POST['extra'], $currentDateTime, 'S/D', 0);
                        $produccionController->agregarProduccion($produccion, $producto);
                    } else {
                        $producto = new Producto($_POST['nombre'], $_POST['desc'], 'S/D', $_POST['tamano'], 'S/D');
                        $currentDateTime = date('Y-m-d H:i:s');
                        $produccion = new Produccion($_POST['cant'], 'S/D', $currentDateTime, 'S/D', 0);
                        $produccionController->agregarProduccion($produccion, $producto);
                    }
                    header("Location: /panaderia/public/productos_produccion");
                    exit(); */
                } else {
                    header("Location: /panaderia/public/productos_produccion");
                }
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/eliminar_producto_produccion':
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'eliminar') {
                    $cod_coche = $_SESSION['cod_coche'];
                    $produccionController->eliminarProduccionByID($cod_coche, $_POST['id']);
                    $_SESSION['success_msg'] = "Producción eliminada correctamente. Los insumos calculados deberán reiniciarse y calcularse nuevamente.";
                    header("Location: /panaderia/public/productos_produccion?id=" . $_SESSION['cod_coche']);
                    exit();
                } else {
                    header("Location: /panaderia/public/productos_produccion?id=" . $_SESSION['cod_coche']);
                    exit();
                }
            } else {
                header("Location: /panaderia/public/login");
            }
        }
        break;
    case '/calculo_temporal':
        if (isset($_SESSION['user_id'])) {
            if (isset($query['id'])) {
                $produccion = $produccionController->obtenerProduccionbyID($_GET['id']);
                $insumos = $produccionController->calcularInsumosTemporales($produccion);
                require_once '../app/views/calculoTemporalView.php';
            }
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/calcular_produccion_base_productos':
        if (isset($_SESSION['user_id'])) {
            $productos = $produccionController->obtenerProductosProduccion();
            require_once '../app/views/listaProductoProduccionView.php';
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/insumos_calculados':
        if (isset($_SESSION['user_id'])) {
            $producciones = $produccionController->obtenerProductosProduccion();
            for ($i = 0; $i < count($producciones); $i++) {
                $produccionController->calcularInsumosRequeridos($producciones[$i]);
            }
            #$produccionController->calularInsumosProduccionTotalPorTipo();
            $produccionController->calularInsumosProduccionTotalPorTipoBaseCoches();
            header("Location: /panaderia/public/produccion");
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/pruebacoches':
        $produccionController->calularInsumosProduccionTotalPorTipoBaseCoches();
        break;
    case '/distribucion_insumos':
        $rutaDelete = 'eliminar_insumo_produccion';
        if (isset($_SESSION['user_id'])) {
            $producciones = $produccionController->obtenerProductosProduccion();
            $insumos = $produccionController->obtenerInsumosProduccion();
            require_once '../app/views/insumosCalculadosView.php';
            #$insumos = $insumosController->obtenerInsumosListaDelDia();
            #require_once '../app/views/registrarInsumosView.php';
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/editar_insumo_produccion':
        if (isset($_SESSION['user_id'])) {
            if (isset($query['id'])) {
                $insumo = $produccionController->obtenerInsumoProduccionByID($query['id']);
                $insumo->setCod_ins($_GET['id']);
                require_once '../app/views/editInsumoProduccionView.php';
            } else {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($_POST['action'] == 'update') {
                        $insumo = new Insumo('S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D', 'S/D', $_POST['stock'], 'S/D', 'S/D');
                        $insumo->setCod_ins($_POST['id']);
                        $produccionController->actualizarStockInsumoProduccion($insumo);
                        header("Location: /panaderia/public/distribucion_insumos");
                        exit();
                    } else {
                        header("Location: /panaderia/public/distribucion_insumos");
                        exit();
                    }
                }
                header("Location: /panaderia/public/usuarios");
                exit();
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/eliminar_insumo_produccion':
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'eliminar') {
                    $produccionController->eliminarInsumoProduccionByID($_POST['id']);
                    header("Location: /panaderia/public/distribucion_insumos");
                    exit();
                } else {
                    header("Location: /panaderia/public/distribucion_insumos");
                    exit();
                }
            } else {
                header("Location: /panaderia/public/login");
            }
        }
        break;
    case '/limpiar_insumos_produccion':

        if (isset($_SESSION['user_id'])) {

            $produccionController->limpiarInsumosProduccion();

            header("Location: /panaderia/public/distribucion_insumos");
            exit();
        } else {

            header("Location: /panaderia/public/login");
        }

        break;

    case '/descuento_insumos_produccion':
        if (isset($_SESSION['user_id'])) {
            $insumosInventario = $produccionController->obtenerInventarioInsumos(); #Insumos en el inventario
            $insumoProduccion = $produccionController->obtenerInsumosProduccion(); #Insumos a descontar
            $produccionController->descontarInsumosProduccion($insumosInventario, $insumoProduccion);
            $producciones = $produccionController->obtenerProductosProduccion();
            foreach ($producciones as $key => $produccion) {
                $produccionController->actualizarEstadoProducciones($produccion->getCod_prod());
            }
            $cod_inv_producto = $produccionController->crearInventarioProductos();
            $produccionController->enviarProductosInventario($cod_inv_producto, $producciones);
            header("Location: /panaderia/public/produccion");
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/consumo_petroleo':
        if (isset($_SESSION['user_id'])) {
            $consumos = $petroleoController->obtenerConsumosPetroleo();
            $rutaDelete = 'eliminar_consumo_petroleo';
            require_once '../app/views/consumosPetroleoView.php';
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/agregar_consumo_petroleo':
        if (isset($_SESSION['user_id'])) {
            require_once '../app/views/createConsumoPetroleoView.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'add') {
                    #$currentDate = date('Y-m-d');
                    $selectedDate = $_POST['fecha'];
                    #Seteando en 0 los valores iniciales
                    $altura_inicial = 0;
                    $altura_final = 0;
                    $variante = 0;
                    $galones = 0;
                    $inversion = 0;
                    if ($_POST['altura_inicial'] > 0 && $_POST['altura_final'] > 0) {
                        $altura_inicial = $_POST['altura_inicial'];
                        $altura_final = $_POST['altura_final'];
                        $variante = $altura_inicial - $altura_final;
                        $galones = $petroleoController->calcularGalones($variante);
                        $inversion = $petroleoController->calcularInversion($galones);
                    } else {
                        $altura_inicial = $_POST['altura_inicial'];
                        $altura_final = $_POST['altura_final'];
                    }
                    $consumo = new Consumo_Petroleo($selectedDate, $altura_inicial, $altura_final, $variante, $galones, $inversion);
                    $petroleoController->agregarConsumosPetroleo($consumo);
                    header("Location: /panaderia/public/consumo_petroleo");
                    exit();
                } else {
                    header("Location: /panaderia/public/consumo_petroleo");
                }
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/editar_consumo_petroleo':
        if (isset($_SESSION['user_id'])) {
            if (isset($query['id'])) {
                $consumo = $petroleoController->obtenerConsumoPetroleo($query['id']);
                $consumo->getCod_consumo_petroleo($_GET['id']);
                require_once '../app/views/editConsumoPetroleoView.php';
            } else {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($_POST['action'] == 'edit') {
                        $cod_consumo_petroleo = $_POST['id'];
                        #$currentDate = date('Y-m-d');
                        $selectedDate = $_POST['fecha'];
                        #Seteando en 0 los valores iniciales
                        $altura_inicial = 0;
                        $altura_final = 0;
                        $variante = 0;
                        $galones = 0;
                        $inversion = 0;
                        if ($_POST['altura_inicial'] > 0 && $_POST['altura_final'] > 0) {
                            $altura_inicial = $_POST['altura_inicial'];
                            $altura_final = $_POST['altura_final'];
                            $variante = $altura_inicial - $altura_final;
                            $galones = $petroleoController->calcularGalones($variante);
                            $inversion = $petroleoController->calcularInversion($galones);
                        } else {
                            $altura_inicial = $_POST['altura_inicial'];
                            $altura_final = $_POST['altura_final'];
                        }
                        $consumo = new Consumo_Petroleo($selectedDate, $altura_inicial, $altura_final, $variante, $galones, $inversion);
                        $consumo->setCod_consumo_petroleo($cod_consumo_petroleo);
                        $petroleoController->editarConsumosPetroleo($consumo);
                        header("Location: /panaderia/public/consumo_petroleo");
                        exit();
                    } else {
                        header("Location: /panaderia/public/consumo_petroleo");
                    }
                }
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/eliminar_consumo_petroleo':
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'eliminar') {
                    $petroleoController->eliminarConsumoPetroleo($_POST['id']);
                    header("Location: /panaderia/public/consumo_petroleo");
                    exit();
                } else {
                    header("Location: /panaderia/public/consumo_petroleo");
                    exit();
                }
            } else {
                header("Location: /panaderia/public/login");
            }
        }
        break;
    case '/merma_produccion':
        if (isset($_SESSION['user_id'])) {
            $mermas = $produccionController->obtenerMermas();
            $rutaDelete = 'eliminar_merma';
            require_once '../app/views/listaMermasView.php';
            exit();
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/agregar_merma':
        if (isset($_SESSION['user_id'])) {
            require_once '../app/views/createMermaView.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'guardar') {
                    $currentDate = date('Y-m-d H:i:s');
                    $merma = new Merma($_POST['producto'], $_POST['tamaño'], $_POST['cantidad'], $currentDate, $_POST['motivo'], '0');
                    $produccionController->agregarMerma($merma);
                    header("Location: /panaderia/public/merma_produccion");
                    exit();
                } else {
                    header("Location: /panaderia/public/merma_produccion");
                }
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/editar_merma':
        if (isset($_SESSION['user_id'])) {
            if (isset($query['id'])) {
                $merma = $produccionController->obtenerMerma($query['id']);
                $merma->setCodMerma($_GET['id']);
                require_once '../app/views/editMermaView.php';
            } else {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($_POST['action'] == 'update') {
                        $selectedDate = $_POST['fecha'];
                        $merma = new Merma($_POST['producto'], $_POST['tamaño'], $_POST['cantidad'], $selectedDate, $_POST['motivo'], '0');
                        $merma->setCodMerma($_POST['cod_merma']);
                        $produccionController->editarMerma($merma);
                        header("Location: /panaderia/public/merma_produccion");
                        exit();
                    } else {
                        header("Location: /panaderia/public/merma_produccion");
                        exit();
                    }
                }
                header("Location: /panaderia/public/usuarios");
                exit();
            }
        } else {
            header("Location: /panaderia/public/login");
        }
        break;
    case '/eliminar_merma':
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['action'] == 'eliminar') {
                    $produccionController->eliminarMerma($_POST['id']);
                    header("Location: /panaderia/public/merma_produccion");
                    exit();
                } else {
                    header("Location: /panaderia/public/merma_produccion");
                    exit();
                }
            } else {
                header("Location: /panaderia/public/login");
            }
        }
        break;
    case '/carga_masiva_ventas':

        if (isset($_SESSION['user_id'])) {

            require_once '../app/views/cargaMasivaVentasView.php';
        } else {

            header("Location: /panaderia/public/login");
        }

        break;
    case '/importar_ventas_excel':

        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                require_once '../app/services/ImportadorVentas.php';

                $importador = new ImportadorVentas();

                $importador->importar(
                    $_FILES['archivo_excel']['tmp_name']
                );

                header("Location: /panaderia/public/registro_ventas");
                exit();
            }
        }

        break;
}
