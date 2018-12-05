<?php
/**
 * @file
 * @ingroup Openfox
 */

/**
 * Adds the 'getisraelsettlements' action to the MediaWiki API.
 *
 * @ingroup Cargo
 *
 * @author Openfox and Yizchak krumbein
 */
class IsraelSettlementsGet extends IsraelSettlementsBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		
		$data = self::getAllSettlements();
		// if ( $templateStr == '' ) {
		// 	CargoUtils::dieWithError( $this, print_r($data,1), 'param_substr' );
		// }
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
	public static function getAllSettlements(  ) {
		return array_column(self::getAllData(),'1');
	}

}
