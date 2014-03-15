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
		//the form is submitted, save into database
		if (isset($_POST['submitted'])) {
			update_option('MCServerStatus_widget_title', $_POST['widgettitle']);
			update_option('MCServerStatus_widget_server', $_POST['server']);
			update_option('MCServerStatus_widget_pl', $_POST['pl']);
		}
		
		//load options
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
		$players = Array("franga2000", "_xXxInSaNexXx_");
	?>
		<div class="widget MCServerStatus">
			<h3 class="widget-title widget_primary_title"><?php echo $widgettitle; ?><b class="caret"></b></h3>
			<b>IP:</b> <?php echo $server; ?><br>
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
	
}

add_action( 'widgets_init', 'register_MCServerStatus');

function register_MCServerStatus()
	{
	 register_widget( 'MCServerStatus' );
	}
?>