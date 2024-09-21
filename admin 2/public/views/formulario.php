<form id="calculadora-form" class="calculadora-tarifas-form">
    <div class="form-container">
        <div class="form-row">
            <div class="form-group">
                <label for="pasarela">Pasarela de Pago:</label>
                <select id="pasarela" required>
                    <option value="" disabled selected>Seleccione una pasarela</option>
                    <option value="ePayco">ePayco</option>
                    <option value="Wompi">Wompi</option>
                    <option value="PayU">PayU Latam</option>
                    <option value="MercadoPago">Mercado Pago</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipoPago">Tipo de Pago:</label>
                <select id="tipoPago" required>
                    <option value="" disabled selected>Seleccione un tipo de pago</option>
                    <option value="tarjetaCredito">Tarjeta de Crédito</option>
                    <option value="PSE">PSE</option>
                </select>
            </div>
        </div>

        <div id="epayco-options" class="form-row hidden">
            <div class="form-group">
                <label for="cuentaDavivienda">¿Tienes cuenta en Davivienda o Daviplata?</label>
                <select id="cuentaDavivienda">
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>

        <div id="payu-options" class="form-row hidden">
            <div class="form-group">
                <label for="afterSept2024">¿Te vinculaste después del 9 de septiembre de 2024?</label>
                <select id="afterSept2024">
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>

       <div id="mercado-pago-options" class="form-row hidden">
    <div class="form-group">
        <label for="diasMercadoPago">¿En cuántos días quieres recibir el dinero?</label>
        <select id="diasMercadoPago">
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="instantaneo">Al instante</option>
            <option value="7">En 7 días</option>
            <option value="14">En 14 días</option>
        </select>
    </div>
</div>

        <div class="form-row">
            <div class="form-group">
                <label for="monto">Monto de la Transacción:</label>
                <div class="input-group">
                    <input type="text" id="monto" required placeholder="Ej: 150.000">
                </div>
            </div>
            <div class="form-group">
                <label for="ventaConIva">¿La venta está sujeta a IVA?</label>
                <select id="ventaConIva" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>

        <div id="iva-rate-field" class="form-row hidden">
            <div class="form-group">
                <label for="ivaRate">Porcentaje de IVA aplicado a la venta (%):</label>
                <input type="number" id="ivaRate" min="0" max="100" step="0.01" value="19">
            </div>
        </div>

        <div class="form-row">
            <button type="submit" class="button button-primary">Calcular</button>
        </div>
    </div>
</form>

<div id="resultado"></div>
