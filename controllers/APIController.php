<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        // Forzar UTF-8 en la conexión a la base de datos
        Servicio::setDBCharset('utf8');

        $servicios = Servicio::all();

        // Verificar datos antes de enviar
        error_log("Servicios obtenidos: " . print_r($servicios, true));

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar()
    {
        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);

        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // Almacena la Cita y el Servicio
// Almacena los servicios con el id de la cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio ->guardar();
        }
        // Retornamos una respuesta
        $respuesta = [
            'resultado' => $resultado
        ];
        // $respuesta = [
        //     'cita' => $cita
        // ];

        echo json_encode(['resultado' => $resultado]);
    }
}
