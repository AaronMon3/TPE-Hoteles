<?php

class HabitacionesView {

    public function listView($habitaciones) {
        
        $habitaciones = $habitaciones;
        require __DIR__ . '/../../templates/public/listadoHabitaciones.phtml';
    }

    public function detalleView($habitacion) {
        $habitacion = $habitacion;
        require __DIR__ . '/../../templates/public/detalleHabitacion.phtml';
    }

    public function porTipoView($habitaciones, $tipo) {
        $habitaciones = $habitaciones;
        $tipo = $tipo;
        require __DIR__ . '/../../templates/public/habitacionesPorTipo.phtml';
    }

    public function notFoundView() {
        require __DIR__ . '/../../templates/public/not_found.phtml';
    }
}
