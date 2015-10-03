<?php
/*
Plugin Name: Pocketmine Server Status
Plugin URI: http://flashacker13.com
Description: A widget to display information from your pocketmine server onto your Wordpress website.
Version: 1.0
Author: Flashacker13, franga2000
Author URI: http://flashacker13.com
License: GPLv2 or later.
*/

add_filter('plugin_action_links', 'PMSS_plugin_action_links', 10, 2);

function PMSS_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/widgets.php">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

class PMSS extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = __('Pocketmine Server Status'));
	}
	
	function form() {
		if (isset($_POST['submitted'])) {
			update_option('PMSS_widget_title', $_POST['widgettitle']);
			update_option('PMSS_widget_server', $_POST['server']);
			update_option('PMSS_widget_port', $_POST['port']);
			update_option('PMSS_widget_pmpl', $_POST['pmpl']);
		}
?>
		<label for="pmwidgettitle">Widget Title:</label><br/>
		<input type="text" class="widefat" name="pmwidgettitle" value="<?php echo stripslashes(get_option('PMSS_widget_title')); ?>" placeholder="Server status" required/>
		<br/><br/>
		
		<label for="pmserver">Server Address:</label><br/>
		<input type="text" class="widefat" name="pmserver" value="<?php echo stripslashes(get_option('PMSS_widget_server')); ?>" placeholder="mc.server.tld" required/>
		<br/><br/>
		
		<label for="pmport">Server Port:</label><br/>
		<input type="number" class="widefat" name="pmport" value="<?php echo stripslashes(get_option('PMSS_widget_port')); ?>" placeholder="19132" required/>
		<br/><br/>
		
		<label for="pmpl">Player list behavior:</label><br/>
		<select name="pmpl">
			<option value="pmpl-collapsed" <?php if (get_option('PMSS_widget_pmpl') == "pmpl-collapsed") echo 'selected'; ?>>Collapsed by default</option>
			<option value="pmpl-expanded" <?php if (get_option('PMSS_widget_pmpl') == "pmpl-expanded") echo 'selected'; ?>>Expanded by default</option>
		</select>
		<br/><br/>
		
		<input type="hidden" name="submitted" value="1" />
		<?php
	}

	function update() {
		
	}

	function widget($args, $instance) {
		$players = Array();
		require __DIR__ . '/PMQuery.class.php';
		
		$Query = new PMQuery();
		try {
			$Query->Connect(get_option('PMSS_widget_server'), get_option('PMSS_widget_port', "19132"));
			$info = $Query->GetInfo();
			$online = true;
		} catch (PMQueryException $e){
			$online = false;}
		?>
				<div class="widget PMServerStatus">
				<h3 class="pmwidget-title widget_primary_title"><?php echo get_option('PMSS_widget_title'); ?></h3>
				<b>IP: </b><?php echo get_option('PMSS_widget_server'); ?><br/>
				<b>Port: </b><?php echo get_option('PMSS_widget_port'); ?><br/>
				<?php
                if ($online){
                ?>
                <img src="<?php echo plugins_url('img/online-icon.png', __FILE__);?>"><p style="color:green; display:inline;"><?php echo 'ONLINE'; ?></p><br>
                <?php
                }
                else{
				?>
                <img src="<?php echo plugins_url('img/offline-icon.png', __FILE__);?>"><p style="color:red; display:inline;"><?php echo 'OFFLINE'; ?></p><br>
                <?php
				}
                ?>
				<span id="pmplayers-toggle" title="Click to toggle">Players:</span>
				<ul id="pmplayers" <?php if(get_option('PMSS_widget_pl') == "pmpl-collapsed") echo 'style="display:none;"'; ?>>
					<?php
					if( ( $Players = $Query->GetPlayers( ) ) == false ){
					echo "No Players Online!";
					}
					else {
					foreach($Query->GetPlayers() as $key => $player) {
						echo '<li class="player">'.$player.'</li>';
					}
					}
					?>
				</ul>
			</div>
			<script>
				document.getElementById("pmplayers-toggle").onclick = function() {
					var element = document.getElementById("pmplayers");
					if (element.style.display == "none") {
						element.style.display = "";
					} else {
						element.style.display = "none";
					}
				};
			</script>
			<style>
				#pmplayers li {
					list-style-type: none !important; /* I used !important because some free themes hard-code styles at the end and this doesn't work. I know it's bad practice but I had no choice */
				}
				
				#pmplayers-toggle {
					cursor: pointer;
					text-decoration: underline;
				}
			</style>
		<?php
	}
}

add_action('widgets_init', 'register_PMSS');

function register_PMSS() {
	register_widget('PMSS');
}

//add_filter ('pre_set_site_transient_update_plugins', 'display_transient_update_plugins');
?>
