<?php

require_once __DIR__.'/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportadorVentas
{
    private $ventasController;
    private $productoController;

    public function __construct()
    {
        require_once '../app/controllers/ventasController.php';
        require_once '../app/controllers/productoController.php';

        $this->ventasController = new VentasController();
        $this->productoController = new ProductoController();
    }

    public function importar($archivo)
    {
        $excel = IOFactory::load($archivo);

        $hoja = $excel->getActiveSheet();

        $datos = $hoja->toArray();

        unset($datos[0]);

        $ventasAgrupadas = [];

        foreach($datos as $fila)
        {
            $key =
            $fila[0].'_'.
            $fila[1].'_'.
            $fila[2];

            $ventasAgrupadas[$key][] = $fila;
        }

        foreach($ventasAgrupadas as $registros)
        {
            $primeraFila = $registros[0];

            $venta = new Venta(
                $primeraFila[0],
                $primeraFila[2],
                $primeraFila[1],
                0,
                'S/D',
                $primeraFila[3],
                1
            );

            $codVenta =
            $this->ventasController->agregarVenta(
                $venta
            );

            foreach($registros as $item)
            {
                $cantidad = floatval($item[7]);

                $precio = floatval($item[8]);

                $producto = new Producto(
                    $item[4],
                    $item[5],
                    'N/A',
                    $item[6],
                    $cantidad
                );

                $producto->setPrecio($precio);

                $producto->setPrecio_tot(
                    $cantidad * $precio
                );

                $codProd =
                $this->productoController
                ->agregarProductobyVenta(
                    $producto
                );

                $this->ventasController
                ->unirVentaProducto(
                    $codProd,
                    $codVenta,
                    0
                );
            }

            foreach($registros as $item)
            {
                if(
                    isset($item[9]) &&
                    trim($item[9]) != ''
                )
                {
                    $this->productoController
                    ->agregarAbonobyCuenta(
                        $primeraFila[0],
                        $item[9],
                        $primeraFila[3],
                        $item[10]
                    );

                    $cuenta =
                    $this->ventasController
                    ->obtenerCuenta(
                        $primeraFila[0]
                    );

                    $cuenta->setSaldo(
                        $cuenta->getSaldo()
                        - floatval($item[9])
                    );

                    $this->ventasController
                    ->editarCuenta(
                        $cuenta
                    );
                }
            }
        }
    }
}