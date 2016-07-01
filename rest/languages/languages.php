<?php
namespace Languages;

/**
* Example additional data to the output
*/
class Language {

	public static function current() {

		$language = wire('user')->language;

		$output['_language']['id']   = $language->id;
		$output['_language']['code'] = $language->name;
		$output['_language']['name'] = $language->title;

		return $output;
	}
}