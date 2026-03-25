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
			          	<p>Pacientes:</p>
						<label>Todos</label><input type="radio" id="op1" name="op" value="0" checked=""/>
						<label>Por Fecha</label><input type="radio" id="op2" name="op" value="1" checked=""/>
						<label>Por Año</label><input type="radio" id="op3" name="op" value="2" checked=""/>
					</div>

					<div class="all">
		 				<?php
						  global $dbh;
						  $mi_tabla = 'paciente';
						  $query = "SELECT Nombre, Rut FROM $mi_tabla GROUP BY Nombre, Rut";
						  $content = $dbh->get_results( $query );
						  if ( count($content) > 0 ) { ?>
		        			<label for="nombre">Elegir Nombre Paciente</label>
		        			<select id="el_paciente" onChange="fechas_paciente('full');">
								  <option>Eligir Paciente</option>
								  <?php foreach ( $content as $row ) { ?>
								    <option value="<?php echo $row->Nombre; ?>" data-rut="<?php echo $row->Rut; ?>"><?php echo $row->Nombre; ?><?php if($row->Rut != '') { echo ("&nbsp; - &nbsp;$row->Rut"); } ?></option>
								  <?php } ?>
		        			</select>
						  <?php } 
		    			?>
						<label for="nombre">Elegir Fecha Lectura</label>
						<select id="fecha_lectura">
							<option value="">Elija Paciente</option>
						</select>
					</div>

					<div class="por-fecha">
						<label for="nombre">Fecha de Lectura</label><br />
						<input type="text" name="flectura" id="flectura" value="" placeholder="dd-mm-yy" onChange="buscar_pacientes();"/><br />
		    			<label for="nombre">Elegir Nombre Paciente</label>
						<select id="nombre_paciente">
							<option value="">Elija Fecha</option>
						</select>
					</div>

					<div class="por-anio">
					<?php
						global $dbh;
						$mi_tabla = 'paciente';
						$query = "SELECT Nombre, Rut FROM $mi_tabla GROUP BY Nombre, Rut";
						$content = $dbh->get_results( $query );
						if ( count($content) > 0 ) {
						?>
						  <label for="nombre">Elegir Nombre Paciente</label>
						  <select id="el_paciente_anio" onChange="fechas_paciente('anio');">
						    <option>Eligir Paciente</option>
						    <?php foreach ( $content as $row ) { ?>
						      <option value="<?php echo $row->Nombre; ?>" data-rut="<?php echo $row->Rut; ?>"><?php echo $row->Nombre; ?><?php if($row->Rut != '') { echo ("&nbsp; - &nbsp;$row->Rut"); } ?></option>
						    <?php } ?>
						  </select>
						<?php } ?>
						<label for="nombre">Elegir Fecha Lectura</label>
						<select id="anio_lectura">
						  <option value="">Elija Paciente</option>
						</select>
					</div>
					<input type="button" name="enviar" id="enviar" value="Consultar" onClick="datos_paciente();"/><br />
					<div id="respuesta"></div>
					<div id="resultado"></div>
					<div id="log"></div>
					<p class="exportar" style="display: none;">Exportar a Excel  <a href="<?php bloginfo('template_directory'); ?>/consulta.php?"><img src="<?php bloginfo('template_directory'); ?>/images/export_to_excel.gif" class="botonExcel"/></a></p>
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
<script src="<?php bloginfo('template_directory'); ?>/chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/math.min.js" type="text/javascript"></script>
<script>
	var inputs = document.getElementsByTagName('input');

	for (var i=0; i<inputs.length; i++)  {
	  if (inputs[i].type == 'radio')   {
	    inputs[i].checked = false;
	  }
	}

  jQuery('body').on('click', 'input[name="op"]', function() {
		if(jQuery(this).val() == '0') {
      jQuery('.por-anio').fadeOut(500);
			jQuery('.por-fecha').fadeOut(500);
			jQuery('.all').fadeIn(500);
			jQuery('.post-entry').attr('rel', 'todo');
			jQuery('#respuesta').html(' ');
			jQuery('.exportar').css('display', 'none');
		} else if (jQuery(this).val() == '1') {
			jQuery('.all').fadeOut(500);
      jQuery('.por-anio').fadeOut(500);
			jQuery('.por-fecha').fadeIn(500);
			jQuery('.post-entry').attr('rel', 'fecha');
			jQuery('#respuesta').html(' ');
			jQuery('.exportar').css('display', 'none');
		} else if (jQuery(this).val() == '2') {
      jQuery('.all').fadeOut(500);
      jQuery('.por-fecha').fadeOut(500);
      jQuery('.por-anio').fadeIn(500);
      jQuery('.post-entry').attr('rel', 'anio');
      jQuery('#respuesta').html(' ');
      jQuery('.exportar').css('display', 'none');
    }
	});

	/*** Datapicker ****/
	jQuery('#flectura').datepicker({
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

	function datos_paciente() {
		var type = jQuery('.post-entry').attr('rel');
		var data = {};

		jQuery('#nombre').val('');
    	jQuery('#estado').val('');
		jQuery("#log").text('');

		if ( type == 'todo') {
			var nom_paciente = jQuery('#el_paciente').val();
			var fecha_lectura = jQuery('#fecha_lectura').val();
		} else if(type == 'fecha') {
			var nom_paciente = jQuery('#nombre_paciente').val();
		  var fecha_lectura = jQuery('#flectura').val();
		} else if(type == 'anio') {
			var nom_paciente = jQuery('#el_paciente_anio').val();
		  var anio_lectura = jQuery('#anio_lectura').val();
		}
		
		if ( type == 'todo' || type == 'fecha') {
			if( fecha_lectura == '' || fecha_lectura.length < 10){
				alert('Ingrese Fecha Correcta') ; return false;
			}

			data = {'action':'pacientes','nom_paciente':nom_paciente,'fecha_lectura':fecha_lectura};
		}
		
		if (type == 'anio') {
			if( anio_lectura == '' || anio_lectura.length < 4){
				alert('Ingrese Fecha Correcta') ; return false;
			}

			data = {'action':'pacientes','nom_paciente':nom_paciente,'anio_lectura':anio_lectura};
		}

		if (nom_paciente == ''){
			alert('Ingrese Nombre') ; return false;
    	}

		jQuery.ajax({
			type: "POST",
			data: data,
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

	function fechas_paciente(op) {
    var nom_paciente = '';

		jQuery('#respuesta').html('');
		jQuery("#log").text('');

    if(op == 'anio') {
      jQuery("#anio_lectura_chzn span").html('Cargando...');
      nom_paciente = jQuery('#el_paciente_anio').val();
      jQuery.ajax({
        type: "POST",
        data: {'action':'fechas','nom_paciente':nom_paciente},
        dataType: "json",
        url: url_ajax,
        beforeSend: function () {
          jQuery("#resultado").html("Procesando, espere por favor...");
        },
        success: mostrarAnios,
        timeout: 35000,
        error: errorEnvio
      });
    } else if(op == 'full') {
		  jQuery("#fecha_lectura_chzn span").html('Cargando...');
      nom_paciente = jQuery('#el_paciente').val();

      jQuery.ajax({
        type: "POST",
        data: {'action':'fechas','nom_paciente':nom_paciente},
        dataType: "json",
        url:'/pku_movil/wp-admin/admin-ajax.php',
        beforeSend: function () {
          jQuery("#resultado").html("Procesando, espere por favor...");
        },
        success: mostrarFechas,
        timeout: 35000,
        error: errorEnvio
      });
    }
	}

	function buscar_pacientes() {
		jQuery('#respuesta').html(' ');
		jQuery("#log").text(' ');
		jQuery("#nombre_paciente").html('<option>Cargando...</option>');
		var fecha_pacientes = jQuery('#flectura').val();

		jQuery.ajax({
			type: "POST",
			data: {'action':'busca_pacientes','fecha_pacientes':fecha_pacientes},
			dataType: "json",
			url:'/pku_movil/wp-admin/admin-ajax.php',
			beforeSend: function () {
        jQuery("#resultado").html("Procesando, espere por favor...");
      },
			success: mostrarPacientes,
			timeout: 35000,
			error: errorEnvio
		});
	}

	function mostrarPacientes(aDatos) {
	  var op_nombres = '';

		for( var contador=0; contador < aDatos.length; contador++ ) {
			var rut = (aDatos[contador].rut != '') ? ' - '+aDatos[contador].rut+'' : '';

      		op_nombres +='<option value="'+aDatos[contador].nombres+'" data-rut="'+aDatos[contador].rut+'"">'+aDatos[contador].nombres+''+rut+'</option>';
		}

		if(aDatos[0].nombres == 'Sin Datos') {
			jQuery('#flectura').attr('value', '');
		} else {
			jQuery('#flectura').attr('value', ''+aDatos[0].flectura+'');
		}

		jQuery("#nombre_paciente").html(op_nombres);
		jQuery("#nombre_paciente").val('').trigger("liszt:updated");
		jQuery("#resultado").html("OK");
	}

	function errorEnvio() {
		jQuery("#log").text("Erro envio datos");
	}

	function mostrarDatos( aDatos ) {
		var campos = "";

		if(aDatos.data != 'years') {
			for( var contador=0; contador < aDatos.length; contador++ ) {
				if(aDatos[contador].edad != ""){
	        campos +='<label for="edad">Edad:</label> '+aDatos[contador].edad+' a&ntilde;os<br />';
				}
				if(aDatos[contador].fenil != ""){
	        campos +='<label for="Fenil">Fenil.:</label><input type="text" name="fenil" id="fenil" value="'+aDatos[contador].fenil+'" readonly="readonly" /><br />';
				}
				if(aDatos[contador].tir != ""){
	        campos +='<label for="tir">Tirosina:</label><input type="text" name="tir" id="tir" value="'+aDatos[contador].tir+'" readonly="readonly"><br />';
				}
				if(aDatos[contador].leu != ""){
	        campos +='<label for="leu">Leu:</label><input type="text" name="leu" id="leu" value="'+aDatos[contador].leu+'" readonly="readonly"/><br />';
				}
				if(aDatos[contador].val != ""){
	        campos +='<label for="val">Val:</label><input type="text" name="val" id="val" value="'+aDatos[contador].val+'" readonly="readonly"/><br />';
				}
				if(aDatos[contador].iso != ""){
	        campos +='<label for="iso">Iso:</label><input type="text" name="iso" id="iso" value="'+aDatos[contador].iso+'" readonly="readonly"/><br />';
				}
				if(aDatos[contador].allo != ""){
	        campos +='<label for="allo">Allo:</label><input type="text" name="allo" id="allo" value="'+aDatos[contador].allo+'" readonly="readonly"/><br />';
				}
				if(aDatos[contador].estado != ""){
	        campos +='<label for="estado">Estado:</label><input type="text" name="estado" id="estado" value="'+aDatos[contador].estado+'" readonly="readonly" /><br />';
				}
				if(aDatos[contador].plectura != ""){
	        campos +='<label for="plectura">P. Lectura:</label><input type="text" name="plectura" id="plectura" value="'+aDatos[contador].plectura+'" readonly="readonly" size="4" />';
				}
				if(aDatos[contador].fleche != ""){
	        campos +='<label for="fleche">Entrega de Leche:</label><input type="text" name="fleche" id="fleche" value="'+aDatos[contador].fleche+'" readonly="readonly" size="4" />';
				}
				if(aDatos[contador].fmuestra != ""){
	        campos +='<label for="fmuestra">Fecha Muestra:</label><input type="text" name="fmuestra" id="fmuestra" value="'+aDatos[contador].fmuestra+'" readonly="readonly" size="4" />';
				}
				if(aDatos[contador].fcontrol != ""){
	        campos +='<label for="fcontrol">Fecha Control:</label><input type="text" name="fcontrol" id="fcontrol" value="'+aDatos[contador].fcontrol+'" readonly="readonly" size="4" />';
				}
			}

			jQuery("#respuesta").html(campos);

			if(jQuery('.post-entry').attr('rel') == 'fecha') {
				var nurl = 'fecha='+jQuery('#flectura').val()+'&nom='+jQuery('#nombre_paciente').val();
			}

			if(jQuery('.post-entry').attr('rel') == 'todo') {
				var nurl = 'fecha='+jQuery('#fecha_lectura').val()+'&nom='+jQuery('#el_paciente').val();
			}

			jQuery('.exportar').find('a').attr('href', '/pku_movil/wp-content/themes/responsive/ejemplo.php?'+nurl+'');
			jQuery('.exportar').css('display', 'block');
			jQuery("#resultado").html("OK");
		} else if(aDatos.data == 'years') {
			var years_data = {};

			years_data = get_data_years(aDatos.rows);

			if(years_data['fenil-media'] != null ) {
        campos +='<label for="fenil-media">Fenil Promedio:</label> '+ years_data['fenil-media'] +'<br />';
			}
			if(years_data['fenil-std'] != null ) {
        campos +='<label for="fenil-std">Fenil Desv. Estándar:</label> '+ years_data['fenil-std'] +'<br />';
			}
			if(years_data['leu-media'] != null ) {
        campos +='<label for="leu-media">leu Promedio:</label> '+ years_data['leu-media'] +'<br />';
			}
			if(years_data['leu-std'] != null ) {
        campos +='<label for="leu-std">leu Desv. Estándar:</label> '+ years_data['leu-std'] +'<br />';
			}
			if(years_data['tir-media'] != null ) {
        campos +='<label for="tir-media">Tirosina Promedio:</label> '+ years_data['tir-media'] +'<br />';
			}
			if(years_data['leu-std'] != null ) {
        campos +='<label for="tir-std">Tirosina Desv. Estándar:</label> '+ years_data['tir-std'] +'<br />';
			}

			jQuery("#respuesta").html(campos);
			jQuery("#resultado").html("OK");
		}
	}

	function mostrarFechas( aDatos ) {
    var op_fechas = '';

		for( var contador=0; contador < aDatos.length; contador++ ) {
      op_fechas +='<option value='+aDatos[contador].fechas+'>'+aDatos[contador].fechas_dos+'</option>';
		}

		jQuery("#fecha_lectura").html(op_fechas);
		jQuery("#fecha_lectura").val('').trigger("liszt:updated");
		jQuery("#resultado").html("OK");
	}

  function mostrarAnios( aDatos ) {
    var op_fechas = '';
    var years = [];
    var tmpDate = '';

    for( var contador=0; contador < aDatos.length; contador++ ) {
      tmpDate = new Date(aDatos[contador].fechas);

      if(jQuery.inArray(tmpDate.getFullYear(), years) < 0 ) {
        years.push(tmpDate.getFullYear());
      }
    }
    
    jQuery(years).each(function (index, element) {
      op_fechas +='<option value='+element+'>'+element+'</option>';
    });

    jQuery("#anio_lectura").html(op_fechas);
    jQuery("#anio_lectura").val('').trigger("liszt:updated");
    jQuery("#resultado").html("OK");
  }

	var config = {
    '#nombre_paciente': {width:"100%"},
    '#el_paciente': {width:"100%"},
    '#el_paciente_anio': {width:"100%"},
	  '#fecha_lectura': {width:"100%"},
    '#anio_lectura': {width:"100%"}
  };

  function get_data_years(rows) {
  	var fenil = [];
  	var leu = [];
  	var tir = [];
  	var totals = [];

  	for(row in rows) {
  		var fe = replaceChars(rows[row].fenil);
  		console.log(fe);
  		var msd = replaceChars(rows[row].leu);
  		var tr = replaceChars(rows[row].tir);

  		if (fe != '') {
  			fenil.push(parseFloat(fe));
  		}

  		if (msd != '') {
  			leu.push(parseFloat(msd));
  		}

  		if (tr != '') {
  			tir.push(parseFloat(tr));
  		}
  	}

  	if (!jQuery.isEmptyObject(fenil)) {
		totals['fenil-media'] = math.mean(fenil).toFixed(4);
  		totals['fenil-std'] = math.std(fenil).toFixed(4);
  	}

  	if (!jQuery.isEmptyObject(leu)) {
		totals['leu-media'] = math.mean(leu).toFixed(4);
  		totals['leu-std'] = math.std(leu).toFixed(4);
  	}

  	if (!jQuery.isEmptyObject(tir)) {
		totals['ter-media'] = math.mean(tir).toFixed(4);
  		totals['ter-std'] = math.std(tir).toFixed(4);
  	}

  	return totals;
  }

  function replaceChars(string) {
  	return string.replace(/([\s-<->])/, '');
  }

  for (var selector in config) {
    jQuery(selector).chosen(config[selector]);
  }
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>