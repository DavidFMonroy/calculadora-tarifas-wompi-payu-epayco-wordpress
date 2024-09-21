<?php
/*
Plugin Name: Calculadora de Tarifas de Pasarelas de Pago | SEO Master S.A.S
Description: Calculadora completa de tarifas y retenciones de pasarelas de pago en Colombia.
Version: 1.0.0
Author: David Fernando Monroy Morera
Text Domain: seomaster.com.co
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

// Definir constantes
define( 'CALCULADORA_TARIFAS_VERSION', '1.0' );
define( 'CALCULADORA_TARIFAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CALCULADORA_TARIFAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Incluir archivos necesarios
require_once CALCULADORA_TARIFAS_PLUGIN_DIR . 'includes/class-calculadora-tarifas-model.php';
require_once CALCULADORA_TARIFAS_PLUGIN_DIR . 'includes/class-calculadora-tarifas-controller.php';
require_once CALCULADORA_TARIFAS_PLUGIN_DIR . 'includes/class-calculadora-tarifas-shortcode.php';
require_once CALCULADORA_TARIFAS_PLUGIN_DIR . 'admin/class-calculadora-tarifas-admin.php';

// Inicializar el plugin
function calculadora_tarifas_init() {
    $model = new Calculadora_Tarifas_Model();
    $controller = new Calculadora_Tarifas_Controller( $model );
    $controller->init();

    $admin = new Calculadora_Tarifas_Admin( $model );
}
add_action( 'plugins_loaded', 'calculadora_tarifas_init' );