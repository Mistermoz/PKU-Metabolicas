<?php
get_header(); ?>
<?php
	  
    // Utilizamos la función de PHP 'json_encode()' para convertir el array a formato JSON antes de devolverlo
	$aDatos = array();
	$aDatos[] = array( 'nombre' => 'uno', 'estado' => 'dos');
    echo json_encode($aDatos);
?>
<?php get_footer(); ?>