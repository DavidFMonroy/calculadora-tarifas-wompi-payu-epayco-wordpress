<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

class Calculadora_Tarifas_Controller {

    private $model;

    public function __construct( $model ) {
        $this->model = $model;
    }

    public function init() {
        // Registrar shortcode
        $shortcode = new Calculadora_Tarifas_Shortcode( $this->model );
        add_shortcode( 'calculadora_tarifas', array( $shortcode, 'render_shortcode' ) );

        // Encolar scripts y estilos
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Registrar el menú de administración
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
    }

    public function enqueue_scripts() {
        // Encolar estilos
        wp_enqueue_style( 'calculadora-tarifas-css', CALCULADORA_TARIFAS_PLUGIN_URL . 'public/css/calculadora-tarifas.css', array(), CALCULADORA_TARIFAS_VERSION );

        // Encolar scripts
        wp_enqueue_script( 'calculadora-tarifas-js', CALCULADORA_TARIFAS_PLUGIN_URL . 'public/js/calculadora-tarifas.js', array( 'jquery' ), CALCULADORA_TARIFAS_VERSION, true );

        // Pasar datos al script
        $tarifas = $this->model->get_tarifas();
        wp_localize_script( 'calculadora-tarifas-js', 'calculadoraTarifasData', $tarifas );
    }

    public function register_admin_menu() {
        add_menu_page(
            'Calculadora Tarifas',         // Título de la página
            'Calculadora Tarifas',         // Título del menú
            'manage_options',              // Capacidad requerida
            'calculadora-tarifas',         // Slug del menú
            array( $this, 'render_admin_page' ), // Función de callback
            'dashicons-calculator',        // Icono del menú
            6                              // Posición en el menú
        );
    }

    public function render_admin_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Procesar formulario al enviar
        if ( isset( $_POST['calculadora_tarifas_nonce'] ) && wp_verify_nonce( $_POST['calculadora_tarifas_nonce'], 'guardar_tarifas' ) ) {
            $new_tarifas = $_POST['tarifas'];
            // Sanitizar y validar los datos antes de guardarlos
            $sanitized_tarifas = $this->sanitize_tarifas( $new_tarifas );
            $this->model->update_tarifas( $sanitized_tarifas );
            echo '<div class="notice notice-success is-dismissible"><p>Tarifas actualizadas correctamente.</p></div>';
        }

        // Obtener tarifas actuales
        $tarifas = $this->model->get_tarifas();

        include CALCULADORA_TARIFAS_PLUGIN_DIR . 'admin/views/admin-display.php';
    }

    private function sanitize_tarifas( $tarifas ) {
        // Aquí puedes sanitizar y validar cada campo de tarifas
        // Por simplicidad, asumiremos que los datos son numéricos y los convertiremos a valores float
        array_walk_recursive( $tarifas, function( &$value, $key ) {
            // Si es un porcentaje, lo dividimos entre 100
            if ( strpos( $key, 'porcentaje' ) !== false || strpos( $key, 'rate' ) !== false || strpos( $key, 'renta' ) !== false || strpos( $key, 'ica' ) !== false || strpos( $key, 'iva' ) !== false ) {
                $value = floatval( $value ) / 100;
            } else {
                $value = floatval( $value );
            }
        } );
        return $tarifas;
    }
}
