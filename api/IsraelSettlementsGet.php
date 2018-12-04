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
		$data2 = self::getAtt();
		// $data_filtered = [];
		// //echo $params['term'] . '</br>';
		// foreach ($data as $part) {
		// 	if(strpos($part, $params['term']) !== FALSE){
		// 		$data_filtered[] = ['title' => $part];
		// 	}

		// }

		//die();
		// Set top-level elements.
		$result = $this->getResult();
		$all = self::arrayDiff($data2, $data);
		sort($data);
		sort($data2);
		sort($all);
		//die(print_r($params));
		$result->setIndexedTagName( $params, 'p' );
		$result->addValue( null, 'pfautocomplete', [
			'in_old_but_not_in_new' => array_diff($data, $data2),
			'in_new_but_not_in_old' => array_diff($data2, $data),
			'in_new_but_not_in_oboold' => $all,
			'new' => $data2,
			'old' => $data,
			] );
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
			//array_push($allNames, $key);
			$allNames = array_merge($allNames, $vals);
			//$new_arr[$key] = $vals;
		}
		return array_values(array_unique($allNames));
	}
	public static function getAllValues(  ) {
		$csvArray = IsraelSettlementsGet::csvFileToArray('../inc/settlements.csv');
		// $base = dirname(__FILE__);
		// $csvString = file_get_contents("$base/../inc/settlements.csv");
		// $csvArray = str_getcsv($csvString, "\n"); //parse the rows 
		// foreach($csvArray as &$row) $row = str_getcsv($row, ","); //parse the items in rows 
		$sets = array_column($csvArray, '2');
		foreach($sets as &$set) $set = trim($set);
		return $sets;
	}
	public static function csvFileToArray( $path ) {
		$base = dirname(__FILE__);
		$csvString = file_get_contents("$base/$path");
		$csvArray = str_getcsv($csvString, "\n"); //parse the rows 
		foreach($csvArray as &$row) $row = str_getcsv($row, ","); //parse the items in rows 
		return $csvArray;
	}

}
