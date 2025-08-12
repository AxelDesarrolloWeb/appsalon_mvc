<?php

namespace Controllers;

use MVC\Router;

class ReservaController
 {
    public static function index(Router $router) {
        $router->render('reserva/index', [
            
        ]);
    }
 }