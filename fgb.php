<?php

/*
Plugin Name: Facebook Send Button By Teknoblogo.com
Plugin URI: http://www.teknoblogo.com/facebook-gonder-butonu-eklenti
Description: Adds Facebook's Send and Like buttons to your posts ! Author : <a href="http://www.teknoblogo.com">teknoblogo.com</a><br /><strong>Don't forgot to re-configure plugin !</strong>
Version: 1.4
Author: Eray Alakese
Author URI: http://www.teknoblogo.com
License: GPL2
*/

    if(!is_admin())
    {
        wp_register_script('fgb_script', "http://connect.facebook.net/en_US/all.js#xfbml=1");
        wp_enqueue_script('fgb_script');
    }
    
function fgb_ayarlari_yap()
{
    add_option('fgb_yer', 'u');
    add_option('fgb_buton', 'snl');
    add_option('fgb_manual', 'hayir');
}
register_activation_hook( __FILE__, 'fgb_ayarlari_yap' );
function fgb_ayarlari_sil()
{
    delete_option('fgb_yer');
    delete_option('fgb_buton');
    delete_option('fgb_manual');
}
register_deactivation_hook( __FILE__, 'fgb_ayarlari_sil' );


function fgb_ekle($content)
{
    $fgb_yer = get_option('fgb_yer'); 
    $fgb_buton_opt = get_option('fgb_buton'); 
    $fgb_manual = get_option('fgb_manual');
    
	$fgb_perma	= rawurlencode(get_permalink());
    $fgb_send_button = "<fb:send href=\"$fgb_perma\" font=\"\"></fb:send>";
    $fgb_like_button = "<fb:like href=\"$fgb_perma\" send=\"false\" width=\"450\" show_faces=\"true\" font=\"\"></fb:like>";
    $fgb_snl_button = "<fb:like href=\"$fgb_perma\" send=\"true\" width=\"450\" show_faces=\"true\" font=\"\"></fb:like>";
    
    if($fgb_buton_opt == "send")
    {
        $fgb_buton = $fgb_send_button;
    }
    elseif($fgb_buton_opt == "like")
    {
        $fgb_buton = $fgb_like_button;
    }
    elseif($fgb_buton_opt == "snl")
    {
        $fgb_buton = $fgb_snl_button;
    }
    else
    {
        echo "Buton türü alınamadı!";
    }
    
    if ($fgb_manual=="hayir"){
        if ($fgb_yer == "u")
        {
            $content = $fgb_buton."<br />".$content;
        }
        elseif ($fgb_yer == "a")
        {
            $content = $content."<br />".$fgb_buton;
        }
        return $content;
    }
    elseif($fgb_manual=="evet"){
        echo $fgb_buton;
    }
}
if (get_option('fgb_manual')=="hayir"){ add_filter( "the_content", "fgb_ekle" ); }

add_action('admin_menu', 'fgb_admin_menu');
function fgb_admin_menu() {
	add_options_page('Facebook Send Button', 'Facebook Send Button', 'manage_options', 'fgb', 'fgb_admin_options');
}
function fgb_admin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
    ?>
    <h2>Facebook Send & Like Button</h2>
    <? 
    if($_POST["fgb_gonder"])
    {
      echo "<h3>saved</h3>";
      update_option('fgb_yer', $_POST["fgb_yer"]);
      update_option('fgb_buton', $_POST["fgb_buton"]);      
      update_option('fgb_manual', $_POST["fgb_manual"]);
      
        $fgb_admin_yer = get_option('fgb_yer');
        $fgb_admin_buton = get_option('fgb_buton');
        $fgb_admin_manual = get_option('fgb_manual');
    }
    ?>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
    Show Facebook buttons <select name="fgb_yer">
        <option value="u" <?php if($fgb_admin_yer == "u"){echo "SELECTED";}?>>before content</option>
        <option value="a" <?php if($fgb_admin_yer == "a"){echo "SELECTED";}?>>after content</option>
    </select> and i want <select name="fgb_buton">
        <option value="snl" <?php if($fgb_admin_buton=="snl"){echo "SELECTED";}?>>send and like buttons together</option>
        <option value="send" <?php if($fgb_admin_buton=="send"){echo "SELECTED";}?>>just send button</option>
        <option value="like" <?php if($fgb_admin_buton=="like"){echo "SELECTED";}?>>just like button</option>
    </select> . <br />
    <input type="radio" value="hayir" name="fgb_manual" <?php if($fgb_admin_manual=="hayir"){echo "CHECKED";}?> /> put buttons for me, AUTOMATICALLY <br />
    <input type="radio" value="evet" name="fgb_manual" <?php if($fgb_admin_manual=="evet"){echo "CHECKED";}?> /> i can put them, MANUALLY <br />
    
    <input type="submit" class="button-primary" name="fgb_gonder" value="<?php _e('Save Changes') ?>" />
    </form>
    <br />If you use <strong>manuel insertion</strong> , you have to add this code to your theme : 
    <strong>&lt;?php if(function_exists('fgb_ekle')) {   fgb_ekle(); }?&gt;</strong>
    
    <hr />
    <em>If you like this plugin, please <a href="http://wordpress.org/extend/plugins/facebook-send-like-button/">vote</a> .
    Author : <a href="http://www.teknoblogo.com">Eray Alakese</a>
    You can <a href="mailto:info@teknoblogo.com">mail me</a> for bugs, thanks.</em>
    
    <?php
	echo '</div>';
}
