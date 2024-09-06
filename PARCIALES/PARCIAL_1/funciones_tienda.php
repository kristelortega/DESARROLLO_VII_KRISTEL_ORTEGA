<?php
//Creación de funciones

//Funcion calcular descuentos
funtion calcular_descuento($total){
    return $total < 100 ? 0 : ($total <= 500 ? $total  * 0.05 : ($total <= 1000 ? $total * 0.10 : $total * 0.15));
}

//Funcion aplicar impuestos
funtion aplicar_impuesto($subtotal) {
    return $subTotal * 0.07;
}

//Funcion calcular total
funtion calcular_total($subtotal, $descuento, $impuesto) {
    return $subtotal - $descuento + $impuesto;
}

>?