<?php
/**
 * @package Hacklog Remote Image Autosave Plus
 * @encoding UTF-8
 * @author Sửa bởi TrustWeb.vn
 * @link http://trustweb.vn
 * @copyright Copyright (C) 2016 TrustWeb.vn
 * @license http://www.gnu.org/licenses/
 */

defined('ABSPATH') || die('No direct access!');

class hacklog_remote_image_autosave 
{
	const VERSION = 'Hacklog Remote Image Autosave Plus 2.0.9';
	const textdomain = 'hacklog_remote_image_autosave';
	const opt = 'hacklog_ria_auto_down';
	private static $plugin_name = 'Hacklog Remote Image Autosave Plus';
	private static $src_size =  array('thumbnail', 'medium', 'large','full');
	private static $opts = array(
	'thumbnail_size'=>'medium',
	'min_width'=>'100',
	//'use_lightbox' => "1",
	'link_image' => "1",
    'css_class_link' =>'photolightbox',
	'css_class_img' => ''
	,'valid_tags' => 'div,p,span,b,strong,a,img,h3,h4,h5,h6,ul,li,ol'
    ,'invalid_attr' => 'id,style,class,width,height,data-pwidth,data-width,alt,title,data-pwidth,data-natural-width,align');
	/**
	 * do the stuff
	 */
	public static function init() 
	{
		self::$opts = get_option(self::opt, self::$opts);
		add_action( 'admin_menu', array (__CLASS__, 'add_setting_menu' ) );
		// add editor button
		add_action('media_buttons', array(__CLASS__, 'add_media_button'), 20);
		register_activation_hook(HACKLOG_RIA_LOADER, array(__CLASS__, 'my_activation'));
		register_deactivation_hook(HACKLOG_RIA_LOADER, array(__CLASS__, 'my_deactivation'));
		add_filter('image_send_to_editor', array(__CLASS__, 'give_linked_images_class'),10,8);
		add_filter('tiny_mce_before_init', array(__CLASS__, 'configure_tinymce'));
		
		
		
	}
    
    public static function configure_tinymce($in) {
	   $valid_tags = self::get_conf('valid_tags');
	   $invalid_attr = self::get_conf('invalid_attr');
       $in['paste_preprocess'] = "function(plugin, args){
    // Strip all HTML tags except those we have whitelisted
    var whitelist = '". $valid_tags ."';
    var stripped = jQuery('<div>' + args.content + '</div>');
    var els = stripped.find('*').not(whitelist);
    for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
	  
    }
    // Strip all class and id attributes
    //stripped.find('*').removeAttr('id').removeAttr('class').removeAttr('style').removeAttr('width').removeAttr('height');
	var invalidAttrsStr = '".$invalid_attr."';
	var invalidAttrs = invalidAttrsStr.replace(/\s+/g, '').split(',');
	if(invalidAttrs.length)
	{
		for(var i = 0; i < invalidAttrs.length; i++)
        {
			stripped.find('*').removeAttr(invalidAttrs[i]);
	    }
		
	}
    args.content = stripped.html();
    }";
  return $in;
    }
/**
 * Attach a class to linked images' parent anchors
 * e.g. a img => a.img img
 */
  public static function  give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
  $post_id = get_post_field( 'post_parent', $id );
  $post_title = htmlentities(get_the_title($post_id));
  $classes = self::get_conf('css_class_link'); // separated by spaces, e.g. 'img image-link'
  $full_url = wp_get_attachment_image_src( $id, 'full'); 
  $url = wp_get_attachment_image_src( $id, 'large'); 
  // check if there are already classes assigned to the anchor
  
    $html = '<a class="'. $classes .'" href="'. $full_url[0] .'" title="'. $post_title .'"><img alt="'. $post_title .'" src="'. $url[0] .'" /><a/>';
  return $html;
     }

	/**
	 * do the stuff once the plugin is installed
	 * @static
	 * @return void
	 */
	public static function my_activation()
	{
		add_option(self::opt, self::$opts);
	}
	
	/**
	 * do cleaning stuff when the plugin is deactivated.
	 * @static
	 * @return void
	 */
	public static function my_deactivation()
	{
		delete_option(self::opt);
	}

	public static function get_conf($key,$default='')
	{
		return array_key_exists($key, self::$opts) ? self::$opts[$key] : $default;
	}

	public static function set_conf($key,$value='')
	{
		if( in_array( $key, array('thumbnail_size','min_width','link_image','css_class_link','css_class_img','valid_tags','invalid_attr') ))
			{
				self::$opts[$key] = $value;
			}
	}

	public static function update_config()
	{
		update_option(self::opt, self::$opts);
	}

	public static function add_media_button($editor_id = 'content')
	{
		global $post_ID;
		$url = WP_PLUGIN_URL . "/hacklog-remote-image-autosave/handle.php?post_id={$post_ID}&tab=download&TB_iframe=true&width=740&height=500";
		$admin_icon = WP_PLUGIN_URL . '/hacklog-remote-image-autosave/images/admin_icon.png';
		if (is_ssl())
		{
			$url = str_replace('http://', 'https://', $url);
		}
		$alt = __('Download remote images to local server', self::textdomain);
		$img = '<img src="' . esc_url($admin_icon) . '" width="15" height="15" alt="' . esc_attr($alt) . '" />';

		echo '<a href="' . esc_url($url) . '" class="thickbox hacklog-ria-button" id="' . esc_attr($editor_id) . '-hacklog_ria" title="' .
			esc_attr__('Hacklog Remote Image Autosave', self::textdomain) . '" onclick="return false;">' . $img . '</a>';
	}

	
	//add option menu to Settings menu
	public static function add_setting_menu()
	{
		add_options_page( self::$plugin_name. ' Options', 'Hacklog RIA', 'manage_options', md5(HACKLOG_RIA_LOADER), array(__CLASS__,'option_page') );
	}
	
	//option page
	public static function option_page() 
	{
		if(array_key_exists('submit', $_POST))
		{
			$min_width = (int) trim($_POST['min_width']);
			self::set_conf('min_width',$min_width);
			$thumbnail_size = $_POST['thumbnail_size'];
			self::set_conf('thumbnail_size',$thumbnail_size);
			
			//$use_lightbox = trim($_POST['use_lightbox']);
			//self::set_conf('use_lightbox',$use_lightbox);
			
			$link_image = trim($_POST['link_image']);
			self::set_conf('link_image',$link_image);
			
			$class_link = trim($_POST['css_class_link']);
			self::set_conf('css_class_link',$class_link);
			
			$class_img = trim($_POST['css_class_img']);
			self::set_conf('css_class_img',$class_img);
			
			$valid_tags = trim($_POST['valid_tags']);
			self::set_conf('valid_tags',$valid_tags);
			
			$invalid_attr = trim($_POST['invalid_attr']);
			self::set_conf('invalid_attr',$invalid_attr);
			
			self::update_config();

		}
		?>
	<div class="wrap">
	<h2><?php _e(self::$plugin_name) ?> Options</h2>
	<form method="post">
	<table width="100%" cellpadding="5" class="form-table">
	<tr valign="top">
		<th scope="row">	
			thumbnail size：
		</th>
		<td>
			<select name="thumbnail_size" style="width:120px;">
				<?php $selected = self::get_conf('thumbnail_size');?>
				<?php foreach(self::$src_size as $size):?>
				<option value="<?php echo $size;?>" <?php selected( $selected, $size, true );?>> <?php echo $size;?> </option>
			<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">	
		min width image to download：
		</th>
		<td>
			<input type="text" name="min_width" value="<?php echo self::get_conf('min_width');?>"/>
		</td>
	</tr>	
	
	<tr valign="top">
		<th scope="row">	
			Link for the image：
		</th>
		<td>
			<select name="link_image" style="width:120px;">
				<?php $selected2 = self::get_conf('link_image');?>
				
				<option value="1" <?php selected( $selected2, "1", true );?>>Yes</option>
		        <option value="0" <?php selected( $selected2, "0", true );?>>No</option>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">	
			CSS class for link：
		</th>
		<td>
		    <?php $class_link = (trim(self::get_conf('css_class_link'))==''?'photolightbox':trim(self::get_conf('css_class_link')));
			?>
		    <input type="text" name="css_class_link" style="width:480px;" value="<?php echo $class_link; ?>" />
			
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">	
			CSS class for image:
		</th>
		<td>
		    <?php $class_img = (trim(self::get_conf('css_class_img'))==''?'':trim(self::get_conf('css_class_img')));
			?>
		    <input type="text" name="css_class_img" style="width:480px;" value="<?php echo $class_img; ?>" />
			
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">	
			Valid tags when copy/paste:
		</th>
		<td>
		    <?php $valid_tags = (trim(self::get_conf('valid_tags'))==''?'div,p,span,b,strong,a,img,h3,h4,h5,h6,ul,li,ol':trim(self::get_conf('valid_tags')));
		
			
			
			?>
		    <input type="text" name="valid_tags" style="width:480px;" value="<?php echo $valid_tags; ?>" />
			
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">	
			Invalid Attributes:
		</th>
		<td>
		    <?php $invalid_attr = (trim(self::get_conf('invalid_attr'))==''?'id,style,class,width,height,data-pwidth,data-width,alt,title,data-pwidth,data-natural-width,align':trim(self::get_conf('invalid_attr')));
			
			?>
		    <input type="text" name="invalid_attr" style="width:480px;" value="<?php echo $invalid_attr; ?>" />
			
		</td>
	</tr>
	
	</table>
	<p class="submit">
			<input type="submit" class="button-primary" name="submit" value="<?php _e('Save Options');?>" />
	</p>
	</form>
	</div>
<?php
	}
     
} //end class