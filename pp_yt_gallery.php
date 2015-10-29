<?php
/*
Plugin Name: PP YouTube Gallery
Plugin URI: 
Description: YouTube Gallery - Put shortcode [pp_yt_gallery] in you content.
Version: 1.0
Author: Gorazd krumpak
Author URI: 
*/

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

//tell wordpress to register the demolistposts shortcode
add_shortcode("pp_yt_gallery", "pp_yt_gallery_handler");

function pp_yt_gallery_styles()
{
    // Register the style like this for a plugin:
    wp_register_style( 'pp-yt-gallery-style', plugins_url( '/css/pp-yt-gallery-style.css', __FILE__ ), array(), '', 'all' );
    wp_enqueue_style( 'pp-yt-gallery-style' );
}
add_action( 'init', 'pp_yt_gallery_styles' );

function pp_yt_gallery_handler() {
  //run function that actually does the work of the plugin
  $output = pp_yt_gallery_function();
  //send back text to replace shortcode in post
  return $output;
}

function pp_yt_gallery_function() {
  //process plugin
  $cont = json_decode(file_get_contents('http://gdata.youtube.com/feeds/api/playlists/PLPtQJM5hrDpHFpvdZcbeUe0sQyKAHq9ww/?v=2&alt=json&feature=plcp&max-results=50'));
$feed = $cont->feed->entry;
$videoID_array = array();

if(count($feed))
{
    foreach($feed as $item)
        array_push($videoID_array, array( 'id' => $item->{'media$group'}->{'yt$videoid'}->{'$t'}, 'title' => $item->{'title'}->{'$t'} ));
}
  $cont_ = json_decode(file_get_contents('http://gdata.youtube.com/feeds/api/playlists/PLPtQJM5hrDpHFpvdZcbeUe0sQyKAHq9ww/?v=2&alt=json&feature=plcp&start-index=51&max-results=50'));
$feed_ = $cont_->feed->entry;

if(count($feed_))
{
    foreach($feed_ as $item)
        array_push($videoID_array, array( 'id' => $item->{'media$group'}->{'yt$videoid'}->{'$t'}, 'title' => $item->{'title'}->{'$t'} ));
}

$izpis.='<script>
function changeVideo(id,title) {
	document.getElementById(\'vid_frame\').src=\'http://youtube.com/embed/\'+id+\'?autoplay=1&rel=0&showinfo=0&autohide=1\';
	document.getElementById(\'vid_title\').innerHTML =title;
}
</script>
<div class="container">
  		<!-- THE YOUTUBE PLAYER --><h2 id="vid_title">'.$videoID_array[0]['title'].'</h2>
  		<div class="vid-container">
		    <iframe id="vid_frame" src="http://www.youtube.com/embed/'.$videoID_array[0]['id'].'?rel=0&showinfo=0&autohide=1" frameborder="0"></iframe>
		</div>

		<!-- THE PLAYLIST -->
		<div id="ex3" class="vid-list-container">';
	         	foreach($videoID_array as $items){
				$title=$items['title'];
				
if(strlen($title)>30){$title=substr($title, 0, strrpos(substr($title, 0, 30), ' ', -1)).'&nbsp;...';}
 	            $izpis.='<div class="vid-list" onClick="changeVideo(\''.$items['id'].'\', \''.$items['title'].'\')" title="'.$items['title'].'"><div class="vid-item">
 	              <div class="thumb"><img src="http://img.youtube.com/vi/'. $items['id']. '/0.jpg" alt="'.$items['title'].'"></div>
 	              <div class="desc">'. $title. '</div></div></div>';
				}
$izpis.='</div></div>';
  $output = $izpis;
  //send back text to calling function
  return $output;
}
?>