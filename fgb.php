<?php

/*
Plugin Name: Facebook Send Button By Teknoblogo.com
Plugin URI: http://www.teknoblogo.com/facebook-gonder-butonu-eklenti
Description: Yazılarınıza otomatik olarak Facebook Beğen ve Gönder butonları ekler
Version: 1.1
Author: Eray Alakese
Author URI: http://www.teknoblogo.com
License: GPL2
*/


    wp_register_script('fgb_script', "http://connect.facebook.net/en_US/all.js#xfbml=1");
    wp_enqueue_script('fgb_script');
    
function fgb_ayarlari_yap()
{
    add_option('fgb_yer', 'u');
    add_option('fgb_buton', 'snl');
}
register_activation_hook(__FILE__,'twl_tablo_olustur');

function fgb_ekle($content)
{
    $fgb_yer = get_option('fgb_yer'); 
    $fgb_buton_opt = get_option('fgb_buton'); 
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
    
    if ($fgb_yer == "u")
    {
        $content = $fgb_buton."<br />".$fgb_buton_opt.$content;
    }
    elseif ($fgb_yer == "a")
    {
        $content = $content."<br />".$fgb_buton;
    }
    return $content;
}
add_filter( "the_content", "fgb_ekle" );

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
    }
    ?>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
    Show Facebook Buttons <select name="fgb_yer">
        <option value="u">Before Content</option>
        <option value="a">After Content</option>
    </select> and i want <select name="fgb_buton">
        <option value="snl">send and like buttons together</option>
        <option value="send">just send button</option>
        <option value="like">just like button</option>
    </select>
    <input type="submit" class="button-primary" name="fgb_gonder" value="<?php _e('Save Changes') ?>" />
    </form>
    <?php
	echo '</div>';
}