<?php

class TiposView {

    public function listView($tipos) {
        $tipos = $tipos;
        require __DIR__ . '/../../templates/public/listadoTipos.phtml';
    }

}
