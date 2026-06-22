<?php
/**
 * Limpia el RUT dejando solo números y K (mayúscula).
 */
function limpiarRut($rut) {
    $rut = strtoupper(trim($rut));
    return preg_replace('/[^0-9K]/', '', $rut);
}

/**
 * Valida un RUT chileno verificando su dígito verificador.
 * Acepta formatos con o sin puntos/guión: "12.345.678-9" o "123456789"
 */
function validarRutChileno($rut) {
    $rut = limpiarRut($rut);

    if (strlen($rut) < 2) {
        return false;
    }

    $dv     = substr($rut, -1);
    $cuerpo = substr($rut, 0, -1);

    if (strlen($cuerpo) < 7 || strlen($cuerpo) > 8 || !ctype_digit($cuerpo)) {
        return false;
    }

    $suma    = 0;
    $multiplo = 2;

    for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
        $suma += intval($cuerpo[$i]) * $multiplo;
        $multiplo = ($multiplo === 7) ? 2 : $multiplo + 1;
    }

    $resto = 11 - ($suma % 11);

    if ($resto === 11) {
        $dvEsperado = '0';
    } elseif ($resto === 10) {
        $dvEsperado = 'K';
    } else {
        $dvEsperado = (string) $resto;
    }

    return $dv === $dvEsperado;
}
?>
