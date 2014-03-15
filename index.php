<?php
/*
Plugin Name: Minecraft Server Status
Plugin URI: http://franga2000.com
Description: This is a Minecraft server status widget
Version: 1.0
Author: franga2000
Author URI: http://franga2000.com/
License: GPLv2 or later.
*/

class MCServerStatus extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = __('Minecraft Server Status'));
	}
	function form() {
		if (isset($_POST['submitted'])) {
			update_option('MCServerStatus_widget_title', $_POST['widgettitle']);
			update_option('MCServerStatus_widget_server', $_POST['server']);
			update_option('MCServerStatus_widget_pl', $_POST['pl']);
		}
		
		$widgettitle = get_option('MCServerStatus_widget_title');
		$server = get_option('MCServerStatus_widget_server');
		$pl = get_option('MCServerStatus_widget_pl');
		?>
		
		Widget Title:<br />
		<input type="text" class="widefat" name="widgettitle" value="<?php echo stripslashes($widgettitle); ?>" />
		<br /><br />
		
		Server Adress:<br />
		<input type="text" class="widefat" name="server" value="<?php echo stripslashes($server); ?>" />
		<br /><br />
		
		Player list behavior:<br />
		<select name="pl">
			<option value="pl-collapsed"<?php if($pl == "pl-collapsed"){echo 'selected';}?> >Collapsed by default</option>
  			<option value="pl-expanded"<?php if($pl == "pl-expanded"){echo 'selected';}?> >Expanded by default</option>
		</select>
		
		<input type="hidden" name="submitted" value="1" />
		<?php
	}
	function update() {
		
	}
	function widget($args, $instance) {
		$widgettitle = get_option('MCServerStatus_widget_title');
		$pl = get_option('MCServerStatus_widget_pl');
		$server = get_option('MCServerStatus_widget_server');
		$currPlayers = 0;
		$maxPlayers = 0;
		$players = Array();
    require __DIR__ . '/MinecraftQuery.class.php';

    $Query = new MinecraftQuery( );
    try{
        $Query->Connect( $server, 25565 );
        $info = $Query->GetInfo();
        $players = $Query->GetPlayers();
        $maxPlayers = $info['MaxPlayers'];
        $currPlayers = $info['Players'];
    	$online = true;
    }catch( MinecraftQueryException $e ){
        $online = false;}
	?>
		<div class="widget MCServerStatus">
			<h3 class="widget-title widget_primary_title"><?php echo $widgettitle; ?><b class="caret"></b></h3>
			<b>IP:</b> <?php echo $server; ?><br>
			<img src="wp-content/plugins/MCServerStatus/img/<?php switch($online){ case true: echo 'online'; break; case false: echo 'offline'; break; {}}?>-icon.png"> <?php switch($online){ case true: echo '<p style="color:green; display:inline;">ONLINE</p>'; break; case false:  echo '<p style="color:red; display:inline;">OFFLINE</p>'; break; {}}?><br>
			<a href="#" id="show_id" onclick="document.getElementById('spoiler_id').style.display=''; document.getElementById('show_id').style.display='none';" class="link"><b>Players: </b><?php echo $currPlayers . "/" . $maxPlayers ?></a><span id="spoiler_id" style="display: none;"><a href="#"onclick="document.getElementById('spoiler_id').style.display='none'; document.getElementById('show_id').style.display='';" class="link"><b>Players: </b><?php echo $currPlayers . "/" . $maxPlayers ?></a>
			<?
			foreach($players as $player){
				echo '<br><img src="https://minotar.net/helm/' . $player . '/30.png"> ' . $player;
			} 
			?>
			</span>
			<?php
			if($pl == "pl-expanded"){		
					echo "<script>document.getElementById('spoiler_id').style.display=''; document.getElementById('show_id').style.display='none';</script>";
			}
			?>
			<br>
			
		</div>
		<?php
	}

function display_transient_update_plugins ($transient)
{
    $obj = new stdClass();
    $obj->slug = 'access.php';
    $obj->new_version = '2.0';
    $obj->url = 'http://anyurl.com';
    $obj->package = 'http://anyurl.com';
    $transient[plugin_directory/plugin_file.php] -> $obj;
    return $transient;
}	
}

add_action( 'widgets_init', 'register_MCServerStatus');

function register_MCServerStatus()
	{
	 register_widget( 'MCServerStatus' );
	}
	add_filter ('pre_set_site_transient_update_plugins', 'display_transient_update_plugins');
?>