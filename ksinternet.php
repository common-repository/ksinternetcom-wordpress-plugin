<?php
/*
Plugin Name: ksinternet.com WordPress Plugin
Plugin URI: http://www.ksinternet.com/wordpress/ksinternet
Description: Monetize your site with <a href="http://www.ksinternet.com">ksinternet.com</a>, this plugin able to convert your outgoing links to www.ksinternet.com, no need to do it manually.
Version: 1.2.0
Author: ksinternet.com
Author URI: http://www.ksinternet.com
License: GPL2
*/
/*
ksinternet.com WordPress Plugin


Options:

- Enable www.ksinternet.com
- Convert outgoing links only/all links to www.ksinternet.com
- Ad type: Intestitial or banner
- www.ksinternet.com id (http://www.ksinternet.com/tools.php?easylink)
*/

add_action('wp_footer', 'np_ksinternet_get_script');

//get options
function np_ksinternet_get_options(){
    $explode = explode('/',get_option('home'));
    $options = array(
        'np_ksinternet_id' => get_option('np_ksinternet_id'),
        'np_ksinternet_type' => get_option('np_ksinternet_type'),
        'np_ksinternet_convert' => (get_option('np_ksinternet_convert') == 'outgoing') ? $explode[2]:''
    );
    return $options;
}

function np_ksinternet_get_script(){
    if(!get_option('np_ksinternet_enable')){
        return false;
    }
    //get plugin options
    $options = np_ksinternet_get_options();

    //populate script;
	$script .= "<script src=\"http://www.ksinternet.com/js/fp.js.php\"></script>\n";
    $script .=  "<script>\n";
	$script .= "var _x3 = _x3 || new _nsurls();\n";
	$script .= "_x3.push(['accountID', '".$options['np_ksinternet_id']."']);\n";
    $script .= "_x3.push(['adType', '".$options['np_ksinternet_type']."']);\n";
    $script .= "_x3.push(['disallowDomains', ['".$options['np_ksinternet_convert'] = str_replace('www.', '', $options['np_ksinternet_convert'])."']]);\n";
	$script .= "_x3.run();\n";
    $script .= "</script>\n";
    
    echo $script;
}




//Let's create the options menu
// create custom plugin settings menu
add_action('admin_menu', 'np_ksinternet_create_menu');

function np_ksinternet_create_menu() {

	//create new top-level menu
	add_options_page('ksinternet.com Plugin Settings', 'ksinternet.com Settings', 'administrator', __FILE__, 'np_ksinternet_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'np_ksinternet_register_mysettings' );
}


function np_ksinternet_register_mysettings() {
	//register our settings
	register_setting( 'np-ksinternet-settings-group', 'np_ksinternet_enable' );
	register_setting( 'np-ksinternet-settings-group', 'np_ksinternet_id' );
	register_setting( 'np-ksinternet-settings-group', 'np_ksinternet_convert' );
	register_setting( 'np-ksinternet-settings-group', 'np_ksinternet_type' );
}

function np_ksinternet_settings_page() {
?>
<div class="wrap">
<h2>ksinternet.com WordPress Plugin</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'np-ksinternet-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable Plugin</th>
        <td><input type="checkbox" <?php if( get_option('np_ksinternet_enable' ) == 1){ echo 'checked'; }; ?> value="1" name="np_ksinternet_enable"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">KSinternet Publisher-ID
        <td><input type="text" name="np_ksinternet_id" value="<?php echo get_option('np_ksinternet_id'); ?>" /> <br/>To get it, login to ksinternet, then go to <a href="http://www.ksinternet.com/withdraw.php" target="_blank">http://www.ksinternet.com/withdraw.php</a> find your KSinternet Publisher-ID. <a href="http://www.ksinternet.com/withdraw.php" target="_blank">Click here</a></th>
        </td>
        </tr>

            <select name="np_ksinternet_convert" style="visibility:hidden;">
                <option value="outgoing" <?php if(get_option('np_ksinternet_convert') == 'outgoing') { echo 'selected="selected"';}?>>Outgoing Links Only</options>
                <option value="all" <?php if(get_option('np_ksinternet_convert') == 'all') { echo 'selected="selected"';}?>>All Links</options>
            </select>
       
        <tr valign="top">
        <th scope="row">Ad Type</th>
        <td>
            <select name="np_ksinternet_type">
                <option value="banner" <?php if(get_option('np_ksinternet_type') == 'banner') { echo 'selected="selected"';}?>>Banner Ad</options>
                <option value="int" <?php if(get_option('np_ksinternet_type') == 'int') { echo 'selected="selected"';}?>>Interstitial Ad</options>
            </select>
        </td>
        </tr>

    </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

    <p>
        Feedback, bug report, and suggestions are greatly appreciated. Please submit any question to <a href="http://www.ksinternet.com">KSinternet.com - Plugins</a>.
    </p>

</form>
</div>
<?php } ?>
