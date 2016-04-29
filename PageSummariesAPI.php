<?php

$wgExtensionCredits['other'][] = array(
	'name' => 'API Parser Summary',
	'author' =>'Colin Andrew Ferm',  
	'description' => 'Extends the "parse" function of the API to include a "summary" field which is taken from the first paragraph of text on the page.',
	'version' => '1.0',
	'path' => __FILE__
);

$wgExtensionCredits['other'][] = array(
	'name' => 'API Pages Summaries',
	'author' =>'Colin Andrew Ferm',  
	'description' => 'Addes the function "summaries" to the API with the paramter "pages" to take multiple page names separated by a "|" and return their summaries.',
	'version' => '1.0',
	'path' => __FILE__
);

$wgHooks['APIAfterExecute'][] = 'addSummaryInfo';
$wgAPIModules['summaries'] = "PageSummariesAPI";

class PageSummariesAPI extends ApiBase {

	public function __construct( $main, $action ) {
		parent :: __construct( $main, $action );
	}
	
	public function execute() {
		$params = $this->extractRequestParams();
		$pages = $params['pages'];
		
		if (is_null($pages))
			$this->dieUsage('No pages were passed.', 'params');
			
		$pagesList = explode('|', $pages);
		
		$summaries = array();
		
		if (count($pagesList)) {
			for ($i = 0; $i < count($pagesList); $i++) {
				$page = $pagesList[$i];
				
				$req = new FauxRequest( array(
						'action' => 'parse',
						'page' => $page
						));
						
				$main = new ApiMain($req);
				$main->execute();
				$data = $main->getResultData();
				
				$tmp = array(
					'title' => $data['parse']['displaytitle'],
					'summary' => $data['parse']['text']['summary'],
					'link' => 'http://'.$_SERVER['SERVER_NAME'].'/reference/'.$page
				);
				
				array_push($summaries, $tmp);
			}
		}
		
		$result = $this->getResult();
		$tags = array('summaries');

                $result->setIndexedTagName_internal( $tags, 'article' );
		$result->addValue(null, $this->getModuleName(), $summaries);
	}
	
	public function getAllowedParams() {
		return array('pages' => null);
	}
	
	public function getParamDescription() {
		return array('pages' => 'The pages, pipe separated to be parsed and returned.');
	}
	
	public function getDescription() {
		return 'This module parses multiple pages and returns their summaries.';
	}
	
	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), 
			array( 
				'code' => 'params', 
				'info' => 'The pages parameter is formatted incorrectly.'
			)
		);
	}
	
	protected function getExamples() {
		return array (
			'api.php?action=pagesummaries&pages=Main_Page|Category:Example'
		);
	}
	
	public function getVersion() {
		return __CLASS__ . ': Version 1.0';
	}
}

function addSummaryInfo( &$module ) {
	//wfDebugLog('addsummaryInfo', 'SummaryAPIExtension Api Extension loaded...');
	if(!($module instanceof ApiParse)) return true;

	$result = $module->getResult();
	$data = $result->getData();
	
	if (!isset($data['parse']['text']['*'])) return true;
	
	$body = $data['parse']['text']['*'];
	$matches = Array();
	$c = preg_match_all('/<p>(.+?)<\/p>/is', $body, $matches);
	$description;
	//wfDebugLog('addsummaryInfo', 'Number of matches: '.$c);
	for ($i = 0; $i < count($matches[0]); $i++) {
		$description = trim(preg_replace('/\"/', '\'', strip_tags($matches[0][$i])));
		if (strlen($description)) break;
	}
	
	if (strlen($description)) {
		$status = $result->addValue(
					array('parse', 'text'),
					'summary',
					$description
		);
	}	
	
	return true;
}
?>
