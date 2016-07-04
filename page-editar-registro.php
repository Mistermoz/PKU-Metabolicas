<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           page.php
 * @package        Responsive
 * @author         Emil Uzelac
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/page.php
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */

get_header(); ?>

<div id="content" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">

  <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

      <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="post-entry">
          <label for="nombre">Fecha de Lectura</label>
          <br />
          <input type="text" name="flectura" id="flectura" value="" placeholder="dd-mm-aaaa" onChange="buscar_pacientes();"/><br />
          <div id="registros"></div>
          <div id="respuesta"></div>
          <div id="resultado"></div>
          <div id="log"></div>
                </div><!-- end of .post-entry -->

        <?php get_template_part( 'post-data' ); ?>

      </div>

        <?php
    endwhile;

  endif;
  ?>

</div><!-- end of #content
function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
 -->
<script>
  //jQuery( document ).tooltip();

  jQuery( "#flectura" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      });
  function buscar_pacientes(){
    jQuery('#respuesta').html(' ');
    jQuery("#log").text(' ');
    jQuery("#nombre_paciente").html('<option>Cargando...</option>');
    var fecha_pacientes = jQuery('#flectura').val();
    jQuery.ajax({
      type: "POST",
      data: {'action':'busca_pacientes','fecha_pacientes':fecha_pacientes},
      dataType: "json",
      url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
      beforeSend: function () {
                        jQuery("#resultado").html("Procesando, espere por favor...");
            },
      success: mostrarPacientes,
      timeout: 35000,
      error: errorEnvio
    });
  }

  function errorEnvio(e) {
    jQuery("#log").text("Erro envio datos");
    e.preventDefault();
  }

  function mostrarPacientes( aDatos )
  {
    if(aDatos[0].nombres !='Sin Datos'){
      var op_nombres = '<div class="box_nom_campos"><label class="nom_campo" id="nro">Nro.</label><label class="nom_campo" id="nom">Nombre</label><label class="nom_campo aminoacidos">Fen</label><label class="nom_campo aminoacidos msud">Msud</label><label class="nom_campo aminoacidos">Tir</label><label class="nom_campo fech">F. Muestra</label></div>';
      for( var c=1; c <= aDatos.length; c++ )
      {

         op_nombres +='<div id="registro'+c+'" class="registro registro'+c+'" rel="'+c+'">';
         op_nombres +='<div class="numero"><label class="nro_paciente">'+c+'.</label><input type="checkbox" id="nro" name="nro'+c+'" value=""/></div>';
         op_nombres +='<input type="hidden" name="id'+c+'" value="'+aDatos[c-1].id+'" /><label class="nombre'+c+' nombre" >'+aDatos[c-1].nombres+'</label><input type="text" class="amino fenil" value="'+aDatos[c-1].fenil+'" name="fenil'+c+'"><input type="text" class="amino msud" value="'+aDatos[c-1].msud+'" name="msud'+c+'"><input type="text" class="amino tir" value="'+aDatos[c-1].tir+'" name="tir'+c+'"><input type="text" name="fmuestra'+c+'" class="fmuestra fecha" value="'+aDatos[c-1].fmuestra+'" placeholder="dd-mm-aaaa"></div>';
      }
       op_nombres +='<input type="button" name="enviar" id="enviar" value="Enviar Datos" onclick="actualiza_pacientes();" />';
       op_nombres +='<input type="button" name="eliminar" id="eliminar" value="Eliminar" onclick="eliminar_paciente();" />';
    }else {
      op_nombres = 'Sin registros';
    }

    jQuery("#registros").html(op_nombres);
    jQuery( ".fecha" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      });
    jQuery("#resultado").html("OK");
  }

  function actualiza_pacientes(e){
      var fecha =  jQuery('#flectura').val();
      if(jQuery('#flectura').val() == '' || fecha.length < 10){
        alert('Ingrese Fecha Correcta') ; return false;
      }
      var id = new Array();
      var fenil = new Array();
      var msud = new Array();
      var tir = new Array();
      var fmuestra = new Array();
      var c = jQuery("#registros > .registro").length;
      for(j=1; j<=c; j++) {
        id[j-1] = jQuery('input[name="id'+j+'"]').val();
        fenil[j-1] = jQuery('input[name="fenil'+j+'"]').val();
        msud[j-1] =jQuery('input[name="msud'+j+'"]').val();
        tir[j-1] = jQuery('input[name="tir'+j+'"]').val();
        fmuestra[j-1] =jQuery('input[name="fmuestra'+j+'"]').val();
      }
      jQuery("#log").text(' ');
      jQuery.ajax({
        type: "POST",
            cache: true,
        data:
        {'action':'actualizar_registros', 'id' : id, 'fenil' : fenil, 'msud' : msud, 'tir' : tir, 'fmuestra' : fmuestra},
        url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
        dataType: "json",
        beforeSend: function () {
                   jQuery("#resultado").html("Procesando, espere por favor...");
        },
        success:
          mostrarResultados,
          timeout: 35000,
          error: errorEnvio
      });
      e.preventDefault();
    }

    /*** Funcion mostrar resultados ****/
    function mostrarResultados( aDatos ) {
      var i = aDatos.length;
      var resultado = '';
      for(j = 0; j < i ; j++){
        resultado += '* '+aDatos[j]+'<br>';
      }
      jQuery("#resultado").html(resultado);
      jQuery('#flectura').val('');
      jQuery('#registros').html(' ');
    }

  function eliminar_paciente(){
    var reg = new Array();
    var c = jQuery("#registros > .registro").length;
    var k = 0, m = 0;
    for(var i = 1; i <= c ; i++) {
      if(jQuery('input[name="nro'+i+'"]').prop('checked')){
        reg[k] = jQuery('input[name="id'+i+'"]').val();
        k++;
      }
    }
    if (confirm('¿Seguro que desea borrar estos registros?')) {
      jQuery('#respuesta').html('');
      jQuery("#log").text(' ');
      jQuery.ajax({
        type: "POST",
            cache: true,
        data:
        {'action':'eliminar_registros', 'id' : reg},
        url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
        dataType: "json",
        beforeSend: function () {
                   jQuery("#resultado").html("Procesando, espere por favor...");
        },
        success:
          resultadoDelete,
          timeout: 35000,
          error: errorEnvio
      });
    }
  }

   /*** Funcion mostrar resultados ****/
    function resultadoDelete( aDatos ) {
      var i = aDatos.length;
      var resultado = '';
      for(j = 0; j < i ; j++){
        resultado += '* '+aDatos[j]+'<br>';
      }
      jQuery("#resultado").html(resultado);
      jQuery('#flectura').val('');
      jQuery('#registros').html(' ');
    }

</script>
<?php get_footer(); ?>