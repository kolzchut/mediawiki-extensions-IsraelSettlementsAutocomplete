<?php


class IsraelSettlementsBase extends ApiBase {
	public function execute() {}
	public static function getPopulationData(  ) {
		$pepoleData = self::csvFileToArray('../inc/settlements_population.csv');
		$pepolePerCityID = [];
		foreach ($pepoleData as $row){
			$pepolePerCityID[$row[0]] = [
				'id' => $row[0],
				'name' => $row[1],
				'pepole_number' => $row[2],
			];
		}
		return $pepolePerCityID;
	}
	public static function getAllData(  ) {
		$baseData = self::csvFileToArray('../inc/settlements.csv');
		$pepoleData = self::getPopulationData();
		foreach ($baseData as  &$dataPart) {
			if( isset( $pepoleData[ $dataPart[ 0 ] ] ) ){
				array_push( $dataPart, $pepoleData[ $dataPart[ 0 ] ]['pepole_number'] );
			}
		}
		return $baseData;
	}
	public static function csvFileToArray( $path ) {
		$base = dirname(__FILE__);
		$csvString = file_get_contents("$base/$path");
		$csvArray = str_getcsv($csvString, "\n"); //parse the rows 
		foreach($csvArray as &$row) $row = str_getcsv($row, ","); //parse the items in rows 
		return $csvArray;
	}
}