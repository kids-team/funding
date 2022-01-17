<?php

namespace Contexis\Funding;

class Countries {

	public static function init() {
		$instance = new self;

		add_action("init", [$instance, "create_post_type"]);
		add_action( 'add_meta_boxes', array($instance, 'add_meta_boxes') );
		add_action( 'save_post', array($instance, 'save'), 1, 2 );

	}

	public function create_post_type() {

		$args = [
			'hierarchical' 		  => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'show_admin_column'	  => true,
			'show_in_menu'        => false,
			'menu_position'       => 30,
			'query_var' 		  => true, 
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true, 
			'supports'            => [ 'title', 'thumbnail', 'page-attributes' ],
			'labels' => [
				'name'               => __('Countries', 'funding'),
				'singular_name'      => __('Country', 'funding'),
				'add_new'            => __('Create new Country', 'funding'),
				'add_new_item'       => __('Create new Country', 'funding'),
				'edit_item'          => __('Edit Country', 'funding'),
				'new_item'           => __('New Country', 'funding'),
				'all_items'          => __('All Countries' ,'funding'),
				'view_item'          => __('View Country', 'funding'),
				'search_items'       => __('Search Countries', 'funding'),
				'not_found'          => __('No Country found', 'funding'),
				'not_found_in_trash' => __('No Country found in trash', 'funding'),
				'parent_item_colon'  => '',
				'menu_name'          => __('Countries', 'funding')
			]
		];

		register_post_type('ctx-country', $args);
	}

	public function add_meta_boxes() {
        add_meta_box(
			'ctx_country_settings',
			__( 'Payment Settings', 'funding' ),
			[$this, 'metabox_callback'],
			'ctx-country',
			'normal'
        ); 
    }

	public function metabox_callback() {
		global $post;

		$reference = get_post_meta( $post->ID, 'reference', true );

		

		$ref = [
			"name" => $reference == 'name' ? "selected" : "",
			"number" => $reference == 'number' ? "selected" : ""
		];

		echo "<table class='form-table funding-country-settings'><tbody>";
		echo "<tr><th scope='row'>" . __("IBAN", "funding") . "</th><td><input name='iban' value='" . get_post_meta( $post->ID, 'iban', true ) . "' type='text'/></td></tr>";
		echo "<tr><th scope='row'>" . __("Bank", "funding") . "</th><td><input name='bank' value='" . get_post_meta( $post->ID, 'bank', true ) . "' type='text'/></td></tr>";
		echo "<tr><th scope='row'>" . __("BIC", "funding") . "</th><td><input type='text' value='" . get_post_meta( $post->ID, 'bic', true ) . "' name='bic'/></td></tr>";
		echo "<tr><th scope='row'>" . __("Beneficiary", "funding") . "</th><td><input type='text' value='" . get_post_meta( $post->ID, 'beneficiary', true ) . "' name='beneficiary'/></td></tr>";
		echo "<tr><th scope='row'>" . __("Reference", "funding") . "</th><td><select name='reference'>";
			echo "<option value='name' " . $ref['name'] . ">" . __("Project Name", "funding") . "</option>";
			echo "<option value='number' " . $ref['number'] . ">" . __("Project Number", "funding") . "</option></select>";
		echo "</td></tr>";
		printf("<tr><th scope='row'>" . __("Reference Prefix", "funding") . "</th><td><input type='text' value='" . get_post_meta( $post->ID, 'ref-pre', true ) . "' name='ref-pre'/><p class='description'>" . __("Will be printed %s the project's name or number", "funding") . "</p></td></tr>", "<i>" . __("before", "funding") . "</i>");
		printf("<tr><th scope='row'>" . __("Reference Suffix", "funding") . "</th><td><input type='text' value='" . get_post_meta( $post->ID, 'ref-suf', true ) . "' name='ref-suf'/><p class='description'>" . __("Will be printed %s the project's name or number", "funding") . "</p></td></tr>", "<i>" . __("after", "funding") . "</i>");
		echo "</tbody></table>";
	}

	public function save( $post_id, $post ) {
		if( $post->post_type != "ctx-country" || !current_user_can( 'edit_post', $post_id ) ) return $post_id;
		if ( !isset( $_POST['iban'] )) return $post_id;
        
		$meta = [];
    
        $meta['iban'] = sanitize_text_field( $_POST['iban'] );
        $meta['bank'] = sanitize_text_field( $_POST['bank'] );
		$meta['bic'] = sanitize_text_field( $_POST['bic'] );
		$meta['beneficiary'] = sanitize_text_field( $_POST['beneficiary'] );
		$meta['reference'] = sanitize_text_field( $_POST['reference'] );
		$meta['ref-pre'] = sanitize_text_field( $_POST['ref-pre'] );
		$meta['ref-suf'] = sanitize_text_field( $_POST['ref-suf'] );
    
        foreach ( $meta as $key => $value ) {    
            if ( get_post_meta( $post_id, $key, false ) ) {
                update_post_meta( $post_id, $key, $value );
                continue;
            } 

            if ( !$value ) {
                delete_post_meta( $post_id, $key );
                continue;
            }
            
            add_post_meta( $post_id, $key, $value); 
        }

	}

	public static function get_json() {
		$args = [
			'post_type' => 'ctx-country',
			'post_status' => 'publish', 
			'orderby' => 'menu_order title', 
			'order' => 'ASC', 
			'fields' => ['id', 'meta'],
		];

		$query = new \WP_Query($args);

		$result = array_map(function($post) {
			return [
				"ID" => $post->ID,
				"title" => $post->post_title,
				"name" => $post->ID
			];
		}, $query->posts);

		return $result;

	}

	public static function find($id) {
		$args = [
			'post_type' => 'ctx-country',
			'p' => $id, 
		];

		$query = new \WP_Query($args);

		$result = array_map(function($post) {
			return [
				"ID" => $post->ID,
				"title" => $post->post_title,
				"name" => $post->post_name,
				"iban" => get_post_meta($post->ID, 'iban', true),
				"bic" => get_post_meta($post->ID, 'bic', true),
				"bank" => get_post_meta($post->ID, 'bank', true),
				"beneficiary" => get_post_meta($post->ID, 'beneficiary', true),
				"reference" => get_post_meta($post->ID, 'reference', true),
				"ref-pre" => get_post_meta($post->ID, 'ref-pre', true),
				"ref-suf" => get_post_meta($post->ID, 'ref-suf', true)
			];
		}, $query->posts);

		return $result[0];
	}


}

Countries::init();