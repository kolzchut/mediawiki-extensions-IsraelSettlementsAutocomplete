<?php
/**
 * @file
 * @ingroup Openfox
 */

/**
 * Adds the 'getisraelsettlementbyid' action to the MediaWiki API.
 *
 * @ingroup Cargo
 *
 * @author Openfox and Yizchak krumbein
 */
class IsraelSettlementsGetSttlementById extends IsraelSettlementsBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		
		$data = self::getSettlementById( $params['id'] );
		// if ( $templateStr == '' ) {
		// 	CargoUtils::dieWithError( $this, print_r($data,1), 'param_substr' );
		// }
		

		//die();
		// Set top-level elements.
		$result = $this->getResult();
		//die(print_r($params));
		$result->setIndexedTagName( $params, 'p' );
		$result->addValue( null, 'data',  $data );
	}

	protected function getAllowedParams() {
		return array(
			'id' => null,
		);
	}

	protected function getParamDescription() {
		return array(
			'id' => 'Settlement ID',
		);
	}

	protected function getDescription() {
		return 'return settlement by its ID';
	}

	protected function getExamples() {
		return array(
			'action=getisraelsettlementbyid&id=3000',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
	public static function getSettlementById( $id ) {
		$data = self::getAllData();
		$data = array_filter($data, function( $part ) use ($id){
			return (int) $id === (int) $part[0];
		});
		$settlement = array_pop($data);
		//add settlements
		IsraelSettlementsGetSettlementsPerZone::init();
		$settlement[] = IsraelSettlementsGetSettlementsPerZone::getSettlementsPerSettlementOrZone( $settlement[1] );
		return $settlement;
	}

}
