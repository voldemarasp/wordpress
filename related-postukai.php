<?php
/*
Plugin name: Related postukai
*/

add_action( 'plugins_loaded', 'TinyTemplate2' );
// register_activation_hook( __FILE__,    array( TinyTemplate2(), 'activate' ) );
// register_deactivation_hook( __FILE__,  array( TinyTemplate2(), 'deactivate' ) );
// register_uninstall_hook( __FILE__,     array( 'TinyTemplate2Class', 'uninstall' ) );

function TinyTemplate2() {
	if ( false === TinyTemplate2Class::$instance ) {
		TinyTemplate2Class::$instance = new TinyTemplate2Class();
	}
	return TinyTemplate2Class::$instance;
}
class TinyTemplate2Class {
	public static $instance = false;
	public $plugin_path = '';
	public $options = array(
		'a' => 'test',
		'content_position' => 'after',
	);
	public function __construct() {

	$this->options = wp_parse_args( get_option( 'tinytemplate2_options' ), $this->options );
    $this->load_settings2();
		// do something when plugin loads.
		$this->plugin_path = plugin_dir_path( __FILE__ );

		add_shortcode( 'dar_vienas', array($this, 'show') );
		add_filter( 'the_content', array($this, 'content') );
		add_action( 'add_meta_boxes_post', array ($this, 'add_metabox') );
		add_action( 'save_post', array ($this, 'save_metabox') );

	}
	public function activate() {
		// do something on plugin activation.
	}
	public function deactivate() {
		// do something on plugin deactivation.
	}
	public static function uninstall() {
		// do something on plugin uninstallation.
	}

	public function content ( $content ) {
		if ( 'post' !== get_post_type() ) {
			return $content;
		}

		if ('before' === $this->options['content_position']) {
			$content = $this->show() . PHP_EOL . $content;
		} elseif ( 'after' === $this->options['content_position']) {
			$content .= PHP_EOL . $this->show();
		}

		return $content;
	}


	public function add_metabox() {

		add_meta_box (
			'ca_related_metabox',
			__('Related Posts', 'ca_related'),
			array ($this, 'print_metabox'),
			'post'
		);

	}

	public function print_metabox( $post) {
		$list = get_post_meta( $post->ID, '_tinyrelated_list', true );

		$output = '<table class="form-table"><tbody>';
		
		for ($i=0; $i < 3; $i++) { 	
			$output .= $this->metabox_field( array(
				'id' => 'tinyrelated_list' . $i,
				'name' => 'tinyrelated_list[' . $i . ']',
				'value' => isset( $list[ $i ] ) ? $list[ $i ] : '',
				'type' => 'select_posts'
			));
		}
		$output .= '</tbody></table>';
		wp_nonce_field( 'tinyrelated_metabox', 'tinyrelated_metabox_nonce' );
		echo $output;

	}

	public function metabox_field ( $args ) {
		$return = '<tr>';
		$return .= '<th scope="row"';
		$return .= '<label for="' . esc_attr( $args['id'] ) . '">';
		$return .= esc_html( $args['label'] );
		$return .= '</label>';
		$return .= '</th>';
		$return .= '<td>';

		switch ( $args['type'] ) {
			case 'select_posts';

			$posts = get_posts ('posts_per_page=99999999');

			$return .= '<select name="' . esc_attr($args['name']) . '" id="' . esc_attr($args['id']) . '">';
			$return .= '<option value="">' . __('-none-', 'ca_related') . '</option>';

			if ( !empty($posts)) {
				foreach ($posts as $post) {
					$selected = selected( $args['value'], $post->ID, false );
					$return .= '<option value="' . $post->ID . '" ' . $selected . '>' . $post->post_title . '</option>';
				}
			}

			$return .= '</select>';

			break;

		}

		$return .= '</td>';
		$return .= '</tr>';

		return $return;

	}


	public function save_metabox ( $post_id ) {
		if ( ! isset($_POST['tinyrelated_list'])) {
			return false;
		}

		if ( defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
	return false;
}

if ( ! current_user_can( 'edit_post', $post_id )) {
	return false;
}

if ( !wp_verify_nonce( $_POST['tinyrelated_metabox_nonce'], 'tinyrelated_metabox' )) {
	return false;
}

$data = array();

foreach ($_POST['tinyrelated_list'] as $key => $value) {
	if ( ! $value ) {
		$data[ $key ] = '';
	} else {
		$data[ $key ] = $value * 1;
	}
}

update_post_meta($post_id, '_tinyrelated_list', $_POST['tinyrelated_list']);

}



public function show( $args = array(), $content = '') {
	$defaults = array(
		'title'       => __( 'Related posts', 'temos-katalogas' ),
	);
	$args = wp_parse_args( $args, $defaults );

	$list = get_post_meta( get_the_ID(), '_tinyrelated_list', true );

	if ( is_array($list) ) {
		foreach ($list as $key => $post_id) {
			if ( ! $post_id ) {
				unset( $list[ $key ]);
			} else {
				$item = array(
					'title' => get_the_title( $post_id ),
					'link' => get_permalink( $post_id ),
				);
				$list[ $key ] = $item;
			}
		}

	} else {
		return '';
	}



	$return = '<div class="ca_related_container">';
	$return .= '<h2>' . $args['title'] . '</h2>';
	$return .= $this->build_list( $list );
	$return .= '</div>';

	return $return;
}

public function build_list($list = array()) {

	$list = apply_filters( 'ca_related_list_items', $list );
	$return = '<ul class="ca_related">';

	foreach ($list as $item) {
		$return .= '<li>';
		$return .= '<a href="' . esc_attr( $item['link'] ) . '">';
		$return .= esc_html( $item['title'] );
		$return .= '</a>';
		$return .= '</li>';
	}

	$return .= '</ul>';
	$return = apply_filters( 'ca_related_list_output', $return, $list );

	return $return;

}

function related_posts_bottom ( $content ) {
	if ( is_single() ) {
		$return = $content;
		$return .= do_shortcode('[dar_vienas]');
		return $return;
	}

	function related_posts_top ( $content ) {
		if ( is_single() ) {

			$return = do_shortcode('[dar_vienas]');
			$return .= $content;

			return $return;
		}


	}
}

public $settings2 = false;
	public function load_settings2() {
		$this->settings2 = array(
			'title' 			=> __( 'TinyTemplate2 Settings', 'tinytemplate2' ),
			'menu_title'	=> __( 'TinyTemplate2', 'tinytemplate2' ),
			'slug' 				=> 'tinytemplate2-settings',
			'option'			=> 'tinytemplate2_options',
			// optional settings.
			'description'	=> __( 'Some general information about the plugin', 'tinytemplate2' ),
			'tabs'	=> array(
				'main'	=> array(
					'sections' => array(
						'inputs' => array(
							'title'				=> __( 'Section #1 - Inputs', 'tinytemplate2' ),
							'description'	=> __( 'Showcases various <code>&lt;input&gt;</code> based fields', 'tinytemplate2' ),
							'fields'	=> array(
								'input_simple' => array(
									'title'	=> __( 'Simple Input', 'tinytemplate2' ),
								),
								'input_description' => array(
									'title'	=> __( 'Simple Input', 'tinytemplate2' ),
									'description'	=> __( 'With a description', 'tinytemplate2' ),
								),
								'input_placeholder' => array(
									'title'	=> __( 'Paceholder Input', 'tinytemplate2' ),
									'attributes' => array(
										'placeholder'	=> __( 'Placeholder example', 'tinytemplate2' ),
									),
								),
								'input_number' => array(
									'title'	=> __( 'Number Input', 'tinytemplate2' ),
									'attributes' => array(
										'type'	=> 'number',
										'step'	=> 2,
										'min'		=> 10,
									),
								),
								'input_password' => array(
									'title'	=> __( 'Password Input', 'tinytemplate2' ),
									'attributes' => array(
										'type'	=> 'password',
									),
								),
							),
						),
						'lists' => array(
							'title'	=> __( 'Section #2 - lists and checkboxes', 'tinytemplate2'),
							'description'	=> __( 'Checkbox lists, radiobox list, select, checkbox, etc.', 'tinytemplate2' ),
							'fields'	=> array(
								'lists_check' => array(
									'title'	=> __( 'Checkbox', 'tinytemplate2' ),
									'label'	=> __( 'Checkbox', 'tinytemplate2' ),
									'callback'	=> 'checkbox',
								),
								'lists_select' => array(
									'title'	=> __( 'Select', 'tinytemplate2' ),
									'list'	=> array( ''=>'-none-', 'a' => 'A', 'b' => 'B' ),
									'callback'	=> 'listfield',
								),
								'lists_select_multi' => array(
									'title'	=> __( 'Select Multiple', 'tinytemplate2' ),
									'list'	=> array( ''=>'-none-', 'a' => 'A', 'b' => 'B', 'c'=> 'C', 'd'=>'D' ),
									'callback'	=> 'listfield',
									'attributes' => array(
										'size'	=> 3,
										'multiple'=>true,
									)
								),
								'content_position' => array(
									'title'	=> __( 'Select Radiobutton', 'tinytemplate2' ),
									'list'	=> array( ''=>'-none-', 'before' => 'Before', 'after' => 'After' ),
									'callback'	=> 'listfield',
									'attributes'	=> array(
										'type'	=> 'radio',
									)
								),
								'lists_checkbox' => array(
									'title'	=> __( 'Select Checkbox', 'tinytemplate2' ),
									'list'	=> array( ''=>'-none-', 'a' => 'A', 'b' => 'B' ),
									'callback'	=> 'listfield',
									'attributes'	=> array(
										'type'	=> 'checkbox',
									)
								),
							),
						),
						'misc' => array(
							'title'	=> __( 'Section #3 - other fields', 'tinytemplate2'),
							'description'	=> __( 'Other types of fields', 'tinytemplate2' ),
							'fields'	=> array(
								'textarea' => array(
									'title'	=> __( 'Textarea', 'tinytemplate2' ),
									'callback'	=> 'textarea',
									'attributes'	=> array(
										'rows' => 10,
										'cols' => 30,
									)
								),
								'link' => array(
									'title'	=> __( 'Link', 'tinytemplate2' ),
									'label'	=> __( 'Click', 'tinytemplate2' ),
									'attributes'	=> array(
										'href' => '?gogogo',
										// 'class' => 'button',
									),
									'callback'	=> 'url',
								),
								'link_button' => array(
									'title'	=> __( 'Link Button', 'tinytemplate2' ),
									'label'	=> __( 'Click', 'tinytemplate2' ),
									'attributes'	=> array(
										'href' => '?gogogo',
										'class' => 'button',
									),
									'callback'	=> 'url',
								),
								'link_primary' => array(
									'title'	=> __( 'Link Primary', 'tinytemplate2' ),
									'label'	=> __( 'Click', 'tinytemplate2' ),
									'attributes'	=> array(
										'href' => '?gogogo',
										'class' => 'button button-primary',
										'target'	=> '_blank',
									),
									'callback'	=> 'url',
								),
							),
						),
					),
				),
			),
			'l10n' => array(
				'no_access'			=> __( 'You do not have sufficient permissions to access this page.', 'tinytemplate2' ),
				'save_changes'	=> esc_attr( 'Save Changes', 'tinytemplate2' ),
			),
		);
		require_once( $this->plugin_path . "tiny/tiny.settings2.php" );
		// require_once( self::$plugin_path . 'tiny/tiny.options.php' );
		$this->settings2 = new TinySettings2( $this->settings2, $this );
	}

}


