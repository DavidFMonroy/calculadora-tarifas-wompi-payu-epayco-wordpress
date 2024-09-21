<?php

class Calculadora_Tarifas_Model {

    private $options_name = 'calculadora_tarifas_options';

    public function get_tarifas() {
        $tarifas = get_option( $this->options_name );

        // Si no hay tarifas guardadas, usar valores por defecto
        if ( ! $tarifas ) {
            $tarifas = $this->get_default_tarifas();
            update_option( $this->options_name, $tarifas );
        }

        return $tarifas;
    }

    public function update_tarifas( $new_tarifas ) {
        update_option( $this->options_name, $new_tarifas );
    }

    public function get_default_tarifas() {
        return array(
            'IVA_RATE' => 0.19,
            'tarifas' => array(
                'ePayco' => array(
                    'cuentaDavivienda' => array(
                        'tarjetaCredito' => array(
                            'porcentaje' => 2.68,
                            'fijo' => 900
                        ),
                        'PSE' => array(
                            'porcentaje' => 2.68,
                            'fijo' => 900,
                            'menor60k' => array(
                                'porcentaje' => 0,
                                'fijo' => 2000
                            )
                        )
                    ),
                    'cuentaOtroBanco' => array(
                        'tarjetaCredito' => array(
                            'porcentaje' => 2.99,
                            'fijo' => 900
                        ),
                        'PSE' => array(
                            'porcentaje' => 2.99,
                            'fijo' => 900,
                            'menor60k' => array(
                                'porcentaje' => 0,
                                'fijo' => 2000
                            )
                        )
                    ),
                    'retenciones' => array(
                        'renta' => 0.015,
                        'ica' => 0.002,
                        'retencion_iva' => 0.15
                    ),
                    'withdrawalFee' => array(
                        'fijo' => 6500 // + IVA
                    )
                ),
                'Wompi' => array(
                    'tarjetaCredito' => array(
                        'porcentaje' => 2.65,
                        'fijo' => 700
                    ),
                    'PSE' => array(
                        'porcentaje' => 2.65,
                        'fijo' => 700
                    ),
                    'retenciones' => array(
                        'renta' => 0.015,
                        'ica' => 0.002,
                        'retencion_iva' => 0.15
                    )
                ),
                'PayU' => array(
                    'afterSept2024' => array(
                        'tarjetaCredito' => array(
                            'porcentaje' => 3.29,
                            'fijo' => 300
                        ),
                        'PSE' => array(
                            'porcentaje' => 3.29,
                            'fijo' => 300
                        )
                    ),
                    'beforeSept2024' => array(
                        'tarjetaCredito' => array(
                            'porcentaje' => 3.49,
                            'fijo' => 800
                        ),
                        'PSE' => array(
                            'porcentaje' => 3.49,
                            'fijo' => 800
                        )
                    ),
                    'retenciones' => array(
                        'renta' => 0.015,
                        'ica' => 0.00414,
                        'retencion_iva' => 0.15
                    ),
                    'withdrawalFee' => array(
                        'fijo' => 6500 // + IVA, aplica a partir del 4to retiro
                    )
                ),
                'MercadoPago' => array(
                    'instantaneo' => array(
                        'porcentaje' => 3.29,
                        'fijo' => 800
                    ),
                    '7' => array(
                        'porcentaje' => 2.99,
                        'fijo' => 800
                    ),
                    '14' => array(
                        'porcentaje' => 2.79,
                        'fijo' => 800
                    ),
                    'retenciones' => array(
                        'renta' => 0.015,
                        'ica' => 0.002,
                        'retencion_iva' => 0.15
                    )
                )
            )
        );
    }
}
