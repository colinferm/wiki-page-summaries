<?php
class PageSummaryHooks {
	public static function onParserSetup( Parser $parser ) {
		$parser->setHook('summary', 'PageSummaryHooks::renderSummaryTags');
	}

	public static function renderSummaryTags($input, array $args, Parser $parser, PPFrame $frame) {
		MWDebug::log('Stripping out summary tag');
		MWDebug::log($args);
		$format = true;
		$output = "";
		if (array_key_exists("show", $args) && $args["show"] != "false") {
			$output = $input;
		} else {
			$format = false;
		}
		$output .= "<!-- <summary>".$input."</summary> -->";
		return array($output, 'noparse' => !$format, 'isHTML' => $format);
		//return array($input);
	}
}

?>