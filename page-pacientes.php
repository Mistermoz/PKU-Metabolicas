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
					<input type="text" name="flectura" id="flectura" value="" placeholder="dd-mm-yy" onChange="buscar_pacientes();"/><br />
                    <label for="nombre">Elegir Nombre Paciente</label>
					<select id="nombre_paciente">
						<option value="">Elija Fecha</option>
					</select>
					<input type="button" name="enviar" id="enviar" value="Consultar" onClick="datos_paciente();"/><br />
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

</div><!-- end of #content
function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					}
 -->
<script src="<?php bloginfo('template_directory'); ?>/chosen/chosen.jquery.js" type="text/javascript"></script>
<script>
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

	function datos_paciente(){
			var fecha =  jQuery('#flectura').val();
			if(jQuery('#flectura').val() == '' || fecha.length < 10){
				alert('Ingrese Fecha Completa') ; return false;
			}
			if(jQuery('#nombre_paciente').val() == ''){
				alert('Ingrese Nombre') ; return false;
            }
		jQuery("#log").text(' ');
		var nom_paciente = jQuery('#nombre_paciente').val();
		var flectura = jQuery('#flectura').val();
		jQuery.ajax({
			type: "POST",
			data: {'action':'edita_pacientes','nom_paciente':nom_paciente , 'flectura':flectura},
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
	function update_paciente(){
			jQuery("#log").text(' ');
			var nom_paciente = jQuery('#nombre_paciente').val();
			var flectura = jQuery('#flectura').val();
			var fenil = jQuery('#fenil').val();
			var tir = jQuery('#tir').val();
			var msud = jQuery('#msud').val();
			var estado = jQuery('#estado').val();
			var plectura = jQuery('#plectura').val();
			var fleche = jQuery('#fleche').val();
			var fmuestra = jQuery('#fmuestra').val();
			var fcontrol = jQuery('#fcontrol').val();
			var fnac = jQuery('#fnac').val();
			jQuery.ajax({
				type: "POST",
				data:
				{'action':'actualiza_pacientes','nom_paciente':nom_paciente,'flectura':flectura ,'fenil':fenil,'tir':tir,'msud':msud,'estado':estado,'plectura':plectura,'fleche':fleche,'fmuestra':fmuestra, 'fcontrol':fcontrol, 'fnac':fnac },
				dataType: "json",
				url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
				beforeSend: function () {
                   jQuery("#resultado").html("Procesando, espere por favor...");
				},
				success:
					muestraResultado,
					timeout: 35000,
					error: errorEnvio
			});
		}
	function muestraResultado( aDatos ) {
			jQuery("#resultado").html(""+aDatos[0].Paciente+"");
			//datos_paciente();
			jQuery("#log").text("Paciente Actualizado");
		}
	function errorEnvio() {
		jQuery("#log").text("Error envio datos, intente nuevamente");
	}
	function mostrarDatos( aDatos )
	{
		for( var contador=0; contador < aDatos.length; contador++ )
		{
			var campos = "";
			campos +='<label for="Fenil">Edad:</label> '+aDatos[contador].edad+' a&ntilde;os<br />';
			campos +='<label for="Fnac">Fecha de Nac.:</label><input clasS="fecha" type="text" name="fnac" id="fnac" value="'+aDatos[contador].fnac+'" /><br />';
			campos +='<label for="Fenil">Fenil.:</label><input type="text" name="fenil" id="fenil" value="'+aDatos[contador].fenil+'" /><br />';
			campos +='<label for="tir">Tirosina:</label><input type="text" name="tir" id="tir" value="'+aDatos[contador].tir+'"  /><br />';
			campos +='<label for="msud">MSUD:</label><input type="text" name="msud" id="msud" value="'+aDatos[contador].msud+'" /><br />';
			campos +='<label for="estado">Estado:</label><input type="text" name="estado" id="estado" value="'+aDatos[contador].estado+'" /><br />';
			campos +='<label for="plectura">P. Lectura:</label><input type="text" clasS="fecha" name="plectura" id="plectura" value="'+aDatos[contador].plectura+'"  />';
			campos +='<label for="Fecha_Leche">Fecha Leche:</label><br><input type="text" clasS="fecha" name="fleche" id="fleche" value="'+aDatos[contador].fleche+'" placeholder="Ej:dd-mm-aaaa"/><br>';
			campos +='<label for="fecha_muestra">Fecha muestra:</label><input type="text" clasS="fecha" name="fmuestra" id="fmuestra" value="'+aDatos[contador].fmuestra+'" readonly="readonly" size="4" />';
			campos +='<label for="fcontrol">Fecha Control:</label><input type="text" clasS="fecha" name="fcontrol" id="fcontrol" value="'+aDatos[contador].fcontrol+'" readonly="readonly" size="4" />';
			campos +='<input type="button" name="enviar" id="enviar" value="Enviar Datos" onclick="update_paciente();" />';
			jQuery("#respuesta").html(campos);
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
	}
	function mostrarPacientes( aDatos )
	{
	    var op_nombres = '';
		for( var contador=0; contador < aDatos.length; contador++ )
		{

			 op_nombres +='<option value="'+aDatos[contador].nombres+'">'+aDatos[contador].nombres+'</option>';
		}
		jQuery("#nombre_paciente").html(op_nombres);
		jQuery("#nombre_paciente").val('').trigger("liszt:updated");
		jQuery("#resultado").html("OK");
	}
	var config = {
      '#nombre_paciente'           : {width:"100%"}
    }
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>