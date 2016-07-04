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
			  					</div>
			  					<div class="all">
	               		<?php
										global $dbh;
										$mi_tabla = 'paciente';
										$query = "SELECT * FROM $mi_tabla GROUP BY Nombre";
										$content = $dbh->get_results( $query );
										if ( count($content) > 0 ) {
										?>
											<label for="nombre">Elegir Nombre Paciente</label>
											<select id="el_paciente" onChange="fechas_paciente();">
												<option>Eligir Paciente</option>
												<?php foreach ( $content as $row ) {
													?>
												<option value="<?php echo $row->Nombre; ?>"><?php echo $row->Nombre; ?></option>
												<?php } ?>
											</select>
										<?php } ?>
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
									<input type="button" name="enviar" id="enviar" value="Consultar" onClick="datos_paciente();"/><br />
									<div id="respuesta">
									</div>
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
<script>
	var inputs = document.getElementsByTagName('input');

	for (var i=0; i<inputs.length; i++)  {
	  if (inputs[i].type == 'radio')   {
	    inputs[i].checked = false;
	  }
	}

  jQuery("input[name='op']").live('click',function(){
		if(jQuery(this).val() == '0'){
			jQuery('.por-fecha').fadeOut(500);
			jQuery('.all').fadeIn(500);
			jQuery('.post-entry').attr('rel', 'todo');
			jQuery('#respuesta').html(' ');
			jQuery('.exportar').css('display', 'none');
		}else{
			jQuery('.all').fadeOut(500);
			jQuery('.por-fecha').fadeIn(500);
			jQuery('.post-entry').attr('rel', 'fecha');
			jQuery('#respuesta').html(' ');
			jQuery('.exportar').css('display', 'none');
		}
	});

	/*** Datapicker ****/
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
	var config = {
      '#nombre_paciente'           : {width:"100%"}
    }
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }

	function datos_paciente(){
		jQuery('#nombre').val(' ');
	    jQuery('#estado').val(' ');
		jQuery("#log").text(' ');
		if(jQuery('.post-entry').attr('rel') == 'todo'){
			var nom_paciente = jQuery('#el_paciente').val();
			var fecha_lectura = jQuery('#fecha_lectura').val();
		}else {
			var nom_paciente = jQuery('#nombre_paciente').val();
		  var fecha_lectura = jQuery('#flectura').val();
		}
		if( fecha_lectura == '' || fecha_lectura.length < 10){
				alert('Ingrese Fecha Correcta') ; return false;
		}
		if(nom_paciente == ''){
				alert('Ingrese Nombre') ; return false;
    }
		jQuery.ajax({
			type: "POST",
			data: {'action':'pacientes','nom_paciente':nom_paciente,'fecha_lectura':fecha_lectura},
			dataType: "json",
			url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
			beforeSend: function () {
                        jQuery("#resultado").html("Procesando, espere por favor...");
            },
			success: mostrarDatos,
			timeout: 35000,
			error: errorEnvio
		});
	}
	function fechas_paciente(){
		jQuery('#respuesta').html(' ');
		jQuery("#log").text(' ');
		jQuery("#fecha_lectura_chzn span").html('Cargando...');
		var nom_paciente = jQuery('#el_paciente').val();
		jQuery.ajax({
			type: "POST",
			data: {'action':'fechas','nom_paciente':nom_paciente},
			dataType: "json",
			url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
			beforeSend: function () {
                        jQuery("#resultado").html("Procesando, espere por favor...");
            },
			success: mostrarFechas,
			timeout: 35000,
			error: errorEnvio
		});
	}
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
	function mostrarPacientes( aDatos )
	{

	  var op_nombres = '';

		for( var contador=0; contador < aDatos.length; contador++ )
		{

			 op_nombres +='<option value="'+aDatos[contador].nombres+'">'+aDatos[contador].nombres+'</option>';
		}
		if(aDatos[0].nombres == 'Sin Datos') {
			jQuery('#flectura').attr('value', '');
		}
		else{
			jQuery('#flectura').attr('value', ''+aDatos[0].flectura+'');
		}
		jQuery("#nombre_paciente").html(op_nombres);
		jQuery("#nombre_paciente").val('').trigger("liszt:updated");
		jQuery("#resultado").html("OK");
	}
	function errorEnvio() {
		jQuery("#log").text("Erro envio datos");
	}
	function mostrarDatos( aDatos )
	{
		for( var contador=0; contador < aDatos.length; contador++ )
		{
			var campos = "";
			if(aDatos[contador].edad != ""){campos +='<label for="edad">Edad:</label> '+aDatos[contador].edad+' a&ntilde;os<br />';
			}
			if(aDatos[contador].fenil != ""){campos +='<label for="Fenil">Fenil.:</label><input type="text" name="fenil" id="fenil" value="'+aDatos[contador].fenil+'" readonly="readonly" /><br />';
			}
			if(aDatos[contador].tir != ""){campos +='<label for="tir">Tirosina:</label><input type="text" name="tir" id="tir" value="'+aDatos[contador].tir+'" readonly="readonly"><br />';
			}
			if(aDatos[contador].msud != ""){campos +='<label for="msud">MSUD:</label><input type="text" name="msud" id="msud" value="'+aDatos[contador].msud+'" readonly="readonly"/><br />';
			}
			if(aDatos[contador].estado != ""){campos +='<label for="estado">Estado:</label><input type="text" name="estado" id="estado" value="'+aDatos[contador].estado+'" readonly="readonly" /><br />';
			}
			if(aDatos[contador].plectura != ""){campos +='<label for="plectura">P. Lectura:</label><input type="text" name="plectura" id="plectura" value="'+aDatos[contador].plectura+'" readonly="readonly" size="4" />';
			}
			if(aDatos[contador].fleche != ""){campos +='<label for="fleche">Entrega de Leche:</label><input type="text" name="fleche" id="fleche" value="'+aDatos[contador].fleche+'" readonly="readonly" size="4" />';
			}
			if(aDatos[contador].fmuestra != ""){campos +='<label for="fmuestra">Fecha Muestra:</label><input type="text" name="fmuestra" id="fmuestra" value="'+aDatos[contador].fmuestra+'" readonly="readonly" size="4" />';
			}
			if(aDatos[contador].fcontrol != ""){campos +='<label for="fcontrol">Fecha Control:</label><input type="text" name="fcontrol" id="fcontrol" value="'+aDatos[contador].fcontrol+'" readonly="readonly" size="4" />';
			}
			jQuery("#respuesta").html(campos);
			if(jQuery('.post-entry').attr('rel') == 'fecha')
			{
				var nurl = 'fecha='+jQuery('#flectura').val()+'&nom='+jQuery('#nombre_paciente').val();
			}
			if(jQuery('.post-entry').attr('rel') == 'todo')
			{
				var nurl = 'fecha='+jQuery('#fecha_lectura').val()+'&nom='+jQuery('#el_paciente').val();
			}

			jQuery('.exportar').find('a').attr('href', 'http://www.metabolicaschile.cl/pku_movil/wp-content/themes/responsive/consulta.php?'+nurl+'');
			jQuery('.exportar').css('display', 'block');
			jQuery("#resultado").html("OK");
		}
	}
	function mostrarFechas( aDatos )
	{
	    var op_fechas = '';
		for( var contador=0; contador < aDatos.length; contador++ )
		{

			 op_fechas +='<option value='+aDatos[contador].fechas+'>'+aDatos[contador].fechas_dos+'</option>';
		}
		jQuery("#fecha_lectura").html(op_fechas);
		jQuery("#fecha_lectura").val('').trigger("liszt:updated");
		jQuery("#resultado").html("OK");
	}
	var config = {
      '#el_paciente'           : {width:"100%"},
	  '#fecha_lectura'           : {width:"100%"}
    }
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }

</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>