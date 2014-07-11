<?php
	function url_get_contents ($Url) {
		if (!function_exists('curl_init')){ 
			die('CURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	// alternative to file_get_contents because it's usually disabled


	$url =  'http://honolulu.craigslist.org/search/?areaID=28&subAreaID=&catAbb=sss&query=' . urlencode($_GET['query']);
	$html = url_get_contents($url);

	// Get title for each row
	$items = array();
	preg_match_all("'<p class=\"row\">(.*?)</p>'si", $html, $rows);

	foreach($rows[1] as $row)
    {
		preg_match("'(.*?)</span>(.*?) -'si", $row, $match);
        $date = trim($match[2]);

		preg_match("'<a href=\"(.*?)\">(.*?)</a>'si", $row, $match);
		$link = trim($match[1]);
		$title = trim($match[2]);
		$title = preg_replace('/ -$/','',$title);

		preg_match("'(.*?)</a>(.*?)<'si", $row, $match);
		$price = '';
		if ($match)
			$price = trim($match[2]);
		
		preg_match("'<font size=\"-1\">(.*?)</font>'si", $row, $match);
		$location = '';
		if ($match) {
			$location = trim($match[1]);
			$location= preg_replace('/[\(\)]/','',$location);
		}

/*		preg_match("'<span class=\"p\">(.*?)</span>'si", $html, $rows);
		$pics = trim($match[1]);
*/		
		array_push($items, array('date' => $date, 'link' => $link, 'title' => $title,'price' => $price,'location' => $location));

    }
	echo '{"items":' . json_encode($items) . '}' ;

?>
