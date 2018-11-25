<?php
/**
 * @file
 * @ingroup Cargo
 */

/**
 * Adds the 'cargoautocomplete' action to the MediaWiki API.
 *
 * @ingroup Cargo
 *
 * @author Yaron Koren
 */
class IsraelSettlementsGet extends ApiBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		
		// if ( $templateStr == '' ) {
		// 	CargoUtils::dieWithError( $this, 'The template must be specified', 'param_substr' );
		// }
		$data = self::getAllValues();
		$data_filtered = [];
		//echo $params['term'] . '</br>';
		foreach ($data as $part) {
			if(strpos($part, $params['term']) !== FALSE){
				$data_filtered[] = ['title' => $part];
			}

		}

		//die();
		// Set top-level elements.
		$result = $this->getResult();
		//die(print_r($params));
		$result->setIndexedTagName( $params, 'p' );
		$result->addValue( null, 'pfautocomplete', $data_filtered );
	}

	protected function getAllowedParams() {
		return array(
			'term' => null,
		);
	}

	protected function getParamDescription() {
		return array(
			'term' => 'Search substring',
		);
	}

	protected function getDescription() {
		return 'Returns list of israeli settlements includes term string';
	}

	protected function getExamples() {
		return array(
			'action=getisraelsettlements&term=ירו',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}

	public static function getAllValues(  ) {
		$base = dirname(__FILE__);
		$csvString = file_get_contents("$base/../inc/settlements.csv");
		$csvArray = str_getcsv($csvString, "\n"); //parse the rows 
		foreach($csvArray as &$row) $row = str_getcsv($row, ","); //parse the items in rows 
		return array_column($csvArray, '2');
	}

}
