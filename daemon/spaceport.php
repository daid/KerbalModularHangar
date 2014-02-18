<?php
require("util/fetch.php");
require("util/simple_html_dom.php");

class KerbalSpaceport
{
	static function listModUrls()
	{
		$pageNr = 1;
		$ret = array();
		while(true)
		{
			//echo $pageNr."\n";
			//if ($pageNr > 2) break;
			$page = fetchUrl("http://kerbalspaceport.com/?paged=".$pageNr."&s=+&orderby=title");
			$page = str_get_html($page);
			$items = $page->find('.search_item');
			if (count($items) < 1)
				break;
			foreach($items as $item)
			{
				$ret[] = "http://kerbalspaceport.com/?p=" . $item->id;
			}
			$pageNr += 1;
		}
		return $ret;
	}
}

$modUrls = KerbalSpaceport::listModUrls();
foreach($modUrls as $url)
	echo $url."\n";

?>