<?php

namespace Contexis\Funding;

use WP_Query;

class Projects {

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
			'supports'            => [ 'title', 'thumbnail', 'page-attributes', 'excerpt' ],
			'labels' => [
				'name'               => __('Projects', 'funding'),
				'singular_name'      => __('Project', 'funding'),
				'add_new'            => __('Create new project', 'funding'),
				'add_new_item'       => __('Create new project', 'funding'),
				'edit_item'          => __('Edit project', 'funding'),
				'new_item'           => __('New project', 'funding'),
				'all_items'          => __('All projects' ,'funding'),
				'view_item'          => __('View project', 'funding'),
				'search_items'       => __('Search projects', 'funding'),
				'not_found'          => __('No project found', 'funding'),
				'not_found_in_trash' => __('No project found in trash', 'funding'),
				'parent_item_colon'  => '',
				'menu_name'          => __('Projects', 'funding')
			]
		];

		register_post_type('ctx-project', $args);
	}

	public function add_meta_boxes() {
        add_meta_box(
			'ctx_project_settings',
			__( 'Project Settings', 'funding' ),
			[$this, 'metabox_callback'],
			'ctx-project',
			'normal'
        ); 
    }

	public function metabox_callback() {
		global $post;
		echo "<table class='form-table'><tbody>";
		echo "<tr><th scope='row'>" . __("Project Number", "funding") . "</th><td><input type='text' name='projectnumber' value='" . get_post_meta($post->ID, 'projectnumber', true) . "'/></td></tr>";
		echo "</tbody></table>";
	}

	public function save( $post_id, $post ) {
		if( $post->post_type != "ctx-project" || !current_user_can( 'edit_post', $post_id ) ) return $post_id;
		if ( !isset( $_POST['projectnumber']) ) return $post_id;
        
		$meta = [];
    
        $meta['projectnumber'] = sanitize_text_field( $_POST['projectnumber'] );
       
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
			'post_type' => 'ctx-project',
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
				"name" => $post->ID,
				"description" => $post->post_excerpt,
				"thumbnail" => get_the_post_thumbnail_url($post->ID, 'thumbnail')
			];
		}, $query->posts);


		return $result;

	}

	public static function find($id) {
		$args = [
			'post_type' => 'ctx-project',
			'p' => $id, 
		];

		$query = new \WP_Query($args);

		$result = array_map(function($post) {
			return [
				"ID" => $post->ID,
				"title" => $post->post_title,
				"name" => $post->post_name,
				"number" => get_post_meta($post->ID, 'projectnumber', true)
			];
		}, $query->posts);

		return $result[0];
	}


}

Projects::init();