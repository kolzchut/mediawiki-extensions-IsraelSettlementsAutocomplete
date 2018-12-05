<?php


class IsraelSettlementsBase extends ApiBase {
	public function execute() {}
	public static function getAllData(  ) {
		return self::csvFileToArray('../inc/settlements.csv');
	}
	public static function csvFileToArray( $path ) {
		$base = dirname(__FILE__);
		$csvString = file_get_contents("$base/$path");
		$csvArray = str_getcsv($csvString, "\n"); //parse the rows 
		foreach($csvArray as &$row) $row = str_getcsv($row, ","); //parse the items in rows 
		return $csvArray;
	}
}