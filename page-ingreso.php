<?php if ( !defined('ABSPATH')) exit;get_header(); ?>
    <div id="content" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">
      <?php if (have_posts()) : ?><?php while (have_posts()) : the_post(); ?>
      <div id="post-&lt;?php the_ID(); ?&gt;" <?php post_class(); ?>>
        <div class="post-entry">
          <h3>Ingreso de pacientes</h3>
		  <?php
						global $dbh;
						$query = "SELECT * FROM paciente GROUP BY Nombre";
						$content = $dbh->get_results( $query );
						if ( count($content) > 0 ) {?>
							<div id="paciente_ejemplo"><option>Elegir Paciente</option><?php foreach ( $content as $row ) {?><option value="<?php echo $row->Nombre; ?>"><?php echo $row->Nombre; ?></option><?php } ?></div>
						<?php } ?>
		  <label>Fenil</label><input type="radio" id="pac_fen" checked="checked" name="enfermedad" value="0"/>
		  <label>MSUD</label><input type="radio" id="pac_msud" name="enfermedad" value="1"/>
		  <div class="box_nom_campos">
		  	<label class="nom_campo" id="nro">Nro.</label>
			<label class="nom_campo" id="nom">Nombre</label>
			<label class="nom_campo" id="flec">F. Lectura</label>
			<label class="nom_campo" id="fen">Fenil</label>
			<label class="nom_campo" id="msud">MSUD</label>
			<label class="nom_campo" id="tir">Tir</label>
			<label class="nom_campo" id="fmues">F. Muestra</label>
		  </div>
		  <div class="box_inputs">
			<div id="registro1" class="registro registro1" rel="1">
			  <div class="numero">
			  	<label class="nro_paciente">1.</label><input type="checkbox" class="nro" name="nro1" value=""/>
			  </div>
			  <div id="box_paciente1" class="box_paciente box_paciente1">
				<label class="elige_paciente">Nuevo</label><input type="radio" id="nuevo" name="paciente" value="0" rel="1"/>
				<label class="elige_paciente">Antiguo</label><input type="radio" id="antiguo" name="paciente" value="1" rel="1"/>
			  </div>
			  <input type="text" name="flectura1" class="flectura fecha" value="" placeholder="Ej:dd-mm-aaaa" />
			  <input type="text" name="fenil1" class="fenil" value="" placeholder="Fenil" />
			  <input type="text" name="msud1" class="msud" value="" placeholder="MSUD" />
			  <input type="text" name="tiro1" class="tiro" value="" placeholder="Tir" />
			  <input type="text" name="fmuestra1" class="fmuestra fecha" value="" placeholder="Ej:dd-mm-aaaa" />
			</div>
		  </div>
		  <input type="button" name="agregar" id="agregar" value="+" onclick="agregar_paciente();" />
		  <input type="button" name="eliminar" id="eliminar" value="-" onclick="eliminar_paciente();" />
          <input type="button" name="enviar" id="enviar" value="Enviar Datos" onclick="ingreso_paciente();" />
          <br />
          <div id="respuesta"></div>
          <div id="resultado"></div>
          <div id="log"></div>
        </div>
        <!-- end of .post-entry --><?php get_template_part( 'post-data' ); ?>
      </div><?php                 endwhile;       endif;  ?>
    </div>
    <!-- end of #contentfunction (xhr, ajaxOptions, thrownError) {alert(xhr.status); alert(thrownError);} -->
    <script>
    /*** Elección de Enfermedad Fenil o MSUD ****/
		jQuery("input[name='enfermedad']").bind('click',function(){
			if(jQuery(this).val() == '1'){
				jQuery('.msud').css('display','block');
				jQuery('#msud').css('display','block');
				jQuery('#fen').css('display','none');
				jQuery('.fenil').css('display','none');
			}else{
				jQuery('.msud').css('display','none');
				jQuery('#msud').css('display','none');
				jQuery('#fen').css('display','block');
				jQuery('.fenil').css('display','block');
			}
		});
		/*** Elección de Paciente ****/
		jQuery("input[name='paciente']").live('click',function(){
			var cont = jQuery(this).attr('rel');
			if(jQuery(this).val() == '0'){
				jQuery('.box_paciente'+cont+'').html('<input type="text" name="nombre'+cont+'" class="nombre nombre'+cont+'" value="" placeholder="Nombre" />');
				jQuery('.nombre'+cont+'').fadeIn(500);
			}else{
				var paciente = jQuery('#paciente_ejemplo').html();
				jQuery('.box_paciente'+cont+'').html('<select id="nombre'+cont+'" name="nombre'+cont+'" class="nombre'+cont+'">'+paciente+'</select>');
				jQuery('.nombre'+cont+'').chosen({width:"99%"});
				jQuery('.nombre'+cont+'_chzn').fadeIn(500);
			}
		});

		/*** Datapicker ****/
		jQuery(document).ready(function() {
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
			jQuery('.flectura').val(fecha_hoy());
			jQuery('.nombre').live('focus', function(){
				jQuery(this).removeClass('nombre_none');
			});
			jQuery('.flectura').live('focus', function(){
				jQuery(this).removeClass('fecha_none');
			});
      jQuery('.chzn-container').live('click', function(){
        jQuery('.chzn-container a').removeClass('nombre_none');
      });
		});
/*** Funcion  Ingreso de Pacientes ****/

function ingreso_paciente(){
	var nom_paciente = new Array();
	var flectura = new Array();
	var fenil = new Array();
	var tir = new Array();
	var msud = new Array();
	var fmuestra = new Array();
	var c = jQuery(".box_inputs > .registro").length;
	for(j=1; j<=c; j++) {
		if(jQuery('.nombre'+j+'').val() == '' || jQuery('.nombre'+j+'').length == 0 || jQuery('.nombre'+j+'').val() == 'Elegir Paciente'){
			jQuery('.nombre'+j+'').addClass('nombre_none');
 			jQuery('#nombre'+j+'_chzn a').addClass('nombre_none');
			return false;
        }
		if(jQuery('input[name="flectura'+j+'"]').val() == ''){
			jQuery('input[name="flectura'+j+'"]').addClass('fecha_none');
			return false;
		}
    	nom_paciente[j-1] = jQuery('[name=nombre'+j+']').val();
		flectura[j-1] = jQuery('input[name="flectura'+j+'"]').val();
		fenil[j-1] = jQuery('input[name="fenil'+j+'"]').val();
		tir[j-1] =jQuery('input[name="tiro'+j+'"]').val();
		msud[j-1] = jQuery('input[name="msud'+j+'"]').val();
		fmuestra[j-1] =jQuery('input[name="fmuestra'+j+'"]').val();
	}
	jQuery("#log").text(' ');
	jQuery.ajax({
		type: "POST",
		cache: true,
		data:
		{'action':'ingreso_pacientes', 'nom_paciente' : nom_paciente, 'flectura' : flectura, 'fenil' : fenil, 'tir' : tir, 'msud' :msud, 'fmuestra' : fmuestra},
		url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
		dataType: "json",
		beforeSend: function () {
           jQuery("#resultado").html("Procesando, espere por favor...");
		},
		success:
			mostrarDatos,
			timeout: 35000,
			error: errorEnvio
	});
}

/*** Funcion Agrega Pacientes ****/
function agregar_paciente(){
	var c = jQuery(".box_inputs > .registro").length;
	c = c+1;
	jQuery('.box_inputs').append('<div id="registro'+c+'" class="registro registro'+c+'" rel="'+c+'"><div class="numero"><label class="nro_paciente">'+c+'.</label><input type="checkbox" class="nro" name="nro'+c+'" value=""/></div><div id="box_paciente'+c+'" class="box_paciente box_paciente'+c+'"><label class="elige_paciente">Nuevo</label><input type="radio" id="nuevo" name="paciente" value="0" rel="'+c+'"/><label class="elige_paciente">Antiguo</label><input type="radio" id="antiguo" name="paciente" value="1" rel="'+c+'"/></div><input type="text" name="flectura'+c+'" class="flectura fecha" value="" placeholder="Ej:dd-mm-aaaa" /><input type="text" name="fenil'+c+'" class="fenil" value="" placeholder="Fenil" /><input type="text" name="msud'+c+'" class="msud" value="" placeholder="MSUD" /><input type="text" name="tiro'+c+'" class="tiro" value="" placeholder="Tir" /><input type="text" name="fmuestra'+c+'" class="fmuestra fecha" value="" placeholder="Ej:dd-mm-aaaa" /></div>');
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
	jQuery('.flectura').val(fecha_hoy());
}

/*** Funcion Elimina Pacientes ****/
function eliminar_paciente(){
	var reg_e = new Array();
	var reg_g = new Array();
	var c = jQuery(".box_inputs > .registro").length;
	var k = 0, m = 0;
	for(var i = 1; i <= c ; i++) {
		if(jQuery('input[name="nro'+i+'"]').prop('checked')){
			reg_e[k] = i;
			k++;
		}else{
			reg_g[m] = i;
			m++;
		}
	}
	if (confirm('¿Seguro que desea borrar estos registros?')) {
		for(var i = 0; i < k ; i++) {
			jQuery('.registro'+reg_e[i]+'').remove();
		}
		// Reset columnas que quedan
		for(i=1; i <= m ; i++){
			if(i != reg_g[i-1]){
				jQuery('#registro'+reg_g[i-1]+'').addClass('registro'+i+'');
				jQuery('.registro'+i+'').attr('id', 'registro'+i+'');
				jQuery('#registro'+i+'').attr('rel', ''+i+'');
				jQuery('#registro'+i+'').removeClass('registro'+reg_g[i-1]+'');
				jQuery('#registro'+i+'').find('.nro_paciente').html(''+i+'.');
	            jQuery('#box_paciente'+reg_g[i-1]+'').addClass('box_paciente'+i+'');
	            jQuery('.box_paciente'+i+'').attr('id', 'box_paciente'+i+'');
	            jQuery('#box_paciente'+i+'').removeClass('box_paciente'+reg_g[i-1]+'');
	            jQuery('#box_paciente'+i+'').find('#nuevo').attr('rel', ''+i+'');
	            jQuery('#box_paciente'+i+'').find('#antiguo').attr('rel', ''+i+'');
	            jQuery('#registro'+i+'').find('.nombre').attr('name', 'nombre'+i+'');
	            jQuery('#nombre'+reg_g[i-1]+'').addClass('nombre'+i+'');
	            jQuery('.nombre'+i+'').attr('id', 'nombre'+i+'');
	            jQuery('#box_paciente'+i+'').find('.chzn-container').attr('id', 'nombre'+i+'_chzn');
	            jQuery('#nombre'+i+'').removeClass('nombre'+reg_g[i-1]+'');
	            jQuery('#registro'+i+'').find('.flectura').attr('name', 'flectura'+i+'');
	            jQuery('#registro'+i+'').find('.fenil').attr('name', 'fenil'+i+'');
	            jQuery('#registro'+i+'').find('.tiro').attr('name', 'tiro'+i+'');
	            jQuery('#registro'+i+'').find('.msud').attr('name', 'msud'+i+'');
	            jQuery('#registro'+i+'').find('.fmuestra').attr('name', 'fmuestra'+i+'');
			}
		}
	} else {

	}
}

/*** Funcion Error Envio ****/
function errorEnvio(error) {
	jQuery("#log").text(error);
     console.log(error);
}

/*** Funcion mostrar Pacientes ****/
function mostrarDatos( aDatos ) {
	var i = aDatos.length;
	var resultado = '';
	for(j = 0; j < i ; j++){
		resultado += '* '+aDatos[j]+'<br>';
	}
	jQuery("#resultado").html(resultado);
	jQuery('#nombre').val('');
}

/*** Funcion mostrar fecha hoy ****/
function fecha_hoy() {
	var f = new Date();
	var mes = f.getMonth() +1;
	if(mes<=9)mes ='0'+mes+'';
	return ('0' + f.getDate()).slice(-2) + "-" + (mes) + "-" + f.getFullYear();
}
</script>
<?php get_sidebar(); ?><?php get_footer(); ?>
