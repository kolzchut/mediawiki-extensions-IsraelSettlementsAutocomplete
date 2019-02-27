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
class IsraelSettlementsGetSettlementsAndPopulation extends IsraelSettlementsBase {
	static $settelmentNameColumn;
	static $zoneNameColumn;
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
		self::$settelmentNameColumn = 1;
		self::$zoneNameColumn = 9;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$result = $this->getResult();
		
		$result->addValue( null, 'settlementsAndPopulation', self::getSettelmentsAndPopulation( ) );
	}

	protected function getSettelmentsAndPopulation() {
		$allData = self::getAllData();
		$settelmentsAndPopulation =[];
		foreach ($allData as $dataPart) {
			$settelmentsAndPopulation[ $dataPart[ self::$settelmentNameColumn] ] = $dataPart[ self::$zoneNameColumn ];
		}
		return $settelmentsAndPopulation;
	}
	protected function getAllowedParams() {
		return array(
		);
	}

	protected function getParamDescription() {
		return array(
		);
	}

	protected function getDescription() {
		return 'Returns list of israeli settlements and population number';
	}

	protected function getExamples() {
		return array(
			'action=israelsettlementsgetsettlementsandpopulation',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
	

}
