(function($) {
    // Configuración de tarifas y tasas de retención
    const IVA_RATE = 0.19; // Tasa de IVA
    const tarifas = {
    ePayco: {
        cuentaDavivienda: {
            tarjetaCredito: { porcentaje: 2.68, fijo: 900 },
            PSE: { porcentaje: 2.68, fijo: 900 }
        },
        cuentaOtroBanco: {
            tarjetaCredito: { porcentaje: 2.99, fijo: 900 },
            PSE: { porcentaje: 2.99, fijo: 900 }
        },
        PSE: {
            menor60k: { porcentaje: 0, fijo: 2000 }
        },
        retenciones: {
            renta: 0.015,
            ica: 0.002,
            retencion_iva: 0.15
        }
    },
    Wompi: {
        tarjetaCredito: { porcentaje: 2.65, fijo: 700 },
        PSE: { porcentaje: 2.65, fijo: 700 },
        retenciones: {
            renta: 0.015,
            ica: 0.002,
            retencion_iva: 0.15
        }
    },
    PayU: {
        afterSept2024: {
            tarjetaCredito: { porcentaje: 3.29, fijo: 300 },
            PSE: { porcentaje: 3.29, fijo: 300 }
        },
        beforeSept2024: {
            tarjetaCredito: { porcentaje: 3.49, fijo: 800 },
            PSE: { porcentaje: 3.49, fijo: 800 }
        },
        retenciones: {
            renta: 0.015,
            ica: 0.002,
            retencion_iva: 0.15
        }
    },
    MercadoPago: {
        instantaneo: { porcentaje: 3.29, fijo: 800 },
        '7': { porcentaje: 2.99, fijo: 800 },
        '14': { porcentaje: 2.79, fijo: 800 },
        retenciones: {
            renta: 0.015,
            ica: 0.002,
            retencion_iva: 0.15
        }
    }
};

// Verificar la estructura del objeto tarifas
console.log("Tarifas cargadas en el JavaScript:", tarifas);

    // Función para limpiar y convertir un valor a número
    function parseNumber(value) {
        let sanitizedValue = value.replace(/\./g, '').replace(/,/g, '.');
        return parseFloat(sanitizedValue);
    }

    // Función para formatear números a moneda colombiana
    function formatNumber(value) {
        let num = parseFloat(value);
        if (isNaN(num)) {
            return value;
        }
        return num.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // Función para calcular las tarifas y retenciones
    function calcularTarifa(pasarela, tipoPago, monto, isIvaSale, ivaSaleRate, additionalOptions) {
        const ivaRateSale = isIvaSale ? ivaSaleRate / 100 : 0;
        let tarifa;
        let retenciones;

        switch (pasarela) {
            case 'ePayco':
                const cuentaDavivienda = additionalOptions.cuentaDavivienda;
                const tarifasEpayco = cuentaDavivienda ? tarifas.ePayco.cuentaDavivienda : tarifas.ePayco.cuentaOtroBanco;
                retenciones = tarifas.ePayco.retenciones;
                tarifa = (tipoPago === 'PSE' && monto < 60000) ? tarifasEpayco.PSE.menor60k : tarifasEpayco[tipoPago];
                break;
            case 'Wompi':
                tarifa = tarifas.Wompi[tipoPago];
                retenciones = tarifas.Wompi.retenciones;
                break;
            case 'PayU':
                const afterSept2024 = additionalOptions.afterSept2024;
                const tarifasPayU = afterSept2024 ? tarifas.PayU.afterSept2024 : tarifas.PayU.beforeSept2024;
                tarifa = tarifasPayU[tipoPago];
                retenciones = tarifas.PayU.retenciones;
                break;
            case 'MercadoPago':
    const dias = additionalOptions.dias;
    console.log("Días seleccionados para Mercado Pago:", dias); // Verificar el valor de días
    console.log("Tarifas disponibles en Mercado Pago:", tarifas.MercadoPago); // Verificar el objeto tarifas

    // Validar si el valor de dias es correcto y si existe en tarifas
    if (!dias || !tarifas.MercadoPago[dias]) {
        alert('Por favor, selecciona un período de tiempo válido para Mercado Pago.');
        return null; // Evitar cálculos si no se selecciona un valor válido
    }

    // Continuar con el cálculo si todo es válido
    tarifa = tarifas.MercadoPago[dias];
    retenciones = tarifas.MercadoPago.retenciones;
    break;

            default:
                throw new Error('Pasarela no válida');
        }

        const porcentajeTarifa = (tarifa.porcentaje / 100) * monto;
        const tarifaFija = tarifa.fijo;
        const subtotalTarifas = porcentajeTarifa + tarifaFija;
        const ivaTarifas = subtotalTarifas * IVA_RATE;
        const totalTarifas = subtotalTarifas + ivaTarifas;

        let retencionRenta = 0;
        let retencionIca = 0;
        let retencionIva = 0;
        if (tipoPago === 'tarjetaCredito') {
            const baseAmount = monto;
            const ivaVenta = baseAmount * ivaRateSale;
            retencionRenta = baseAmount * retenciones.renta;
            retencionIca = baseAmount * retenciones.ica;
            retencionIva = ivaVenta * retenciones.retencion_iva;
        }

        const totalRetenciones = retencionRenta + retencionIca + retencionIva;
        const montoRecibido = monto - totalTarifas - totalRetenciones;

        return {
            porcentajeTarifa,
            tarifaFija,
            subtotalTarifas,
            ivaTarifas,
            totalTarifas,
            retencionRenta,
            retencionIca,
            retencionIva,
            totalRetenciones,
            montoRecibido,
            porcentajeComision: tarifa.porcentaje.toFixed(2)
        };
    }

    // Inicialización y manejadores de eventos
    $(document).ready(function() {
        // Manejador de cambio para seleccionar pasarela de pago
        $('#pasarela').on('change', function() {
            const pasarela = $(this).val();
            
            // Oculta todas las opciones condicionales
            $('#epayco-options').addClass('hidden');
            $('#payu-options').addClass('hidden');
            $('#mercado-pago-options').addClass('hidden');

            // Muestra las opciones relevantes según la pasarela seleccionada
            if (pasarela === 'ePayco') {
                $('#epayco-options').removeClass('hidden');
            } else if (pasarela === 'PayU') {
                $('#payu-options').removeClass('hidden');
            } else if (pasarela === 'MercadoPago') {
                $('#mercado-pago-options').removeClass('hidden');
            }
        });

        // Manejador de cambio para seleccionar si la venta está sujeta a IVA
        $('#ventaConIva').on('change', function() {
            const isIvaSale = $(this).val() === 'si';
            if (isIvaSale) {
                $('#iva-rate-field').removeClass('hidden');
            } else {
                $('#iva-rate-field').addClass('hidden');
            }
        });

        // Manejador de envío del formulario
        $('#calculadora-form').on('submit', function(e) {
            e.preventDefault();

            const pasarela = $('#pasarela').val();
            const tipoPago = $('#tipoPago').val();
            const montoInput = $('#monto').val();
            const monto = parseNumber(montoInput);
            const ventaConIva = $('#ventaConIva').val();
            const isIvaSale = ventaConIva === 'si';
            const ivaRateInput = parseFloat($('#ivaRate').val().replace(',', '.')) || 0;
            let additionalOptions = {};

            if (!pasarela || !tipoPago || isNaN(monto) || monto <= 0 || !ventaConIva) {
                alert('Por favor, completa todos los campos con valores válidos.');
                return;
            }

            if (isIvaSale && (isNaN(ivaRateInput) || ivaRateInput < 0 || ivaRateInput > 100)) {
                alert('Por favor, ingresa un porcentaje de IVA válido.');
                return;
            }

            if (pasarela === 'ePayco') {
                const cuentaDavivienda = $('#cuentaDavivienda').val();
                if (!cuentaDavivienda) {
                    alert('Por favor, indica si tienes cuenta en Davivienda o Daviplata.');
                    return;
                }
                additionalOptions.cuentaDavivienda = cuentaDavivienda === 'si';
            } else if (pasarela === 'PayU') {
                const afterSept2024 = $('#afterSept2024').val();
                if (!afterSept2024) {
                    alert('Por favor, indica si te vinculaste después del 9 de septiembre de 2024.');
                    return;
                }
                additionalOptions.afterSept2024 = afterSept2024 === 'si';
            } else if (pasarela === 'MercadoPago') {
                const dias = $('#diasMercadoPago').val();
                if (!dias) {
                    alert('Por favor, selecciona la cantidad de días.');
                    return;
                }
                additionalOptions.dias = dias;
                console.log("Días seleccionados para cálculo:", dias); // Depuración
            }

            const resultado = calcularTarifa(pasarela, tipoPago, monto, isIvaSale, ivaRateInput, additionalOptions);

            // Verificar si hay error en resultado
            if (!resultado) {
                return;
            }

            const montoRounded = Math.round(monto);
            const porcentajeTarifaRounded = Math.round(resultado.porcentajeTarifa);
            const tarifaFijaRounded = Math.round(resultado.tarifaFija);
            const subtotalTarifasRounded = Math.round(resultado.subtotalTarifas);
            const ivaTarifasRounded = Math.round(resultado.ivaTarifas);
            const totalTarifasRounded = Math.round(resultado.totalTarifas);
            const retencionRentaRounded = Math.round(resultado.retencionRenta);
            const retencionIcaRounded = Math.round(resultado.retencionIca);
            const retencionIvaRounded = Math.round(resultado.retencionIva);
            const totalRetencionesRounded = Math.round(resultado.totalRetenciones);
            const montoRecibidoRounded = Math.round(resultado.montoRecibido);

            const montoFormatted = formatNumber(montoRounded);
            const porcentajeTarifaFormatted = formatNumber(porcentajeTarifaRounded);
            const tarifaFijaFormatted = formatNumber(tarifaFijaRounded);
            const subtotalTarifasFormatted = formatNumber(subtotalTarifasRounded);
            const ivaTarifasFormatted = formatNumber(ivaTarifasRounded);
            const totalTarifasFormatted = formatNumber(totalTarifasRounded);
            const retencionRentaFormatted = formatNumber(retencionRentaRounded);
            const retencionIcaFormatted = formatNumber(retencionIcaRounded);
            const retencionIvaFormatted = formatNumber(retencionIvaRounded);
            const totalRetencionesFormatted = formatNumber(totalRetencionesRounded);
            const montoRecibidoFormatted = formatNumber(montoRecibidoRounded);

            $('#resultado').html(`
                <strong>Monto Inicial:</strong> ${montoFormatted}<br>
                <strong>Tarifas de la Pasarela:</strong><br>
                - Tarifa por Porcentaje (${resultado.porcentajeComision.replace('.', ',')}%): ${porcentajeTarifaFormatted}<br>
                - Tarifa Fija: ${tarifaFijaFormatted}<br>
                - Subtotal Tarifas: ${subtotalTarifasFormatted}<br>
                - IVA sobre Tarifas (${(IVA_RATE * 100).toFixed(2).replace('.', ',')}%): ${ivaTarifasFormatted}<br>
                <strong>Total Tarifas Descontadas:</strong> ${totalTarifasFormatted}<br><br>
                ${tipoPago === 'tarjetaCredito' ? `
                <strong>Retenciones Aplicadas:</strong><br>
                - Retención de Renta: ${retencionRentaFormatted}<br>
                - Retención de ICA: ${retencionIcaFormatted}<br>
                - Retención de IVA: ${retencionIvaFormatted}<br>
                <strong>Total Retenciones:</strong> ${totalRetencionesFormatted}<br><br>
                ` : `
                <strong>No se aplican retenciones para este tipo de pago.</strong><br><br>
                `}
                <strong>Monto que Recibirás:</strong> ${montoRecibidoFormatted}
            `);
        });
    });
})(jQuery);
