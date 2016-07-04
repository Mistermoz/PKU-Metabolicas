<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Index Template
 *
 *
 * @file           index.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/index.php
 * @link           http://codex.wordpress.org/Theme_Development#Index_.28index.php.29
 * @since          available since Release 1.0
 */

get_header(); ?>

<div id="content" class="grid col-620">
        
	<<?php if (!(current_user_can('level_0'))){ ?>
<h2>Login</h2>
<form action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
<input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" />
<input type="password" name="pwd" id="pwd" size="20" />
<input type="submit" name="submit" value="Send" class="button" />
    <p>
       <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> Remember me</label>
       <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
    </p>
</form>
<a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword">Recover password</a>
<?php } else { ?>
<h2>Logout</h2>
<a href="<?php echo wp_logout_url(urlencode($_SERVER['REQUEST_URI'])); ?>">logout</a><br />
<a href="http://XXX/wp-admin/">admin</a>
<?php }?>
      
</div><!-- end of #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
