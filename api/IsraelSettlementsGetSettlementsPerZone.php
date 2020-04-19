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
	static $settlementNameColumn;
	static $zoneNameColumn;
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
		self::init();
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$result = $this->getResult();
		$result->addValue( null, 'settlementsPerZone', self::getSettlementsPerSettlementOrZone($params['settlement']));
	}

	protected function getAllowedParams() {
		return array(
			'settlement' => null,
		);
	}

	protected function getParamDescription() {
		return array(
			'settlement' => 'Settlement in zone',
		);
	}

	protected function getDescription() {
		return 'Returns list of israeli settlements includes settlement string';
	}

	protected function getExamples() {
		return array(
			'action=israelsettlementsgetsettlementsperzone&settlement=יריחו',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}

	public static function arrayDiff($A, $B) {
	    $intersect = array_intersect($A, $B);
	    return array_merge(array_diff($A, $intersect), array_diff($B, $intersect));
	}
	public static function init() {
	    self::$settlementNameColumn = 1;
		self::$zoneNameColumn = 8;
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
	public static function getAllSettlements(  ) {
		$csvArray = self::getAllData();
		$sets = array_unique(array_column((string)$csvArray, self::$settlementNameColumn));
		foreach($sets as &$set) $set = trim($set);
		return $sets;
	}
	public static function getSettlementsPerZone(  ) {
		$csvArray = self::getAllData();
		$zones = [];
		foreach($csvArray as &$row){
			$zone = isset($row[self::$zoneNameColumn]) && $row[self::$zoneNameColumn] ? $row[self::$zoneNameColumn] : $row[self::$zoneNameColumn - 2];;
			if(!isset($zones[$zone])){
				$zones[$zone] = [];
			}
			$zones[$zone][] = $row[self::$settlementNameColumn];
		} 
		
		return $zones;
	}
	public static function getZonesPerSettlements(  ) {
		$csvArray = self::getAllData();
		$settlements = [];
		foreach($csvArray as &$row){
			$zone = isset($row[self::$zoneNameColumn]) && $row[self::$zoneNameColumn] ? $row[self::$zoneNameColumn] : $row[self::$zoneNameColumn - 2];;
			$settlement = $row[self::$settlementNameColumn];
			
			$settlements[$settlement] = $zone;// . implode('_', [$row[self::$zoneNameColumn],$row[self::$zoneNameColumn-2]]);
		} 
		
		return $settlements;
	}
	public static function getZonePerSettlement( $settlement ) {
		$zonesPerSettlements = self::getZonesPerSettlements();
		return isset($zonesPerSettlements[$settlement]) ? $zonesPerSettlements[$settlement] : '';
	}	
	public static function getSettlementsPerSettlementOrZone( $settlement ) {
		$zone = self::getZonePerSettlement($settlement);
		//could send also zone name
		$zone = $zone ? $zone : trim(preg_replace('/מועצה אזורית/','',$settlement));
		$settlementsPerZone = self::getSettlementsPerZone();
		//die( print_r([$zone,$settlement,$settlementsPerZone[$zone]]));
		return $zone && isset($settlementsPerZone[$zone]) ? $settlementsPerZone[$zone] : '';
	}
	

}
