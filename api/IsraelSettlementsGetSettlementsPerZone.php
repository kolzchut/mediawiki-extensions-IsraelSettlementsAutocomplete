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
class IsraelSettlementsGetSettlementsPerZone extends IsraelSettlementsBase {
	static $settelmentNameColumn;
	static $zoneNameColumn;
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
		self::$settelmentNameColumn = '1';
		self::$zoneNameColumn = '8';
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$result = $this->getResult();
		$result->addValue( null, 'pfautocomplete', [
			'getSettelmentsPerSettelment' => self::getSettelmentsPerSettelment($params['settelment']),
			'getZonePerSettelment' => self::getZonePerSettelment($params['settelment']),
			'settelment' => $params['settelment'],
			'getZonesPerSettelments' => self::getZonesPerSettelments(),
			] );
	}

	protected function getAllowedParams() {
		return array(
			'settelment' => null,
		);
	}

	protected function getParamDescription() {
		return array(
			'settelment' => 'Settelment in zone',
		);
	}

	protected function getDescription() {
		return 'Returns list of israeli settlements includes settelment string';
	}

	protected function getExamples() {
		return array(
			'action=israelsettlementsgetsettlementsperzone&settelment=יריחו',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}

	public static function arrayDiff($A, $B) {
	    $intersect = array_intersect($A, $B);
	    return array_merge(array_diff($A, $intersect), array_diff($B, $intersect));
	}
	public static function getAtt(  ) {
		$csvArray = IsraelSettlementsGet::csvFileToArray('../inc/center_to_setlements.csv');
		$allNames = [];
		$new_arr = [];
		foreach($csvArray as &$row){
			$key = array_shift($row);
			$vals = explode(',', $row[0]);
			foreach($vals as &$val) $val = trim($val);
			//$row = [];
			array_push($allNames, $key);
			//$allNames = array_merge($allNames, $vals);
			//$new_arr[$key] = $vals;
		}
		return array_values(array_unique($allNames));
	}
	public static function getAllSettelments(  ) {
		$csvArray = self::getAllData();
		$sets = array_unique(array_column($csvArray, self::$settelmentNameColumn));
		foreach($sets as &$set) $set = trim($set);
		return $sets;
	}
	public static function getSettelmentsPerZone(  ) {
		$csvArray = self::getAllData();
		$zones = [];
		foreach($csvArray as &$row){
			$zone = isset($row[self::$zoneNameColumn]) && $row[self::$zoneNameColumn] ? $row[self::$zoneNameColumn] : $row[self::$zoneNameColumn - 2];;
			if(!isset($zones[$zone])){
				$zones[$zone] = [];
			}
			$zones[$zone][] = $row[self::$settelmentNameColumn];
		} 
		
		return $zones;
	}
	public static function getZonesPerSettelments(  ) {
		$csvArray = self::getAllData();
		$settelments = [];
		foreach($csvArray as &$row){
			$zone = isset($row[self::$zoneNameColumn]) && $row[self::$zoneNameColumn] ? $row[self::$zoneNameColumn] : $row[self::$zoneNameColumn - 2];;
			$settelment = $row[self::$settelmentNameColumn];
			
			$settelments[$settelment] = $zone;
		} 
		
		return $settelments;
	}
	public static function getZonePerSettelment( $settelment ) {
		$zonesPerSettelments = self::getZonesPerSettelments();
		return isset($zonesPerSettelments[$settelment]) ? $zonesPerSettelments[$settelment] : '';
	}	
	public static function getSettelmentsPerSettelment( $settelment ) {
		$zone = self::getZonePerSettelment($settelment);
		$settelmentsPerZone = self::getSettelmentsPerZone();
		return $zone && isset($settelmentsPerZone[$zone]) ? $settelmentsPerZone[$zone] : '';
	}
	

}
