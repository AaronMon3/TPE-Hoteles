<?php

class AdminView {

    public function dashboardView() {
        require __DIR__ . '/../../templates/admin/dashboard.phtml';
    }

    public function listaHabitacionesView($habitaciones) {
        $habitaciones = $habitaciones;
        require __DIR__ . '/../../templates/admin/listaHabitaciones.phtml';
    }

    public function formHabitacionView($habitacion = null, $tipos = [], $hoteles = []) {
        $habitacion = $habitacion;
        $tipos = $tipos;
        $hoteles = $hoteles;
        require __DIR__ . '/../../templates/admin/formHabitacion.phtml';
    }

    public function listaTiposView($tipos) {
        $tipos = $tipos;
        require __DIR__ . '/../../templates/admin/listaTipo.phtml';
    }

    public function formTipoView($tipo = null) {
        $tipo = $tipo;
        require __DIR__ . '/../../templates/admin/formTipo.phtml';
    }

    public function listaHotelesView($hoteles) {
        $hoteles = $hoteles;
        require __DIR__ . '/../../templates/admin/listaHoteles.phtml';
    }

    public function formHotelView($hotel = null) {
        $hotel = $hotel;
        require __DIR__ . '/../../templates/admin/formHotel.phtml';
    }

    public function loginView($error = null) {
        $error = $error;
        require __DIR__ . '/../../templates/admin/login.phtml';
    }

}
