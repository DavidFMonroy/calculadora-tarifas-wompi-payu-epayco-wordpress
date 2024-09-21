<?php

class Calculadora_Tarifas_Shortcode {

    private $model;

    public function __construct( $model ) {
        $this->model = $model;
    }

    public function render_shortcode() {
        ob_start();
        include CALCULADORA_TARIFAS_PLUGIN_DIR . 'public/views/calculadora-display.php';
        return ob_get_clean();
    }
}
