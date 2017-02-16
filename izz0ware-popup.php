<?php
/*
Plugin Name: Izz0ware Popup
Plugin URI:
Description: A more advanced Fancybox 2 popup plugin.
Version: 1.0
Author: Izaac Johansson (Izz0ware)
Author URI:
License: GPL2
*/

namespace Izz0ware;

class Popup {
	const FILE = __FILE__;
	const VERSION = '1.0.1';

	private $handlers = array();

	public function __construct() {
		include_once plugin_dir_path( $this::FILE ) . 'includes/class-tgm-plugin-activation.php';
		include_once plugin_dir_path( $this::FILE ) . 'includes/wpalchemy/MetaBox.php';
		include_once plugin_dir_path( $this::FILE ) . 'includes/class.izz0ware.helpers.php';

		add_action( 'tgmpa_register', array( $this, 'check_siteorigin_pagebuilder' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_action( 'init', array( $this, 'add_posttype' ), 0 );
		add_filter( 'manage_edit-izz0ware_popup_columns', array( $this, 'edit_izz0ware_popup_columns' ) );
		add_action( 'manage_izz0ware_popup_posts_custom_column', array( $this, 'manage_izz0ware_popup_columns' ), 10, 2 );
		add_shortcode( 'izz0warepopup', array( $this, 'popup_shortcode' ) );
		add_action( 'admin_menu', array($this, 'add_menu') );
	}

	public function add_menu() {
		add_submenu_page( "edit.php?post_type=izz0ware_popup", "Usage", "Usage", "administrator", "usage" , array($this, 'plugin_usage') );
	}

	public function plugin_usage() {
		include plugin_dir_path($this::FILE).'includes/usage.php';
	}

	public function add_scripts( $hook ) {
		global $post;
		if(is_admin()) {
			// Scripts only loaded if admin page!
		}else{
			// Scripts only loaded if NOT admin page!
		}
		// Scripts loaded on every page
		wp_enqueue_style('bootstrap-buttons', plugin_dir_url( $this::FILE ).'assets/css/bootstrap.css');
		wp_enqueue_script('jquery');
		wp_enqueue_script('fancybox2', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js');
		wp_enqueue_style('fancybox2', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css');
		wp_enqueue_script('fancybox2-buttons', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.js');
		wp_enqueue_script('fancybox2-media', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js');
		wp_enqueue_script('fancybox2-thumbs', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js');
		wp_enqueue_style('fancybox2-buttons', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.css');
		wp_enqueue_style('fancybox2-thumbs', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css');
		wp_enqueue_style('fontawesome-4.6.3', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		wp_enqueue_script('izz0ware-popup', plugin_dir_url($this::FILE).'assets/js/izz0ware-popup.js');
		wp_enqueue_style('izz0ware-grid', plugin_dir_url($this::FILE).'assets/css/izz0ware-grid.css');

		wp_enqueue_style('siteorigin-panels-front');

		if( $hook == 'edit.php' && $post->post_type === 'izz0ware_popup' ) {
			wp_enqueue_script('auto-ta', plugin_dir_url($this::FILE).'assets/js/auto-ta.js');
		}
	}

	public function add_posttype() {
		$labels = array(
			'name' => _x( 'Izz0ware Popups', 'izz0ware_popup' ),
			'singular_name' => _x( 'Popup', 'izz0ware_popup' ),
			'add_new' => _x( 'Add New', 'izz0ware_popup' ),
			'add_new_item' => _x( 'Add New Popup', 'izz0ware_popup' ),
			'edit_item' => _x( 'Edit Popup', 'izz0ware_popup' ),
			'new_item' => _x( 'New Popup', 'izz0ware_popup' ),
			'view_item' => _x( 'View Popup', 'izz0ware_popup' ),
			'search_items' => _x( 'Search Popups', 'izz0ware_popup' ),
			'not_found' => _x( 'No popups found', 'izz0ware_popup' ),
			'not_found_in_trash' => _x( 'No popups found in Trash', 'izz0ware_popup' ),
			'parent_item_colon' => _x( 'Parent Popup:', 'izz0ware_popup' ),
			'menu_name' => _x( 'Izz0ware Popups', 'izz0ware_popup' ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,

			'supports' => array( 'title', 'editor' ),

			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 100,

			'show_in_nav_menus' => false,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => false,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		register_post_type( 'izz0ware_popup', $args );

		$this->add_js_metabox();
	}

	public function edit_izz0ware_popup_columns( $columns ) {
		return array(
			'cb' => '<input type="checkbox"/>',
			'title' => __( 'Title' ),
			'shortcode' => __( 'Shortcode' ),
			'previewpopup' => __( 'Preview (Note: Not all content is supported in the popup!)' ),
			'author' => __( 'Author' ),
			'date' => __( 'Date' ),
			//'debug' => __( 'Debug' )
		);
	}

	public function manage_izz0ware_popup_columns( $column, $post_id ) {
		global $post;
		switch( $column ) {
			case 'shortcode':
				echo '<textarea readonly style="width: 100%; height: auto;">'.htmlentities($this->generate_shortcode($post_id), ENT_QUOTES).'</textarea>';
				break;
			case 'previewpopup' :
				$ret = do_shortcode($this->generate_shortcode($post_id));
				echo $ret;
				break;
			default :
				break;
		}
	}

	public function add_js_metabox() {
		global $izz0warepopupjsmeta;
		$izz0warepopupjsmeta = new \WPAlchemy_MetaBox(array(
			'id' => '_izz0ware_popup',
			'types' => array('izz0ware_popup'),
			'title' => 'Advanced settings',
			'template' => plugin_dir_path($this::FILE) . 'includes/wpalchemy/templates/advanced.php',
			'context' => 'advanced',
			'priority' => 'high',
			'autosave' => true,
		));
	}

	// Popup Shortcode
	public function popup_shortcode( $atts ) {
		global $izz0warepopupjsmeta;
		$a = shortcode_atts( array(
			'id' => '1',
			'text' => '',
			'class' => 'btn btn-default',
			'debug' => false,
		), $atts );
		$datanames = array();
		$datavalues = array();
		$popup = null;
		foreach($atts as $key => $value) {
			if(Helpers::startsWith($key, 'data-')) {
				$datanames[str_replace('data-', '', $key)] = $value;
			}
		}
		if($a['id'] > 0) {
			$popup = get_post( $a['id'], 'ARRAY_A' );
			$izz0warepopupjsmeta->the_meta($a['id']);
			$clickhandler = $izz0warepopupjsmeta->get_the_value('clickhandler');
			$datavalues = $izz0warepopupjsmeta->get_the_value('datanames');
		}else{
			require plugin_dir_path($this::FILE).'includes/examples.php';
		}

		foreach($datavalues as $d) {
			if(!array_key_exists($d['dataname'], $datanames)) {
				$datanames[$d['dataname']] = $d['default'];
			}
		}

		if($a['text']=='' || $a['text'] === null)
			$a['text'] = $popup['post_title'];

		$hndl = md5('izz0warepopup_'.$a['id']);
		$ret = '<a id="'.$hndl.'_id" href="#'.$hndl.'" class="izz0warepopup '.$a['class'].'">'.$a['text'].'</a>'.PHP_EOL;
		if(!in_array($hndl, $this->handlers)) {
			$ret .= '<div id="'.$hndl.'" style="display:none;">'.PHP_EOL;
			// TODO: add site origin page builder stuff
			$ret .= '<h3>'.$popup['post_title'].'</h3>'.PHP_EOL;
			$ret .= '<p>'.apply_filters('the_content', do_shortcode($popup['post_content'])).'</p>'.PHP_EOL;
			$ret .= '</div>'.PHP_EOL;
			if($a['debug']) {
				ob_start();
				echo 'Datanames: ';
				print_r( $datanames );
				$dn = ob_get_clean();
				ob_start();
				echo 'Attributes: ';
				print_r( $atts );
				$as = ob_get_clean();
				$ret .= '<pre>'.$dn.'</pre>';
				$ret .= '<pre>'.$as.'</pre>';
			}
			$ret .= '<script type="text/javascript">'.PHP_EOL;
			$ret .= 'jQuery(document).ready(function($) {'.PHP_EOL;
			$ret .= '$("#'.$hndl.'_id").fancybox('.PHP_EOL;
			$ret .= '{'.PHP_EOL;
			$ret .= 'minWidth: \'25%\','.PHP_EOL;
			$ret .= 'minHeight: \'25%\','.PHP_EOL;
			$ret .= 'autoWidth: true,'.PHP_EOL;
			$ret .= 'autoHeight: true,'.PHP_EOL;
			$ret .= 'afterShow: '.PHP_EOL;
			$ret .= 'function() {'.PHP_EOL;
			$ret .= 'var hndl = "' . $hndl . '_id";'.PHP_EOL;
			$ret .= 'var content = $("#'.$hndl.'");'.PHP_EOL;
			foreach($datanames as $k => $v) {
				$ret .= 'var '.$k.' = "'.$v.'";'.PHP_EOL;
			}
			ob_start();
			print_r($clickhandler);
			$m = ob_get_clean();
			$ret .= $m.PHP_EOL;
			$ret .= '$.fancybox.update();'.PHP_EOL;
			$ret .= '} } ); });'.PHP_EOL;
			$ret .= '</script>';
			$this->handlers[] = $hndl;
		}
		return $ret;
	}

	private function generate_shortcode($postid) {
		global $izz0warepopupjsmeta;
		//$p = get_post($postid, 'ARRAY_A');
		$izz0warepopupjsmeta->the_meta($postid);
		$data = $izz0warepopupjsmeta->get_the_value('datanames');

		$d = '';
		foreach($data as $v) {
			$d .= '   data-'.$v['dataname'].'="'.$v['default'].'"'.PHP_EOL;
		}

		$ret = '[izz0warepopup id="'.$postid.'"'.PHP_EOL.
		       '   text="Popup Link"'.PHP_EOL .
		       $d.']';

		return $ret;
	}

	public function check_siteorigin_pagebuilder($checkonly = false) {
		if(!$checkonly) {
			$plugins = array(
				array(
					'name'     => 'SiteOrigin Page Builder', // The plugin name.
					'slug'     => 'siteorigin-panels', // The plugin slug (typically the folder name).
					'required' => false, // If false, the plugin is only 'recommended' instead of required.
				),
			);

			$config = array(
				'default_path' => '',
				// Default absolute path to pre-packaged plugins.
				'menu'         => 'tgmpa-install-plugins',
				// Menu slug.
				'has_notices'  => true,
				// Show admin notices or not.
				'dismissable'  => true,
				// If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',
				// If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,
				// Automatically activate plugins after installation or not.
				'message'      => '',
				// Message to output right before the plugins table.
				'strings'      => array(
					'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
					'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
					'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ),
					// %s = plugin name.
					'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
					'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
					// %1$s = plugin name(s).
					'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
					// %1$s = plugin name(s).
					'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
					// %1$s = plugin name(s).
					'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
					// %1$s = plugin name(s).
					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
					// %1$s = plugin name(s).
					'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
					// %1$s = plugin name(s).
					'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
					// %1$s = plugin name(s).
					'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
					// %1$s = plugin name(s).
					'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
					'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
					'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
					'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ),
					// %s = dashboard link.
					'nag_type'                        => 'updated'
					// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
				)
			);

			tgmpa( $plugins, $config );

			if ( \Izz0ware\Helpers::is_plugin_installed( 'siteorigin-panels' ) ) {
				$s = maybe_unserialize( get_option( 'siteorigin_panels_settings' ) );
				if ( ! in_array( 'izz0ware_popup', $s['post-types'] ) ) {
					$s['post-types'][] = 'izz0ware_popup';
					update_option( 'siteorigin_panels_settings', serialize( $s ) );

					return true;
				}
			}
		}else{
			return \Izz0ware\Helpers::is_plugin_installed( 'siteorigin-panels' );
		}
		return false;
	}
}

new \Izz0ware\Popup();