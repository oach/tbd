<?php
if (!defined('BASEPATH')) {
	exit ('No direct script access allowed');
}

class WordpressModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}

	public function getRss() {
		$xml = @simplexml_load_file(BLOG_RSS_URL, 'SimpleXMLElement', LIBXML_NOCDATA);
		if (!$xml) {
			throw new Exception('<p>Blog RSS currently down.</p>');
		}
		
		$rss = new stdClass;
		$rss->title = $xml->xpath('/rss/channel/item/title');
		$rss->link = $xml->xpath('/rss/channel/item/link');
		$rss->pubdate = $xml->xpath('/rss/channel/item/pubDate');
		$rss->creator = $xml->xpath('/rss/channel/item/dc:creator');
		$rss->description = $xml->xpath('/rss/channel/item/description');

	    return $rss;
	}
}