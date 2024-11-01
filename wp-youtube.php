<?php
/*
Plugin Name: WP YouTube
Plugin URI: http://www.jenst.se/2007/11/01/wp-youtube
Description: WP YouTube makes it easier for you to post embed YouTube videos in Wordpress. It also features YouTube options , for you to control all the YouTube videos at the same time.
Author: Jens T&ouml;rnell
Version: 0.8
Author URI: http://www.jenst.se
*/


// Add stylesheet
add_action('admin_head', 'wpyt_header_admin');
function wpyt_header_admin() {
	echo "\n".'<link rel="stylesheet" href="'.get_option('siteurl').'/wp-content/plugins/wp-youtube/wp-youtube.css" type="text/css" media="screen" />'."\n";
}

//This function will be used in our templates to show the intro.
function youtube_width(){
    $youtube_width = get_option("option_youtube_width"); //Get option title
    echo $youtube_width;
}
function youtube_height(){
	$youtube_height = get_option("option_youtube_height"); //Get option text
    echo $youtube_height;
}
function youtube_output() {
	youtube_width();
	youtube_height();
}
function wpyt_tag(){
	$youtube_tag_name = get_option("option_youtube_tag_name"); //Get option text
	echo $youtube_tag_name;
}
function wpyt_tag_text(){
	$youtube_tag_name = get_option("option_youtube_tag_name"); //Get option text
	if ($youtube_tag_name == ""){
		echo "wp_youtube";
	}
	else {
		echo $youtube_tag_name;
	}
}

function youtube_tag_replace($content){
	$output = $content;
	$youtube_width = get_option("option_youtube_width");
	$youtube_height = get_option("option_youtube_height");
	$youtube_tag_name = get_option("option_youtube_tag_name");
	$youtube_color = get_option("option_youtube_color");
	$youtube_border = get_option("option_youtube_border");
	$youtube_related = get_option("option_youtube_related");
	$youtube_valid = get_option("option_youtube_valid");
	$youtube_autoplay = get_option("option_youtube_autoplay");
	
	if ($youtube_tag_name == "") {
		$youtube_tag_name = "wp_youtube";
	}
	if ($youtube_border == "on") {
		$youtube_border = "1";
	}
	else {
		$youtube_border = "0";
	}
	if ($youtube_related == "on") {
		$youtube_related = "1";
	}
	else {
		$youtube_related = "0";
	}
	if ($youtube_autoplay == "on") {
		$youtube_autoplay = "1";
	}
	else {
		$youtube_autoplay = "0";
	}
	if ($youtube_width == ""){
		$youtube_width = "425";
	}
	if ($youtube_height == ""){
		$youtube_height = "355";
	}	
	if ($youtube_color == "wpyt_color1") {
		$youtube_color1 = "0xd6d6d6";
		$youtube_color2 = "0xf0f0f0";
	}
	else if ($youtube_color == "wpyt_color2") {
		$youtube_color1 = "0x3a3a3a";
		$youtube_color2 = "0x999999";
	}
	else if ($youtube_color == "wpyt_color3") {
		$youtube_color1 = "0x2b405b";
		$youtube_color2 = "0x6b8ab6";
	}
	else if ($youtube_color == "wpyt_color4") {
		$youtube_color1 = "0x006699";
		$youtube_color2 = "0x54abd6";
	}
	else if ($youtube_color == "wpyt_color5") {
		$youtube_color1 = "0x234900";
		$youtube_color2 = "0x4e9e00";
	}
	else if ($youtube_color == "wpyt_color6") {
		$youtube_color1 = "0xe1600f";
		$youtube_color2 = "0xfebd01";
	}
	else if ($youtube_color == "wpyt_color7") {
		$youtube_color1 = "0xcc2550";
		$youtube_color2 = "0xe87a9f";
	}
	else if ($youtube_color == "wpyt_color8") {
		$youtube_color1 = "0x402061";
		$youtube_color2 = "0x9461ca";
	}
	else if ($youtube_color == "wpyt_color9") {
		$youtube_color1 = "0x5d1719";
		$youtube_color2 = "0xcd311b";
	}
	else {
		$youtube_color1 = "0xd6d6d6";
		$youtube_color2 = "0xf0f0f0";
	}
	
	$youtube_open_tag = "[" . $youtube_tag_name . "]";
	$youtube_close_tag = "[/" . $youtube_tag_name . "]";
	$wpyt_content = explode( $youtube_open_tag, $output );
	$output = $wpyt_content[0];
	for( $i=1; $i<count($wpyt_content); $i++ ){
		$array2 = explode( $youtube_close_tag, $wpyt_content[$i] );
		$wp_youtube_uid = $array2[0];
		
		if ($youtube_valid == "on" ) {
			$output .= '<object type="application/x-shockwave-flash" style="width:'.$youtube_width.'px; height:'.$youtube_height.'px;" data="http://www.youtube.com/v/'.$wp_youtube_uid.'&amp;rel='.$youtube_related.'&amp;color1='.$youtube_color1.'&amp;color2='.$youtube_color2.'&amp;border='.$youtube_border.'&amp;autoplay='.$youtube_autoplay.'">';
			$output .= '<param name="movie" value="http://www.youtube.com/v/'.$wp_youtube_uid.'&amp;rel='.$youtube_related.'&amp;color1='.$youtube_color1.'&amp;color2='.$youtube_color2.'&amp;border='.$youtube_border.'&amp;autoplay=' . $youtube_autoplay . '" /></object>';
		}
		else {
			$output .= '<object width="' . $youtube_width . '" height="' . $youtube_height . '">';
			$output .= '<param name="movie" value="http://www.youtube.com/v/'.$wp_youtube_uid . '&rel=' . $youtube_related . '&color1='.$youtube_color1.'&color2='.$youtube_color2.'&border='.$youtube_border.'"></param>';
			$output .= '<param name="wmode" value="transparent"></param>';
			$output .= '<embed src="http://www.youtube.com/v/'.$wp_youtube_uid . '&rel=' . $youtube_related . '&color1='.$youtube_color1.'&color2='.$youtube_color2.'&border='.$youtube_border.'" type="application/x-shockwave-flash" wmode="transparent" width="'.$youtube_width.'" height="'.$youtube_height.'"></object>';
		}
		
		
		$output .= $array2[1];
	}
	return $output;
}

add_filter('the_content','youtube_tag_replace');

//Make our admin page function
function wpyt_admin(){
    if(isset($_POST['submitted'])){
        //Get youtube width and height
        $youtube_width = $_POST['input_youtube_width'];
        $youtube_height = $_POST['input_youtube_height'];
		$youtube_tag_name = $_POST['input_wpyt_tag'];
		$youtube_tag_color = $_POST['input_wpyt_color'];
		$youtube_tag_border = $_POST['input_wpyt_border'];
		$youtube_tag_related = $_POST['input_wpyt_related'];
		$youtube_tag_valid = $_POST['input_wpyt_valid'];
		$youtube_tag_autoplay = $_POST['input_wpyt_autoplay'];
		
        update_option("option_youtube_width", $youtube_width); //Puts value into database
        update_option("option_youtube_height", $youtube_height);
		update_option("option_youtube_tag_name", $youtube_tag_name);
		update_option("option_youtube_color", $youtube_tag_color);
		update_option("option_youtube_border", $youtube_tag_border);
		update_option("option_youtube_related", $youtube_tag_related);
		update_option("option_youtube_valid", $youtube_tag_valid);
		update_option("option_youtube_autoplay", $youtube_tag_autoplay);
		
        //Options updated message
        echo "<div id=\"message\" class=\"updated fade\"><p><strong>WP YouTube options updated.</strong></p></div>";
    }
	?>
    <div class="wrap">
    <h2>Options</h2>
	These settings will affect every post or page using [<?php wpyt_tag_text(); ?>].<br /><br />
    <form method="post" name="options" target="_self">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><strong>Width</strong> <em>(set to 425 if blank)</em></td>
    <td><input name="input_youtube_width" type="text" style="width:100%;" value="<?php youtube_width(); ?>" /></td>
  </tr>
  <tr>
    <td><strong>Height</strong> <em>(set to 355 if blank):</em></td>
    <td><input name="input_youtube_height" type="text" style="width:100%;" value="<?php youtube_height(); ?>" /></td>
  </tr>
  <tr>
    <td><strong>Tag name</strong> <em>(set to wp_youtube if blank):</em></td>
    <td><input name="input_wpyt_tag" type="text" style="width:100%;" value="<?php wpyt_tag(); ?>" /></td>
  </tr>
  <tr>
    <td><strong>Player color:</strong></td>
	<td>
		<?php
			$color_wpyt = get_option("option_youtube_color");
			$border_wpyt = get_option("option_youtube_border");
			$related_wpyt = get_option("option_youtube_related");
			$valid_wpyt = get_option("option_youtube_valid");
			$autoplay_wpyt = get_option("option_youtube_autoplay");
		?>
		<table style="width: 100%;">
		<tr>
			<td style="height: 20px; background: #ababab;"></td>
			<td style="height: 20px; background: #6a6a6a;"></td>
			<td style="height: 20px; background: #4b6589;"></td>
			<td style="height: 20px; background: #2a89b8;"></td>
			<td style="height: 20px; background: #397400;"></td>
			<td style="height: 20px; background: #f08f08;"></td>
			<td style="height: 20px; background: #da5078;"></td>
			<td style="height: 20px; background: #6a4196;"></td>
			<td style="height: 20px; background: #95241a;"></td>
		</tr>
		</table>
		<table style="width: 100%;">
			<tr>
				<td><input type="radio" name="input_wpyt_color" value="wpyt_color1" <?php if ($color_wpyt == "wpyt_color1" || $color_wpyt == ""){ echo 'checked="checked"'; } ?> /></td>
				<?php
				for ($i=2; $i<10; $i++) {
					echo '<td><input type="radio" name="input_wpyt_color" value="wpyt_color' . $i . '"';
					if ($color_wpyt == "wpyt_color" . $i){
						echo 'checked="checked"';
					}
					echo "></td>\n";
				}
				?>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td><strong>Show Border:</strong></td>
		<td><input type="checkbox" name="input_wpyt_border" <?php if($border_wpyt == "on"){ echo 'checked="checked"'; } ?>/></td>
	</tr>
	<tr>
		<td><strong>Autoplay:</strong></td>
		<td><input type="checkbox" name="input_wpyt_autoplay" <?php if($autoplay_wpyt == "on"){ echo 'checked="checked"'; } ?>/></td>
	</tr>
	<tr>
		<td><strong>Include related videos:</strong></td>
		<td><input type="checkbox" name="input_wpyt_related" <?php if($related_wpyt == "on"){ echo 'checked="checked"'; } ?>/></td>
	</tr>
	<tr>
		<td><strong>Valid XHTML code:</strong></td>
		<td><input type="checkbox" name="input_wpyt_valid" <?php if($valid_wpyt == "on"){ echo 'checked="checked"'; } ?>/></td>
	</tr>
</table>
<p class="submit">
<input name="submitted" type="hidden" value="yes" />
<input type="submit" name="Submit" value="Update Options &raquo;" />
</p>
</form>

<h2>Instructions</h2>
<strong>Get YouTube ID from URL...</strong><br />
<span style="font-family: Courier">http://www.youtube.com/watch?v=</span><span style="font-family: Courier; color: red;">YjYT5OLoR8U</span><br /><br />
<strong>...or from the embed code:</strong><br />
<span style="font-family: Courier">&lt;object width="425" height="366";&gt;&lt;param name="movie" value="http://www.youtube.com/v/</span><span style="font-family: Courier; color: red;">YjYT5OLoR8U</span><span style="font-family: Courier">&amp;rel=1&amp;border=1"&gt;&lt;/param&gt;&lt;param name="wmode" value="transparent"&gt;&lt;/param&gt;&lt;embed src="http://www.youtube.com/v/<span style="color: red;">YjYT5OLoR8U</span>&amp;rel=1&amp;border=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="366"&gt;&lt;/embed&gt;&lt;/object&gt;<br /><br />
<strong>Just put the YouTube ID between the tags in a post or a page:</strong><br />
<span style="font-family: Courier">[<?php wpyt_tag_text(); ?>]<span style="color: red;">your_youtube_id</span>[/<?php wpyt_tag_text(); ?>]</span><br /><br />
<strong>Example:</strong><br />
<span style="font-family: Courier">[<?php wpyt_tag_text(); ?>]<span style="color: red;">YjYT5OLoR8U</span>[/<?php wpyt_tag_text(); ?>]</span><br /><br />
For support visit <a href="http://www.jenst.se/2007/11/01/wp-youtube">Jenst - WP YouTube</a><br /><br />

</div>

<?php 
}

//Add the options page in the admin panel
function wpyt_addpage() {
    add_submenu_page('options-general.php', 'WP YouTube', 'WP YouTube', 10, __FILE__, 'wpyt_admin');
}
add_action('admin_menu', 'wpyt_addpage');
?>
