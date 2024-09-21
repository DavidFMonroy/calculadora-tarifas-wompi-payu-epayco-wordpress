<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

class Calculadora_Tarifas_Admin {

    private $model;

    public function __construct( $model ) {
        $this->model = $model;

        // Registrar el menú de administración
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );

        // Registrar acciones adicionales si es necesario
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
        // Por simplicidad, asumiremos que los datos son numéricos
        array_walk_recursive( $tarifas, function( &$value, $key ) {
            $value = floatval( $value );
        } );
        return $tarifas;
    }
}
