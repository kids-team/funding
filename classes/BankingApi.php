<?php

namespace Contexis\Funding;

use Contexis\Funding\Countries;
use SepaQr\Data;
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Common\EccLevel;

class BankingApi {
	
	public static function init() {
        
        $instance = new self;
		add_action('wp_ajax_nopriv_funding_qr_code',[$instance, 'generate_qr_code']);
		add_action('wp_ajax_nopriv_funding_payment_info',[$instance, 'get_payment_info']);
		add_action('wp_ajax_funding_qr_code',[$instance, 'generate_qr_code']);
		add_action('wp_ajax_funding_payment_info',[$instance, 'get_payment_info']);
        
    }

	/**
	 * return an image containing the qr code
	 * 
	 * @todo change purpose
	 *
	 * @return void
	 */
    public function generate_qr_code() {
		if(empty($_REQUEST['country']) || empty($_REQUEST['project'])) return;
		$data = $this->get_data();

		$paymentData = Data::create()
			->setName($data['beneficiary'])
			->setIban($data['iban'])
			->setRemittanceText($data['purpose'])
			->setAmount($_REQUEST['amount'] ?? 25);

		$qrOptions = new QROptions([
			'version' => 7,
			'eccLevel' => QRCode::ECC_M, // required by EPC standard
			'imageBase64' => false,
			'addQuietzone'           => true,
			'imageTransparent'       => false,
			'keepAsSquare' => [QRMatrix::M_FINDER|QRMatrix::M_DARKMODULE, QRMatrix::M_LOGO, QRMatrix::M_FINDER_DOT, QRMatrix::M_ALIGNMENT|QRMatrix::M_DARKMODULE],
			'drawCircularModules' => true,
			'circleRadius' => 0.4,
			'outputType' => QRCode::OUTPUT_MARKUP_SVG,
			'svgConnectPaths' => true
		]);
		
		$result = new QRCode($qrOptions);
		header('Content-Type: image/svg+xml');
		echo $result->render($paymentData);
		wp_die();
    }

	/**
	 * Return an array with payment information
	 * 
	 * @todo: change purpose
	 *
	 * @return array
	 */
	public function get_payment_info() {
		if(empty($_REQUEST['country']) || empty($_REQUEST['project'])) return;
		$data = $this->get_data();

		$result = [
			"result" => true,
			"purpose" => $data['purpose'],
			"iban" => $data['iban'],
			"beneficiary" => $data['beneficiary'],
			"bic" => $data['bic'],
			"bank" => $data['bank'],
			"amount" => $_REQUEST['amount'] ?? 25,
			"_full" => $data
		];

		header('Content-Type: application/json');
		echo json_encode($result);
		wp_die();
	}

	/**
	 * Collect payment data depending on Country and project settings
	 *
	 * @return array
	 */
	public function get_data() {
		$country = Countries::find($_REQUEST['country']);
		$project = Projects::find($_REQUEST['project']);

		$exception = $project['exception'] == 0 || $project['exception'] == $country['ID'];
		$reference = $project['reference'] && $exception ? $project['reference'] : $country['reference'];
		$prefix = $project['prefix'] && $exception ? $project['prefix'] : $country['prefix'];
		$suffix = $project['suffix'] && $exception ? $project['suffix'] : $country['suffix'];
		return [
			"iban" => $project['iban'] && $exception ? $project['iban'] : $country['iban'],
			"bic" => $project['bic'] && $exception ? $project['bic'] : $country['bic'],
			"beneficiary" => $project['beneficiary'] && $exception ? $project['beneficiary'] : $country['beneficiary'],
			"bank" => $project['bank'] && $exception ? $project['bank'] : $country['bank'],
			"purpose" => $prefix . ($reference == "number" ? $project['number'] : $project['name']) . $suffix,
			"exception" => $exception,
			"project" => $project,
			"country" => $country
		];
	}
}

BankingApi::init();