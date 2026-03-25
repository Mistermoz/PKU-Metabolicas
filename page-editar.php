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
           <div id="choice">
            <h3>Crear / Editar paciente</h3>
            <label>Perfil</label><input type="radio" id="op1" name="op" value="0" checked/>
            <label>Registro</label><input type="radio" id="op2" name="op" value="1"/>
          </div>

          <?php
            global $dbh;
            $mi_tabla = 'paciente_info';
            $query = "SELECT Nombre FROM $mi_tabla GROUP BY Nombre";
            $content = $dbh->get_results( $query );
            if ( count($content) > 0 ) {
          ?>
              <label for="nombre" id="titleElegir">Elegir Nombre Paciente</label>
              <select id="el_paciente" data-placeholder="Seleccione paciente">
              <option>Eligir Paciente</option>
              <?php foreach ( $content as $row ) {
                ?>
                <option value="<?php echo $row->Nombre; ?>"><?php echo $row->Nombre; ?></option>
              <?php } ?></select>
          <?php } ?>
          <form id="registro">
          	<label for="Rut">Rut:</label><input type="text" name="rut_registro" id="rut_registro" value="" placeholder="Ingrese Rut" /><br />
          	<label for="Nombre">Nombre:</label><input type="text" name="nombre" id="nombre_registro" value="" placeholder="Ingrese Nombre" /><br />
      			<label for="Fnac">Fecha de Nac.:</label><input class="fecha" type="text" name="fnac_registro" id="fnac_registro" value="" /><br /><br />
      			<input type="button" name="guardar" id="Guardar" value="Guardar" onClick="guardar_paciente();"/><br />
          </form>
          <input type="button" name="enviar" id="Consultar" value="Consultar" onClick="datos_paciente();"/><br />
          <div id="respuesta">
          </div>
          <div id="resultado"></div>
          <div id="log"></div>
                </div><!-- end of .post-entry -->

        <?php get_template_part( 'post-data' ); ?>

      </div>

        <?php
    endwhile;

  endif;
  ?>

</div>
<script src="<?php bloginfo('template_directory'); ?>/chosen/chosen.jquery.js" type="text/javascript"></script>
<script>
  
  function datos_paciente(){
    if(jQuery('#el_paciente').val() == ''){
      alert('Ingrese Nombre') ; return false;
    }

    jQuery("#log").text(' ');
    var nom_paciente = jQuery('#el_paciente').val();
    jQuery.ajax({
      type: "POST",
      data: {'action':'busca_datos','nom_paciente':nom_paciente},
      dataType: "json",
      url: url_ajax,
      beforeSend: function () {
                        jQuery("#resultado").html("Procesando, espere por favor...");
            },
      success: mostrarDatos,
      timeout: 35000,
      error: errorEnvio
    });
  }

  function guardar_paciente () {
  	jQuery("#log").text(' ');
    var nombre = jQuery('#nombre_registro').val();
    var rut = jQuery('#rut_registro').val();
    var fnac = jQuery('#fnac_registro').val();

    if (nombre == '') {
    	errorNombre();
    	return false;
    }

    jQuery.ajax({
      type: "POST",
      data: {'action':'agrega_paciente','rut':rut, 'nombre':nombre,'fnac':fnac },
      dataType: "json",
      url: url_ajax,
      beforeSend: function () {
                 jQuery("#resultado").html("Procesando, espere por favor...");
      },
      success: muestraResultadoNombre,
      timeout: 35000,
      error: errorEnvio
    });
  }

  function update_paciente(){
    jQuery("#log").text(' ');
    var nombre_antiguo = String(jQuery('#el_paciente').val());
    var nombre_nuevo = jQuery('#nombre').val();
    var rut = jQuery('#rut').val();
    var fnac = jQuery('#fnac').val();

    jQuery.ajax({
      type: "POST",
      data:
      {'action':'actualiza_paciente','rut':rut, 'nombre_antiguo':nombre_antiguo,'nombre_nuevo':nombre_nuevo,'fnac':fnac },
      dataType: "json",
      url: url_ajax,
      beforeSend: function () {
                 jQuery("#resultado").html("Procesando, espere por favor...");
      },
      success:
        muestraResultado,
        timeout: 35000,
        error: errorEnvio
    });
  }

  function borrar_paciente(){
    jQuery("#log").text(' ');
    var nombre_antiguo = String(jQuery('#el_paciente').val());

    jQuery.ajax({
      type: "POST",
      data:
      {'action':'borrar_paciente','nombre_antiguo':nombre_antiguo},
      dataType: "json",
      url: url_ajax,
      beforeSend: function () {
                 jQuery("#resultado").html("Procesando, espere por favor...");
      },
      success:
        Resultado,
        timeout: 35000,
        error: errorEnvio
    });
  }

  function listar(){
    jQuery("#log").text(' ');
    jQuery.ajax({
      type: "POST",
      data:
      {'action':'listar'},
      dataType: "json",
      url: url_ajax,
      beforeSend: function () {
                 jQuery("#respuesta").html("");
      },
      success:
        updateSelect,
        timeout: 35000,
        error: errorEnvio
    });
  }

	function muestraResultado( aDatos ) {
    jQuery("#resultado").html(""+aDatos[0].Paciente+"");
    jQuery("#log").text("Ok");

    listar();
  }

  function muestraResultadoNombre( aDatos ) {
  	if (typeof aDatos[0].Error != 'undefined') {
  		if (/Duplicate/i.test(aDatos[0].Error)) {
  			jQuery("#resultado").html("Paciente ya ingresado");
  		}
  	} else {
			jQuery("#resultado").html(""+aDatos[0].Paciente+"");
    	jQuery("#log").text("Ok");
    	cleanRegistro();

    	listar();
    }
  }

  function Resultado( aDatos ) {
    jQuery("#resultado").html(""+aDatos[0].Paciente+"");
    jQuery("#log").text("Ok");

    listar();
  }

  function updateSelect( aDatos ) {
    var op_nombres = '';
    for( var contador=0; contador < aDatos.length; contador++ )
    {

      op_nombres +='<option value="'+aDatos[contador].Nombre+'">'+aDatos[contador].Nombre+'</option>';
    }
    jQuery("#el_paciente").html(op_nombres);
    jQuery("#el_paciente").val('').trigger("liszt:updated");
  }

  function errorEnvio() {
    jQuery("#log").text("Error en envio datos");
  }

  function errorNombre() {
    jQuery("#log").text("Error: Debes ingresar Nombre");
  }

  function mostrarDatos( aDatos ) {
    for( var contador=0; contador < aDatos.length; contador++ )
    {
    	var date = new Date('now');
      var campos = "";
      campos +='<label for="Edad">Edad:</label> '+aDatos[contador].edad+'<br />';
      campos +='<label for="Rur">Rut:</label><input type="text" name="rut" id="rut" value="'+aDatos[contador].rut+'" /><br />';
      campos +='<label for="Nombre">Nombre:</label><input type="text" name="nombre" id="nombre" value="'+aDatos[contador].nombres+'" /><br />';
      campos +='<label for="Fnac">Fecha de Nac.:</label><input clasS="fecha" type="text" name="fnac" id="fnac" value="'+aDatos[contador].fnac+'" /><br />';
      campos +='<input type="button" name="enviar" id="enviar" value="Enviar Datos" onclick="update_paciente();" />';
      campos +='<input type="button" name="borrar" id="borrar" value="Borrar Paciente" onclick="borrar_paciente();" />';
      jQuery("#respuesta").html(campos);
      jQuery( ".fecha" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1920:" + date.getFullYear() + "",
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
  }

  function cleanRegistro() {
  	jQuery('#nombre_registro').val('');
    jQuery('#rut_registro').val('');
    jQuery('#fnac_registro').val('');
  }

  function showRegister() {
  	jQuery("#resultado").html("");
  	jQuery("#log").text("");
  	jQuery('#el_paciente_chzn').hide();
  	jQuery('#Consultar').hide();
  	jQuery('#Respuesta').hide();
  	jQuery('#titleElegir').hide();

  	jQuery('#registro').show();
  	jQuery('#Guardar').show();
  }

  function showPerfil() {
  	jQuery("#resultado").html("");
  	jQuery("#log").text("");
  	jQuery('#registro').hide();
  	jQuery('#Guardar').hide();

  	jQuery('#el_paciente_chzn').show();
  	jQuery('#Consultar').show();
  	jQuery('#Respuesta').show();
  	jQuery('#titleElegir').show();
  }

  function initDatePicker() {
  	var date = new Date('now');

  	jQuery( ".fecha" ).datepicker({
	    changeMonth: true,
	    changeYear: true,
	    yearRange: "1920:" + date.getFullYear() + "",
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
  };

  function init() {
  	var config = { '#el_paciente': {width:"100%"} };

	  for (var selector in config) {
	    jQuery(selector).chosen(config[selector]);
	  }

	  jQuery('input[type=radio][name=op]').change(function (e) {
	  	var value = e.currentTarget.value;

	  	if (value == 1) {
	  		showRegister();
	  	} else {
	  		showPerfil();
	  	}

      jQuery('#respuesta').empty();
	  });

	  initDatePicker();
  }

  jQuery('document').ready(function() {
	  init();

	  showPerfil();
	});
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>