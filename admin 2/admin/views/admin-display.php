<div class="wrap">
    <h1>Configuración de Tarifas</h1>
    <form method="post">
        <?php wp_nonce_field( 'guardar_tarifas', 'calculadora_tarifas_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th scope="row">IVA General (%)</th>
                <td><input type="number" name="tarifas[IVA_RATE]" value="<?php echo esc_attr( $tarifas['IVA_RATE'] * 100 ); ?>" step="0.01" min="0" max="100"></td>
            </tr>
            <?php foreach ( $tarifas['tarifas'] as $pasarela => $datos_pasarela ) : ?>
                <tr>
                    <th colspan="2"><h2><?php echo esc_html( $pasarela ); ?></h2></th>
                </tr>
                <?php foreach ( $datos_pasarela as $clave => $valor ) : ?>
                    <?php if ( is_array( $valor ) ) : ?>
                        <?php if ( $clave === 'retenciones' ) : ?>
                            <tr>
                                <th colspan="2"><strong>Retenciones</strong></th>
                            </tr>
                            <?php foreach ( $valor as $retencion => $tasa ) : ?>
                                <tr>
                                    <th scope="row"><?php echo esc_html( ucfirst( str_replace('_', ' ', $retencion) ) ); ?> (%)</th>
                                    <td><input type="number" name="tarifas[tarifas][<?php echo esc_attr( $pasarela ); ?>][retenciones][<?php echo esc_attr( $retencion ); ?>]" value="<?php echo esc_attr( $tasa * 100 ); ?>" step="0.01" min="0" max="100"></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php elseif ( $clave === 'withdrawalFee' ) : ?>
                            <tr>
                                <th colspan="2"><strong>Costo de Retiro</strong></th>
                            </tr>
                            <tr>
                                <th scope="row">Tarifa Fija</th>
                                <td><input type="number" name="tarifas[tarifas][<?php echo esc_attr( $pasarela ); ?>][withdrawalFee][fijo]" value="<?php echo esc_attr( $valor['fijo'] ); ?>" step="0.01" min="0"></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th colspan="2"><strong><?php echo esc_html( ucfirst( str_replace('_', ' ', $clave ) ) ); ?></strong></th>
                            </tr>
                            <?php foreach ( $valor as $metodo_pago => $tarifa ) : ?>
                                <?php if ( is_array( $tarifa ) ) : ?>
                                    <tr>
                                        <th colspan="2"><em><?php echo esc_html( ucfirst( str_replace('_', ' ', $metodo_pago ) ) ); ?></em></th>
                                    </tr>
                                    <?php foreach ( $tarifa as $k => $v ) : ?>
                                        <?php if ( is_array( $v ) ) : ?>
                                            <tr>
                                                <th colspan="2"><em><?php echo esc_html( ucfirst( str_replace('_', ' ', $k ) ) ); ?></em></th>
                                            </tr>
                                            <?php foreach ( $v as $subk => $subv ) : ?>
                                                <tr>
                                                    <th scope="row"><?php echo esc_html( ucfirst( str_replace('_', ' ', $subk ) ) ); ?> <?php echo ($subk === 'porcentaje') ? '(%)' : ''; ?></th>
                                                    <td><input type="number" name="tarifas[tarifas][<?php echo esc_attr( $pasarela ); ?>][<?php echo esc_attr( $clave ); ?>][<?php echo esc_attr( $metodo_pago ); ?>][<?php echo esc_attr( $k ); ?>][<?php echo esc_attr( $subk ); ?>]" value="<?php echo esc_attr( ( $subk === 'porcentaje' ) ? $subv * 100 : $subv ); ?>" step="0.01" min="0" <?php echo ($subk === 'porcentaje') ? 'max="100"' : ''; ?>></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <th scope="row"><?php echo esc_html( ucfirst( str_replace('_', ' ', $k ) ) ); ?> <?php echo ($k === 'porcentaje') ? '(%)' : ''; ?></th>
                                                <td><input type="number" name="tarifas[tarifas][<?php echo esc_attr( $pasarela ); ?>][<?php echo esc_attr( $clave ); ?>][<?php echo esc_attr( $metodo_pago ); ?>][<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( ( $k === 'porcentaje' ) ? $v * 100 : $v ); ?>" step="0.01" min="0" <?php echo ($k === 'porcentaje') ? 'max="100"' : ''; ?>></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <!-- Otros casos -->
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <!-- Valores simples -->
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
