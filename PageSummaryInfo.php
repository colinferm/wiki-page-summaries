<?php
class PageSummaryInfo {
	public static function addSummaryInfo( &$module ) {
		MWDebug::log('SummaryAPIExtension Api Extension loaded...');
		if(!($module instanceof ApiParse)) return true;
	
		$result = $module->getResult();
		$data = $result->serializeForApiResult();

		//print_r($data);
		
		//wfDebugLog('addsummaryInfo', 'SummaryAPIExtension Data reads: '.$data['parse']['text']['*']);
		if (!isset($data['parse']['text'])) return true;
		
		$body = $data['parse']['text'];
		$matches = Array();
		//$body = preg_replace('/\r|\n/', ' ', $body);
		$c = preg_match_all('/<summary>(.+?)<\/summary>/is', $body, $matches);
		if ($c == 0) {
			$c = preg_match_all('/<p>(.+?)<\/p>/is', $body, $matches);
		}
		//$c = preg_match('/<p[^>]*>(.+?)<\/p>/is', $body, $matches);
		$description;
		MWDebug::log('Number of matches: '.$c);
		for ($i = 0; $i < count($matches[0]); $i++) {
			$description = trim(preg_replace('/\"/', '\'', strip_tags($matches[0][$i])));
			wfDebugLog('addsummaryInfo', 'Summary Description: '.$description);
			if (strlen($description)) break;
		}
		
		if (strlen($description)) {
			MWDebug::log("SummaryAPIExtension Description: $description");
			$status = $result->addValue(
				array('parse'),
				'summary',
				$description
			);
		}	
		return true;
	}
}