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
        
    }

	/**
	 * return an image containing the qr code
	 * 
	 * @todo change purpose
	 *
	 * @return void
	 */
    public function generate_qr_code() {
		//if(empty($_REQUEST['country']) || empty($_REQUEST['project'])) return;
		$country = Countries::find($_REQUEST['country']);
		$project = Projects::find($_REQUEST['project']);
		
		$paymentData = Data::create()
			->setName($country['beneficiary'])
			->setIban($country['iban'])
			->setRemittanceText($country['ref-pre'] . ($country['reference'] == "number" ? $project['number'] : $project['name']) . $country['ref-suf'])
			->setAmount($_REQUEST['amount'] ?? 10);

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
		$country = Countries::find($_REQUEST['country']);
		$project = Projects::find($_REQUEST['project']);
		$prefix = $country['ref-pre'] ? $country['ref-pre'] . "-" : "";
		$suffix = $country['ref-suf'] ? "-" . $country['ref-suf'] : "";
		$result = [
			"result" => true,
			"purpose" => $prefix . ($country['reference'] == "number" ? $project['number'] : $project['name']) . $suffix,
			"iban" => $country['iban'],
			"beneficiary" => $country['beneficiary'],
			"bic" => $country['bic'],
			"bank" => $country['bank'],
			"amount" => $_REQUEST['amount'] ?? 10
		];

		header('Content-Type: application/json');
		echo json_encode($result);
		wp_die();
	}
}

BankingApi::init();