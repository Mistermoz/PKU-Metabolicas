<?php if ( !defined('ABSPATH')) exit;get_header(); ?>
    <div id="content" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?> ingresa-lec">
      <?php if (have_posts()) : ?><?php while (have_posts()) : the_post(); ?>
      <div id="post-&lt;?php the_ID(); ?&gt;" <?php post_class(); ?>>
        <div class="post-entry">
          <h3>Ingreso de lecturas por paciente</h3>

          <div class="link-agregar-paciente">
          	<a href="/pku_movil/editar/">Agregar nuevo paciente</a>
          </div>

		  <?php
			global $dbh;
			$query = "SELECT Nombre, Rut FROM paciente_info GROUP BY Nombre, Rut";
			$content = $dbh->get_results( $query );
			if ( count($content) > 0 ) {?>
				<div id="paciente_ejemplo_rut">
					<option>Elegir Paciente</option>
					<?php foreach ( $content as $row ) { ?>
						<?php if ($row->Rut != '') { ?>
							<option value="<?php echo $row->Rut; ?>" data-nombre="<?php echo $row->Nombre; ?>""><?php echo $row->Rut; ?></option>
						<?php } ?>
					<?php } ?>
				</div>

				<div id="paciente_ejemplo_nombre"><option>Elegir Paciente</option><?php foreach ( $content as $row ) {?><option value="<?php echo $row->Nombre; ?>" data-rut="<?php echo $row->Rut; ?>"><?php echo $row->Nombre; ?></option><?php } ?></div>
			<?php } ?>
		  <label>Fenil</label><input type="radio" id="pac_fen" checked="checked" name="enfermedad" value="0"/>
		  <label>Leu</label><input type="radio" id="pac_leu" name="enfermedad" value="1"/>
		  <div class="box_nom_campos">
		  	<label class="nom_campo nom_campo--nro" id="nro">Nro.</label>
			<label class="nom_campo nom_campo--nom" id="nom">Paciente</label>
			<label class="nom_campo nom_campo--rut_nom" id="rut_nom">Rut/Nombre</label>
			<label class="nom_campo nom_campo--flec" id="flec">F. Lectura</label>
			<label class="nom_campo nom_campo--fen PKU amin" id="fen">Fenil</label>
			<label class="nom_campo nom_campo--leu MSUD amin" id="leu">Leu</label>
			<label class="nom_campo nom_campo--tir amin" id="tir">Tir</label>
			<label class="nom_campo nom_campo--val MSUD amin" id="val">Val</label>
			<label class="nom_campo nom_campo--iso MSUD amin" id="iso">Iso</label>
			<label class="nom_campo nom_campo--allo MSUD amin" id="allo">Allo</label>
			<label class="nom_campo nom_campo--fmues" id="fmues">F. Muestra</label>
		  </div>
		  <div class="box_inputs">
			<div id="registro1" class="registro registro1" rel="1">
			  <div class="numero nom_campo--nro">
			  	<label class="nro_paciente">1.</label><input type="checkbox" class="nro" name="nro1" value=""/>
			  </div>
			  <div id="box_paciente1" class="box_paciente box_paciente1 nom_campo--nom">
				<label class="elige_paciente">Rut</label><input type="radio" id="rut" name="paciente" value="0" rel="1"/>
				<label class="elige_paciente">Nombre</label><input type="radio" id="nombre" name="paciente" value="1" rel="1"/>
			  </div>
			  <div class="rut nom_campo--rut_nom">
			  	<input type="text" class="input_rut_nom" name="rut_nom1" value="" readonly="readonly"/>
			  </div>
			  <input type="text" name="flectura1" class="flectura fecha nom_campo--flec" value="" placeholder="Ej:dd-mm-aaaa" />
			  <input type="text" name="fenil1" class="fenil nom_campo--fen PKU amin" value="" placeholder="Fenil" />
			  <input type="text" name="leu1" class="leu nom_campo--leu MSUD amin" value="" placeholder="Leu" />
			  <input type="text" name="tiro1" class="tiro nom_campo--tir amin" value="" placeholder="Tir" />
			  <input type="text" name="val1" class="val nom_campo--val MSUD amin" value="" placeholder="Val" />
			  <input type="text" name="iso1" class="iso nom_campo--iso MSUD amin" value="" placeholder="Iso" />
			  <input type="text" name="allo1" class="allo nom_campo--allo MSUD amin" value="" placeholder="Allo" />
			  <input type="text" name="fmuestra1" class="fmuestra fecha nom_campo--fmues" value="" placeholder="Ej:dd-mm-aaaa" />
			</div>
		  </div>
		  <div class="add_delete">
			  <input type="button" name="agregar" id="agregar" value="+" onclick="agregar_paciente();" />
			  <input type="button" name="eliminar" id="eliminar" value="-" onclick="eliminar_paciente();" />
	      </div>
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
		/*** Elección de Enfermedad Fenil o Leu ****/
		jQuery("input[name='enfermedad']").bind('click',function(){
			if(jQuery(this).val() == '1'){
				jQuery('.MSUD').css('display','block');
				jQuery('.PKU').css('display','none');
			} else {
				jQuery('.MSUD').css('display','none');
				jQuery('.PKU').css('display','block');
			}
		});
		/*** Elección de Paciente ****/
		jQuery("body").on('click','input[name="paciente"]', function(){
			var cont = jQuery(this).attr('rel');

			if (jQuery(this).val() == '0') {
				var paciente_ruts = jQuery('#paciente_ejemplo_rut').html();
				jQuery('#registro'+cont+'').data('type', 'rut');
				jQuery('.box_paciente'+cont+'').html('<select id="rut'+cont+'" name="rut'+cont+'" class="rut'+cont+' select-name" data-id="'+cont+'" data-type="rut">'+paciente_ruts+'</select>');
				jQuery('.rut'+cont+'').chosen({width:"99%"});
				jQuery('.nombre'+cont+'_chzn').fadeIn(500);
			} else {
				var paciente_nombres = jQuery('#paciente_ejemplo_nombre').html();
				jQuery('#registro'+cont+'').data('type', 'nombre');
				jQuery('.box_paciente'+cont+'').html('<select id="nombre'+cont+'" name="nombre'+cont+'" class="nombre'+cont+' select-name" data-id="'+cont+'" data-type="nombre">'+paciente_nombres+'</select>');
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
			jQuery('body').on('focus', '.nombre', function(){
				jQuery(this).removeClass('nombre_none');
			});
			jQuery('body').on('focus', '.flectura', function() {
				jQuery(this).removeClass('fecha_none');
			});
			jQuery('body').on('click', '.chzn-container', function() {
				jQuery('.chzn-container a').removeClass('nombre_none');
			});

			jQuery('body').on('change', '.select-name', function () {
				var nro = jQuery(this).attr('data-id');
				var rut = jQuery(this).children("option:selected").attr('data-rut');
				var nombre = jQuery(this).children("option:selected").attr('data-nombre');
				var type = jQuery(this).attr('data-type');

				if (type == 'nombre' && rut != '') {
					jQuery('input[name=rut_nom'+ nro +']').val(rut);
				} else if (type == 'rut' && nombre != '') {
					jQuery('input[name=rut_nom'+ nro +']').val(nombre);
				}
			});
		});
		/*** Funcion  Ingreso de Pacientes ****/

		function ingreso_paciente(){
			var rut_paciente = new Array();
			var nom_paciente = new Array();
			var flectura = new Array();
			var fenil = new Array();
			var tir = new Array();
			var leu = new Array();
			var val = new Array();
			var iso = new Array();
			var allo = new Array();
			var fmuestra = new Array();
			var c = jQuery(".box_inputs > .registro").length;
			for(j=1; j<=c; j++) {
				if (jQuery('#registro'+j+'').data('type') == 'nombre') {
					if (jQuery('.nombre'+j+'').val() == '' || jQuery('.nombre'+j+'').length == 0 || jQuery('.nombre'+j+'').val() == 'Elegir Paciente'){
						jQuery('.nombre'+j+'').addClass('nombre_none');
			 			jQuery('#nombre'+j+'_chzn a').addClass('nombre_none');
						return false;
		        	}
		        } else {
		        	if (jQuery('.rut'+j+'').val() == '' || jQuery('.rut'+j+'').length == 0 || jQuery('.rut'+j+'').val() == 'Elegir Paciente'){
						jQuery('.rut'+j+'').addClass('nombre_none');
			 			jQuery('#rut'+j+'_chzn a').addClass('nombre_none');
						return false;
		        	}
		        }
				if(jQuery('input[name="flectura'+j+'"]').val() == ''){
					jQuery('input[name="flectura'+j+'"]').addClass('fecha_none');
					return false;
				}

				if (jQuery('#registro'+j+'').data('type') == 'nombre') {
					nom_paciente[j-1] = jQuery('[name=nombre'+j+']').val();
					rut_paciente[j-1] = jQuery('[name=rut_nom'+j+']').val();
				} else {
					rut_paciente[j-1] = jQuery('[name=rut'+j+']').val();
		    		nom_paciente[j-1] = jQuery('[name=rut_nom'+j+']').val();
				}
				flectura[j-1] = jQuery('input[name="flectura'+j+'"]').val();
				fenil[j-1] = jQuery('input[name="fenil'+j+'"]').val();
				tir[j-1] =jQuery('input[name="tiro'+j+'"]').val();
				leu[j-1] = jQuery('input[name="leu'+j+'"]').val();
				val[j-1] = jQuery('input[name="val'+j+'"]').val();
				iso[j-1] = jQuery('input[name="iso'+j+'"]').val();
				allo[j-1] = jQuery('input[name="allo'+j+'"]').val();
				fmuestra[j-1] =jQuery('input[name="fmuestra'+j+'"]').val();
			}
			//jQuery("#log").text('leu' + leu);
			jQuery.ajax({
				type: "POST",
				cache: true,
				data:
				{'action':'ingreso_pacientes', 'rut_paciente': rut_paciente, 'nom_paciente' : nom_paciente, 'flectura' : flectura, 'fenil' : fenil, 'tir' : tir, 'leu' : leu, 'val' : val, 'iso' : iso, 'allo' : allo, 'fmuestra' : fmuestra},
				url: url_ajax,
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
			jQuery('.box_inputs').append('<div id="registro'+c+'" class="registro registro'+c+'" rel="'+c+'">'+
			'<div class="numero nom_campo--nro">'+
			'<label class="nro_paciente">'+c+'.</label>'+
			'<input type="checkbox" class="nro" name="nro'+c+'" value=""/></div>'+
			'<div id="box_paciente'+c+'" class="box_paciente box_paciente'+c+' nom_campo--nom">'+
			'<label class="elige_paciente">Rut</label>'+
			'<input type="radio" id="rut" name="paciente" value="0" rel="'+c+'"/>'+
			'<label class="elige_paciente">Nombre</label>'+
			'<input type="radio" id="nombre" name="paciente" value="1" rel="'+c+'"/></div>'+
			'<div class="rut nom_campo--rut_nom"><input type="text" class="input_rut_nom" name="rut_nom'+c+'" value=""/></div>'+
			'<input type="text" name="flectura'+c+'" class="flectura fecha nom_campo--flec" value="" placeholder="Ej:dd-mm-aaaa" />'+
			'<input type="text" name="fenil'+c+'" class="fenil PKU amin" value="" placeholder="Fenil" />'+
			'<input type="text" name="leu'+c+'" class="leu MSUD amin" value="" placeholder="Leu" />'+
			'<input type="text" name="tiro'+c+'" class="tiro amin" value="" placeholder="Tir" />'+
			'<input type="text" name="val'+c+'" class="val MSUD amin" value="" placeholder="Val" />'+
			'<input type="text" name="iso'+c+'" class="iso MSUD amin" value="" placeholder="Iso" />'+
			'<input type="text" name="allo'+c+'" class="allo MSUD amin" value="" placeholder="Allo" />'+
			'<input type="text" name="fmuestra'+c+'" class="fmuestra fecha nom_campo--fmues" value="" placeholder="Ej:dd-mm-aaaa" /></div>');
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
				} else {
					reg_g[m] = i;
					m++;
				}
			}
			if (confirm('¿Seguro que desea borrar estos registros?')) {
				for(var i = 0; i < k ; i++) {
					jQuery('.registro'+reg_e[i]+'').remove();
				}
				// Reset columnas que quedan
				for(i=1; i <= m ; i++) {
					var oldReg = reg_g[i-1];

					if (oldReg != i) {
						jQuery('#registro'+oldReg+'').removeClass('registro'+oldReg+'').addClass('registro'+i+'');
						jQuery('.registro'+i+'').attr('id', 'registro'+i+'');
						jQuery('#registro'+i+'').attr('rel', ''+i+'');
						jQuery('#registro'+i+'').find('.nro_paciente').html(''+i+'.');
						jQuery('#registro'+i+'').find('.nro').attr('name', 'nro'+i+'');
						jQuery('#registro'+i+'').find('.input_rut_nom').attr('name', 'rut_nom'+i+'');
						jQuery('#box_paciente'+oldReg+'').removeClass('box_paciente'+oldReg+'').addClass('box_paciente'+i+'');
						jQuery('.box_paciente'+i+'').attr('id', 'box_paciente'+i+'');
						jQuery('#box_paciente'+i+'').attr('data-id', ''+i+'');
						jQuery('#box_paciente'+i+'').find('#nuevo').attr('rel', ''+i+'');
						jQuery('#box_paciente'+i+'').find('#antiguo').attr('rel', ''+i+'');
						jQuery('#registro'+i+'').find('.nombre').attr('name', 'nombre'+i+'');
						jQuery('#nombre'+oldReg+'').removeClass('nombre'+oldReg+'').addClass('nombre'+i+'');
						jQuery('.nombre'+i+'').attr('id', 'nombre'+i+'');
						jQuery('#nombre'+i+'').attr('name', 'nombre'+i+'');
						jQuery('#nombre'+i+'').attr('data-id', ''+i+'');
						jQuery('#box_paciente'+i+'').find('.chzn-container').attr('id', 'nombre'+i+'_chzn');
						jQuery('#registro'+i+'').find('.flectura').attr('name', 'flectura'+i+'');
						jQuery('#registro'+i+'').find('.fenil').attr('name', 'fenil'+i+'');
						jQuery('#registro'+i+'').find('.tiro').attr('name', 'tiro'+i+'');
						jQuery('#registro'+i+'').find('.leu').attr('name', 'leu'+i+'');
						jQuery('#registro'+i+'').find('.val').attr('name', 'val'+i+'');
						jQuery('#registro'+i+'').find('.iso').attr('name', 'iso'+i+'');
						jQuery('#registro'+i+'').find('.allo').attr('name', 'allo'+i+'');
						jQuery('#registro'+i+'').find('.fmuestra').attr('name', 'fmuestra'+i+'');
						jQuery('#registro'+i+'').find('.input_rut').attr('name', 'rut'+i+'');
					}
				}
			} else {
				return false;
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
