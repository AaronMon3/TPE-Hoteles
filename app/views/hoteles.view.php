<?php

class HotelesView {

    public function listView($hoteles) {
        $hoteles = $hoteles;
        require __DIR__ . '/../../templates/admin/listaHoteles.phtml';
    }

    public function formView($hotel = null) {
        $hotel = $hotel;
        require __DIR__ . '/../../templates/admin/formHotel.phtml';
    }

}
