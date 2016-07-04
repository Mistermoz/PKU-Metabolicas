<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 *
 * @file           page.php
 * @package        Responsive
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/page.php
 * @since          available since Release 1.0
 */

get_header();
global $current_user;
get_currentuserinfo();?>

<div id="content" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">

  <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

      <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

          <div class="post-entry">
          <div id="respuesta">
          </div>
          <div id="resultado"></div>
          <div id="log"></div>
          <p class="exportar" style="display: none;">Exportar a Excel  <a href="<?php bloginfo('template_directory'); ?>/ejemplo.php?"><img src="<?php bloginfo('template_directory'); ?>/images/export_to_excel.gif" class="botonExcel"/></a></p>
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
  jQuery(document).ready(function() {
    jQuery(".botonExcel").click(function(event) {
      jQuery("#FormularioExportacion").submit();
    });

    historial_paciente('nombre');
  });
  function historial_paciente(tipo){
    jQuery('#FormularioExportacion').css('display', 'none');
    jQuery('#respuesta').html(' ');
    jQuery("#log").text(' ');
    jQuery("#respuesta").html('Cargando...');
    var nom_paciente = '<?php echo $current_user->user_login; ?>';
    var fecha_paciente = jQuery('#la_fecha').val();
    var type = tipo;
    jQuery.ajax({
      type: "POST",
      data: {'action':'historial','nom_paciente':nom_paciente, 'fecha_paciente':fecha_paciente, 'tipo':type},
      dataType: "json",
      url:'http://www.metabolicaschile.cl/pku_movil/wp-admin/admin-ajax.php',
      beforeSend: function () {
                        jQuery("#resultado").html("");
            },
      success: mostrarHistorial,
      timeout: 35000,
      error: errorEnvio
    });
  }
  function errorEnvio() {
    jQuery("#log").text("Erro envio datos");
  }

  function mostrarHistorial( aDatos ) {
    var tipo = 1;
    if (aDatos[0].tipo == 'nombre'){
      tipo = 0;
      var nurl = 'nom=<?php echo $current_user->user_login; ?>';
      jQuery('.exportar').find('a').attr('href', 'http://www.metabolicaschile.cl/pku_movil/wp-content/themes/responsive/ejemplo.php?'+nurl+'');

      if(jQuery(window).width() <= 550) {
          var op_historial = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_historial" width="100%"><thead><tr><th>F</th><th>F.Lec</th><th>FA</th><th>TIR</th><th>LEU</th><th>E</th><th>P.Lec</th></tr></thead><tbody>';
          for( var contador=0; contador < aDatos.length; contador++ )
          {
             op_historial +='<tr class="odd gradeX"><td class="center">'+aDatos[contador].fsort+'</td><td class="center">'+aDatos[contador].flectura+'</td><td class="center">'+aDatos[contador].fenil+'</td><td class="center">'+aDatos[contador].tir+'</td><td class="center">'+aDatos[contador].msud+'</td><td class="center">'+aDatos[contador].estado+'</td><td class="center">'+aDatos[contador].plectura+'</td></tr>';
          }
          op_historial +='</tbody><tfoot><tr><th>F</th><th>F.Lec</th><th>FA</th><th>TIR</th><th>LEU</th><th>E</th><th>P.Lec</th></tr></tfoot></table>';
      }else {
        var op_historial = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_historial" width="100%"><thead><tr><th>F</th><th>F. Lectura</th><th>Fenil</th><th>Tir</th><th>MSUD</th><th>Estado</th><th>P.Lectura</th><th>F.Leche</th><th>F.Muestra</th><th>F.Control</th></tr></thead><tbody>';
        for( var contador=0; contador < aDatos.length; contador++ )
        {
           op_historial +='<tr class="odd gradeX"><td class="center">'+aDatos[contador].fsort+'</td><td class="center">'+aDatos[contador].flectura+'</td><td class="center">'+aDatos[contador].fenil+'</td><td class="center">'+aDatos[contador].tir+'</td><td class="center">'+aDatos[contador].msud+'</td><td class="center">'+aDatos[contador].estado+'</td><td class="center">'+aDatos[contador].plectura+'</td><td class="center">'+aDatos[contador].fleche+'</td><td class="center">'+aDatos[contador].fmuestra+'</td><td class="center">'+aDatos[contador].fcontrol+'</td></tr>';
        }
        op_historial +='</tbody><tfoot><tr><th>F</th><th>F. Lectura</th><th>Fenil</th><th>Tir</th><th>MSUD</th><th>Estado</th><th>P.Lectura</th><th>F.Leche</th><th>F.Muestra</th><th>F.Control</th></tr></tfoot></table>';
      }

    }else {
      var nurl = 'fecha='+jQuery('#la_fecha').val()+'';
      jQuery('.exportar').find('a').attr('href', 'http://www.metabolicaschile.cl/pku_movil/wp-content/themes/responsive/ejemplo.php?'+nurl+'');

      if(jQuery(window).width() <= 550) {
        var op_historial = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_historial" width="100%"><thead><tr><th>Nombre</th><th>Fa</th><th>Tir</th><th>Leu</th><th>Est</th><th>P.Lec</th></tr></thead><tbody>';
        for( var contador=0; contador < aDatos.length; contador++ )
        {
           op_historial +='<tr class="odd gradeX"><td class="center">'+aDatos[contador].nombre+'</td><td class="center">'+aDatos[contador].fenil+'</td><td class="center">'+aDatos[contador].tir+'</td><td class="center">'+aDatos[contador].msud+'</td><td class="center">'+aDatos[contador].estado+'</td><td class="center">'+aDatos[contador].plectura+'</td></tr>';
        }
        op_historial +='</tbody><tfoot><tr><th>Nombre</th><th>Fa</th><th>Tir</th><th>Leu</th><th>Est</th><th>P.Lec</th></tr></tfoot></table>';
      }else {
        var op_historial = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_historial" width="100%"><thead><tr><th>Nombre</th><th>Fenil</th><th>Tir</th><th>MSUD</th><th>Estado</th><th>P.Lectura</th><th>F.Leche</th><th>F.Muestra</th><th>F.Control</th></tr></thead><tbody>';
        for( var contador=0; contador < aDatos.length; contador++ )
        {
           op_historial +='<tr class="odd gradeX"><td class="center">'+aDatos[contador].nombre+'</td><td class="center">'+aDatos[contador].fenil+'</td><td class="center">'+aDatos[contador].tir+'</td><td class="center">'+aDatos[contador].msud+'</td><td class="center">'+aDatos[contador].estado+'</td><td class="center">'+aDatos[contador].plectura+'</td><td class="center">'+aDatos[contador].fleche+'</td><td class="center">'+aDatos[contador].fmuestra+'</td><td class="center">'+aDatos[contador].fcontrol+'</td></tr>';
        }
        op_historial +='</tbody><tfoot><tr><th>Nombre</th><th>Fenil</th><th>Tir</th><th>MSUD</th><th>Estado</th><th>P.Lectura</th><th>F.Leche</th><th>F.Muestra</th><th>F.Control</th></tr></tfoot></table>';
      }
    }

    jQuery("#respuesta").html(op_historial);
    jQuery('.exportar').css('display', 'block');
    if(tipo == 1) {
      jQuery('#tabla_historial').dataTable({
        "oLanguage": {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior",
          },
          "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
        },
        "aaSorting": [[ 0, "asc" ]],
      });
    }else {
      jQuery('#tabla_historial').dataTable({
        "oLanguage": {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior",
          },
          "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
        },
        "aaSorting": [[ 0, "desc" ]],
        "aoColumnDefs": [
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] },
                        {"aTargets": [ 1 ], "sType": "date-euro"}
                    ]
      });
    }

    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
      "date-euro-pre": function ( a ) {
      if (jQuery.trim(a) != '') {
        var frDatea = jQuery.trim(a).split('-');
        var x = (frDatea[2] + frDatea[1] + frDatea[0]) * 1;
      } else {
        var x = 10000000000000; // = l'an 1000 ...
      }
      return x;
      },
      "date-euro-asc": function ( a, b ) {
        return a - b;
      },

      "date-euro-desc": function ( a, b ) {
        return b - a;
      }
    });
    jQuery("#resultado").html("OK");
  }

</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>