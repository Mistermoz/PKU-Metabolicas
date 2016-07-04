<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Blog Template
 *
 * @file           home.php
 * @package        Responsive
 * @author         Emil Uzelac
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.1.0
 * @filesource     wp-content/themes/responsive/home.php
 * @link           http://codex.wordpress.org/Templates
 * @since          available since Release 1.0
 */
get_header();
global $more; $more = 0;
?>

<div id="content-blog" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">
<?php if (!(current_user_can('level_0'))){ ?>
	<h2>Acceso Usuarios</h2>
		<form action="<?php echo get_option('home'); ?>/wp-login.php" method="post" id="acceso_usuario">
			<input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" placeholder="Usuario"/>
			<input type="password" name="pwd" id="pwd" size="20" placeholder="Contrase&ntilde;a"/>
			<input type="submit" name="submit" value="Enviar" class="button" />
    <p>
       <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" />Recordarme</label>
       <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
    </p>
</form>
<a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword">Recuperar Contrase&ntilde;a</a>
<?php } else {
	  global $current_user;
      get_currentuserinfo();
?>
<h2></h2>
	<h1> Bienvenido <?php echo $current_user->user_login;?></h1>
	<div class="sesion"><a href="<?php echo wp_logout_url(urlencode($_SERVER['REQUEST_URI'])); ?>">Cerrar Sesi&oacute;n</a></div>
<?php }?>

</div><!-- end of #content-blog -->

<?php// get_sidebar(); ?>
<?php get_footer(); ?>