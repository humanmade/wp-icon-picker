<?php
/**
 * Base font icon handler
 *
 * @package Icon_Picker
 * @author Dzikri Aziz <kvcrvt@gmail.com>
 */

require_once dirname( __FILE__ ) . '/base.php';

/**
 * Generic handler for icon fonts
 *
 */
abstract class Icon_Picker_Type_Font extends Icon_Picker_Type {

	/**
	 * Stylesheet ID
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string
	 */
	protected $stylesheet_id = '';

	/**
	 * JS Controller
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string
	 */
	protected $controller = 'Font';

	/**
	 * Template ID
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string
	 */
	protected $template_id = 'font';


	/**
	 * Get icon groups
	 *
	 * @since  0.1.0
	 * @return array
	 */
	public function get_groups() {}


	/**
	 * Get icon names
	 *
	 * @since  0.1.0
	 * @return array
	 */
	public function get_items() {}


	/**
	 * Register assets
	 *
	 * @since  0.1.0
	 * @action icon_picker_loader_init
	 * @param  Icon_Picker_Loader      $loader Icon_Picker_Loader instance.
	 * @return void
	 */
	public function register_assets( Icon_Picker_Loader $loader ) {
		if ( empty( $this->stylesheet_uri ) ) {
			return;
		}

		$register = true;
		$deps     = false;
		$styles   = wp_styles();

		if ( $styles->query( $this->stylesheet_id, 'registered' ) ) {
			$object = $styles->registered[ $this->stylesheet_id ];

			if ( version_compare( $object->ver, $this->version, '<' ) ) {
				$deps = $object->deps;
				wp_deregister_style( $this->stylesheet_id );
			} else {
				$register = false;
			}
		}

		if ( $register ) {
			wp_register_style( $this->stylesheet_id, $this->stylesheet_uri, $deps, $this->version );
		}

		$loader->add_style( $this->stylesheet_id );
	}


	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 * @param array $args Optional arguments passed to parent class.
	 */
	public function __construct( array $args = array() ) {
		parent::__construct( $args );

		if ( empty( $this->stylesheet_id ) ) {
			$this->stylesheet_id = $this->id;
		}

		add_action( 'icon_picker_loader_init', array( $this, 'register_assets' ) );
	}


	/**
	 * Get properties
	 *
	 * @since  0.1.0
	 * @return array
	 */
	public function get_props() {
		$props = array(
			'id'         => $this->id,
			'name'       => $this->name,
			'controller' => $this->controller,
			'data'       => array(
				'groups'     => $this->groups,
				'items'      => $this->items,
			),
		);

		/**
		 * Filter icon type properties
		 *
		 * @since 0.1.0
		 * @param array $props Icon type properties.
		 */
		$props = apply_filters( "icon_picker_{$this->id}_props", $props );

		return $props;
	}


	/**
	 * Get media templates
	 *
	 * @since  0.1.0
	 * @return array
	 */
	public function get_templates() {
		$templates = array(
			'item' => sprintf(
				'<div class="attachment-preview js--select-attachment">
					<div class="thumbnail">
						<span class="_icon"><i class="{{data.type}} {{ data.id }}"></i></span>
						<div class="filename"><div>{{ data.name }}</div></div>
					</div>
				</div>
				<a class="check" href="#" title="%s"><div class="media-modal-icon"></div></a>',
				esc_attr__( 'Deselect', 'icon-picker' )
			),
		);

		/**
		 * Filter media templates
		 *
		 * @since 0.1.0
		 * @param array $templates Media templates.
		 */
		$templates = apply_filters( 'icon_picker_font_media_templates', $templates );

		return $templates;
	}
}
