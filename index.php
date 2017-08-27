<?php

$binary_array_example = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1,1,1,1,1,0,0);

print float_reduce($binary_array_example);
print "\n";

function float_reduce(array $binary_rep) {

    if (count($binary_rep) != 32 || !array_reduce($binary_rep, "is_boolean_rep", true)) {
        throw new Exception("No se puede considerar como una representación de un punto flotante");
    }

    return calculate_sign_factor($binary_rep) 
        * calculate_fraction_factor($binary_rep)
        * calculate_exponent_factor($binary_rep);
}

function is_boolean_rep($carry, $item) {
    $carry &= $item === 1 || $item === 0 || $item === '1' || $item === '0';
    return $carry;
}

function calculate_sign_factor($binary_rep) {
    #extraigo el bit de signo
    $sign = array_pop($binary_rep);

    return pow(-1, $sign);

}

function calculate_fraction_factor($binary_rep) {

    $accumulator = 1;

    #hago el reduce de forma discreta porque necesito "i", 
    #es decir la iteración actual, por otra parte el for me
    #ahorra el slice

    for($i=1; $i<24; $i++) {
        $bit = $binary_rep[23 - $i];
        $accumulator += $bit * pow(2, -1 * $i);
    }
    return $accumulator;

}

function calculate_exponent_factor($binary_rep) {
    
    #extraigo el exponente
    #evito funciones custom

    $exponent_string = join('',array_slice($binary_rep, 23, 8));
    $exponent = bindec(strrev($exponent_string));
    return pow(2, $exponent - 127);
}

?>
