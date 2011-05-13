<?php

/*
Plugin Name: Facebook Send Button By Teknoblogo.com
Plugin URI: http://www.teknoblogo.com/facebook-gonder-butonu-eklenti
Description: Adds Facebook's Send and/or Like buttons to your posts ! Author : <a href="http://www.teknoblogo.com">teknoblogo.com</a><br /> <strong>Don't forgot to re-configure plugin !</strong>
Version: 1.5.1
Author: Eray Alakese
Author URI: http://www.teknoblogo.com
License: GPL2
*/

    if(get_option('fgb_mode') == 'xfbml' && get_option('fgb_trouble') != 'yes')
    {
        wp_register_script('fgb_script', "http://connect.facebook.net/en_US/all.js#appId=215477511814689&amp;xfbml=1");
        wp_enqueue_script('fgb_script');
    }

function fgb_ayarlari_yap()
{
    add_option('fgb_yer', 'u');
    add_option('fgb_button', 'snl');
    add_option('fgb_manual', 'no');
    add_option('fgb_mode', 'xfbml');
    add_option('fgb_trouble', 'no');
}
register_activation_hook( __FILE__, 'fgb_ayarlari_yap' );

function fgb_ayarlari_sil()
{
    delete_option('fgb_yer');
    delete_option('fgb_button');
    delete_option('fgb_manual');
    delete_option('fgb_mode');
}
register_deactivation_hook( __FILE__, 'fgb_ayarlari_sil' );

function fgb_ekle($content)
{
    $fgb_yer = get_option('fgb_yer');
    $fgb_button_opt = get_option('fgb_button');
    $fgb_manual = get_option('fgb_manual');
    $fgb_mode = get_option('fgb_mode');
    $fgb_perma	= rawurlencode(get_permalink());
    $fgb_trouble = get_option('fgb_trouble');

    if($fgb_mode == 'iframe')
    {
        if($fgb_button_opt == 'like')
        {
            $fgb_button = "<iframe src=\"http://www.facebook.com/plugins/like.php?app_id=215477511814689&amp;href=$fgb_perma&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:450px; height:21px;\" allowTransparency=\"true\"></iframe>";
        }
        elseif($fgb_button_opt == 'send')
        {
            echo "<strong>send buttons isn't available in iframe mode !</strong>";
        }
        elseif($fgb_button_opt == 'snl')
        {
            echo "<strong>send buttons isn't available in iframe mode !</strong>";
        }
        else
        {
            echo "<strong>fgb_button_opt is invalid. Please re-activate and re-configure plugin. </strong>";
        }
    }
    elseif($fgb_mode == 'xfbml')
    {
        if($fgb_button_opt == 'like')
        {
            $fgb_button = "<div id=\"fb-root\"></div><fb:like href=\"$fgb_perma\" send=\"false\" layout=\"button_count\" width=\"450\" show_faces=\"false\" font=\"\"></fb:like>";
        }
        elseif($fgb_button_opt == 'send')
        {
            $fgb_button = "<div id=\"fb-root\"></div><fb:send href=\"$fgb_perma\" font=\"\"></fb:send>";
        }
        elseif($fgb_button_opt == 'snl')
        {
            if($fgb_trouble == 'yes')
            {
                $fgb_button = "<div id=\"fb-root\" style=\"float:left;\"></div><script src=\"http://connect.facebook.net/en_US/all.js#xfbml=1&amp;layout=button_count\"></script><fb:send href=\"$fgb_perma\" font=\"\"></fb:send><div id=\"fb-root\" style=\"float:left;\"></div><script src=\"http://connect.facebook.net/en_US/all.js#appId=124435277635306&amp;xfbml=1\"></script><fb:like href=\"$fgb_perma\" send=\"false\" layout=\"button_count\" show_faces=\"false\" font=\"\"></fb:like>";
            }
            else {
                $fgb_button = "<div id=\"fb-root\"></div><fb:like href=\"$fgb_perma\" send=\"true\" layout=\"button_count\" width=\"450\" show_faces=\"false\" font=\"\"></fb:like>";
            }
        }
        else
        {
            echo "<strong>fgb_button_opt is invalid. Please re-activate and re-configure plugin. </strong>";
        }
    }
    else
    {
        echo "<strong> fgb_mode is invalid. Please reactivate and reconfigure plugin.</strong>";
    }

    if($fgb_manual == 'no')
    {
        if ($fgb_yer == "u")
        {
            $content = $fgb_button."<br />".$content;
        }
        elseif ($fgb_yer == "a")
        {
            $content = $content."<br />".$fgb_button;
        }
        return $content;
    }
    elseif($fgb_manual == 'yes')
    {
        echo $fgb_button;
    }
}
if (get_option('fgb_manual')=="no"){ add_filter( "the_content", "fgb_ekle" ); }

/* ADMIN PANEL */
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
      update_option('fgb_button', $_POST["fgb_button"]);
      update_option('fgb_manual', $_POST["fgb_manual"]);
      update_option('fgb_mode', $_POST["fgb_mode"]);
      update_option('fgb_trouble', $_POST["fgb_trouble"]);
    }
    ?>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">

        I want to use <input type="radio" value="iframe" name="fgb_mode" <?php if(get_option('fgb_mode') == 'iframe'){ echo "CHECKED"; }?>>iframe <input type="radio" value="xfbml" name="fgb_mode" <?php if(get_option('fgb_mode') == 'xfbml'){ echo "CHECKED"; }?>>xfbml(default) mode.
        Show Facebook buttons <select name="fgb_yer">
        <option value="u" <?php if(get_option('fgb_yer') == 'u'){ echo "SELECTED"; }?>>before content</option>
        <option value="a" <?php if(get_option('fgb_yer') == 'a'){ echo "SELECTED"; }?>>after content</option>
    </select> and i want <select name="fgb_button">
        <option value="snl" <?php if(get_option('fgb_button') == 'snl'){ echo "SELECTED"; }?>>send and like buttons together</option>
        <option value="send" <?php if(get_option('fgb_button') == 'send'){ echo "SELECTED"; }?>>just send button</option>
        <option value="like" <?php if(get_option('fgb_button') == 'like'){ echo "SELECTED"; }?>>just like button</option>
    </select> .
    <input type="checkbox" value="yes" name="fgb_trouble"<?php if(get_option('fgb_trouble') == 'yes'){echo "CHECKED";} ?> />TROUBLE MODE
    <br />
    <input type="radio" value="no" name="fgb_manual" <?php if(get_option('fgb_manual') == 'no'){ echo "CHECKED"; }?> /> put buttons for me, AUTOMATICALLY <br />
    <input type="radio" value="yes" name="fgb_manual" <?php if(get_option('fgb_manual') == 'yes'){ echo "CHECKED"; }?> /> i can put them, MANUALLY <br />

    <input type="submit" class="button-primary" name="fgb_gonder" value="<?php _e('Save Changes') ?>" />
    </form>
    <br />
    <strong>WARNING ! </strong>  Send buttons is <strong>NOT</strong> available on <strong>iframe</strong> mode.
    <br />If you use <strong>manuel insertion</strong> , you have to add this code to your theme :
    <strong>&lt;?php if(function_exists('fgb_ekle')) {   fgb_ekle(); }?&gt;</strong>

    <hr />
    <em>If you <iframe src="http://www.facebook.com/plugins/like.php?app_id=165465603514883&amp;href=http%3A%2F%2Fwordpress.org%2Fextend%2Fplugins%2Ffacebook-send-like-button%2F&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe> this plugin, please <a href="http://wordpress.org/extend/plugins/facebook-send-like-button/">vote</a> .
    Author : <a href="http://www.teknoblogo.com">Eray Alakese</a>
    You can <a href="mailto:info@teknoblogo.com">mail me</a> for bugs, <a href="http://www.twitter.com/teknoblogo">follow me</a> for upgrades, thanks.</em>

    <?php
	echo '</div>';
}
?>
