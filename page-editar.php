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
            <p>Editar Paciente:</p>
            <!--<label>Perfil</label><input type="radio" id="op1" name="op" value="0" checked=""/>
            <label>Registro</label><input type="radio" id="op2" name="op" value="1" checked=""/>-->
          </div>

          <?php
            global $dbh;
            $mi_tabla = 'paciente';
            $query = "SELECT * FROM $mi_tabla GROUP BY Nombre";
            $content = $dbh->get_results( $query );
            if ( count($content) > 0 ) {?>
              <label for="nombre">Elegir Nombre Paciente</label>
              <select id="el_paciente">
              <option>Eligir Paciente</option>
              <?php foreach ( $content as $row ) {
                ?>
                <option value="<?php echo $row->Nombre; ?>"><?php echo $row->Nombre; ?></option>

              <?php } ?></select>
            <?php } ?>
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

</div><!-- end of #content
function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
 -->
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
      url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
      beforeSend: function () {
                        jQuery("#resultado").html("Procesando, espere por favor...");
            },
      success: mostrarDatos,
      timeout: 35000,
      error: errorEnvio
    });
  }

  function update_paciente(){
      jQuery("#log").text(' ');
      var nombre_antiguo = String(jQuery('#el_paciente').val());
      var nombre_nuevo = jQuery('#nombre').val();
      var fnac = jQuery('#fnac').val();

      jQuery.ajax({
        type: "POST",
        data:
        {'action':'actualiza_paciente','nombre_antiguo':nombre_antiguo,'nombre_nuevo':nombre_nuevo,'fnac':fnac },
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
    function borrar_paciente(){
      jQuery("#log").text(' ');
      var nombre_antiguo = String(jQuery('#el_paciente').val());

      jQuery.ajax({
        type: "POST",
        data:
        {'action':'borrar_paciente','nombre_antiguo':nombre_antiguo},
        dataType: "json",
        url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
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
        url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
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
    jQuery("#log").text("Erro envio datos");
  }
  function mostrarDatos( aDatos )
  {
    for( var contador=0; contador < aDatos.length; contador++ )
    {
      var campos = "";
      campos +='<label for="Fenil">Edad:</label> '+aDatos[contador].edad+'<br />';
      campos +='<label for="Nombre">Nombre:</label><input type="text" name="nombre" id="nombre" value="'+aDatos[contador].nombres+'" /><br />';
      campos +='<label for="Fnac">Fecha de Nac.:</label><input clasS="fecha" type="text" name="fnac" id="fnac" value="'+aDatos[contador].fnac+'" /><br />';
      campos +='<input type="button" name="enviar" id="enviar" value="Enviar Datos" onclick="update_paciente();" />';
      campos +='<input type="button" name="borrar" id="borrar" value="Borrar Paciente" onclick="borrar_paciente();" />';
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
  var config = {
      '#el_paciente'           : {width:"100%"}
    }
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>