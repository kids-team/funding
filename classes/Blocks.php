<?php
namespace Contexis\Funding;

class Blocks {

	public $assets = [
		'style'         => 'funding-style',
		'editor_script' => 'funding-blocks',
		'editor_style'  => 'funding-blocks-style',
	];

	public $dmm;

	public static function init(Start $dmm) {
		$instance = new self;
		$instance->dmm = $dmm;
		add_action('init', [$instance, "register_assets"]);
		add_action('init', [$instance, "register_blocks"]);
		add_action('admin_enqueue_scripts', [$instance, "add_admin_script"]);
	}

	public function register_assets() {
		$dir = __DIR__ . "/../assets/";

		if ( ! file_exists( $dir . "backend.asset.php" ) ) {
				throw new \Error(
						'You need to run `npm start` or `npm run build` for the funding blocks first.'
				);
		}

		$script_asset = require( $dir . "backend.asset.php" );
		$frontend_asset = require( $dir . "frontend.asset.php" );

		wp_register_script(
			$this->assets['editor_script'],
			plugins_url( '../assets/backend.js', __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);

		wp_set_script_translations( $this->assets['editor_script'], 'ctx-blocks', plugin_dir_path( __FILE__ ) . '../languages' );
		
		wp_register_style(
			$this->assets['editor_style'],
			plugins_url( '../assets/backend.css', __FILE__ ),
			array(),
			$script_asset['version']
		);

		wp_register_style(
			$this->assets['style'],
			plugins_url( '../assets/style-backend.css', __FILE__ ),
			array(),
			$script_asset['version']
		);

		wp_enqueue_script('funding-frontend', plugin_dir_url(__FILE__) . "../assets/frontend.js", $frontend_asset['dependencies'], $frontend_asset['version'], true);
		wp_set_script_translations( 'funding-frontend', 'funding', plugin_dir_path( __FILE__ ) . '../languages' );

		wp_localize_script('funding-frontend', "fundingScriptData", [
			"countries" => Countries::get_json(),
			"projects" => Projects::get_json(),
		]);

	}

	public function add_admin_script() {
	wp_enqueue_script('ctx-block-filter', plugin_dir_url(__FILE__) . "../assets/admin.js", [], false, true);
	}

	public function register_blocks() {
		register_block_type(
			"funding/mollie", array_merge(
				['name' => 'funding/mollie', 
				'api_version' => 2, 
				'render_callback' => [$this->dmm, 'dmm_donate_form'], 
				'attributes' => ['preview' => ["type" => "boolean", "default" => false]]]
				, $this->assets)
		);

		register_block_type(
			"funding/transfer", array_merge(
				['name' => 'funding/transfer', 
				'api_version' => 2, 
				'render_callback' => ["\Contexis\Funding\Transfer", 'render_block'], 
				'attributes' => ['preview' => ["type" => "boolean", "default" => false]]]
				, $this->assets)
		);
	}
}