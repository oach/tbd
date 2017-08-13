<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Seo
{
	private $_ci;

	public function __construct()
    {
		$this->_ci =& get_instance();
	}

	public function getSEO($len = 2)
    {
		$str_uri = '';
		if ($this->_ci->uri->total_segments() == 0) {
			$str_uri = 'page/index/';
		} else {		
			$parts = explode('/', $this->_ci->uri->uri_string());
			
			if (is_array($parts)) {
                $cnt = 0;
				foreach ($parts as $uri) {
					if ($cnt < $len && !empty($uri)) {
						$str_uri .= $uri . '/';
						$cnt++;
					}
				}
			}
		}
		
        $this->_ci->load->model('SEOModel', '', true);
		return $this->_ci->SEOModel->getSEOInfo($str_uri);
	}

	public function getDynamicSEO($obj, $type)
    {
        $seo = new StdClass();
        $seo->pageTitle = '';
        $seo->metaDescription = '';
        $seo->metaKeywords = '';
        $seo->metaKey_beer = 'beer reviews, beer ratings, brewery, brewery hops';
        
        if ('beer review' == $type) {
            $seo->pageTitle = $obj->beerName . ' - ' . $obj->name . ' - Two Beer Dudes';
            $seo->metaDescription = $obj->beerName . ', ' . $obj->style . ' from ' . $obj->name . ' in ' . $obj->city . ', ' . $obj->stateFull . ', beer reviews and ratings for ' . $obj->beerName . ' on Two Beer Dudes';
            $seo->metaKeywords = $obj->beerName . ', ' . $obj->style . ', ' . $obj->name . ', ' . $obj->city . ', ' . $obj->stateFull . ', ' . $seo->metaKey_beer;
        } elseif ('beer style' == $type) {
            $seo->pageTitle = $obj->style . ' - ' . $obj->origin . ' ' . $obj->styleType . 's - Two Beer Dudes';
            $seo->metaDescription = $obj->style . ' a ' . $obj->origin . ' ' . $obj->styleType . 's, beer reviews and ratings for ' . $obj->style;
            $seo->metaKeywords = $obj->style . ', ' . $obj->origin . ' ' . $obj->styleType . ', ' . $seo->metaKey_beer;
        } elseif ('establishment city' == $type) {
            $seo->pageTitle = 'Breweries in ' . $obj->city . ', ' . $obj->stateFull . ' - Two Beer Dudes';
            $seo->metaDescription = 'Two Beer Dudes breweries in ' . $obj->city . ', ' . $obj->stateFull . ', beer reviews and ratings in ' . $obj->city . ', ' . $obj->stateFull;
            $seo->metaKeywords = 'breweries in ' . $obj->city . ', ' . $obj->stateFull . ', ' . $seo->metaKey_beer;
        } elseif ('estabishment state' == $type) {
            $seo->pageTitle = 'Breweries, beer bars, brew pubs, and beer stores in ' . $obj->stateFull . '  - Two Beer Dudes';
            $seo->metaDescription = 'Two Beer Dudes breweries, beer bars, brew pubs, and beer stores in ' . $obj->stateFull . ', beer reviews and ratings in ' . $obj->stateFull;
            $seo->metaKeywords = 'breweries, beer bars, brew pubs, and beer stores in ' . $obj->stateFull . ', ' . $seo->metaKey_beer;
        } elseif ('update profile' == $type) {
            $seo->pageTitle = 'User Account for ' . $obj->username . ' - Two Beer Dudes';
            $seo->metaDescription = 'Two Beer Dudes beer reviews and ratings for members like ' . $obj->username;
            $seo->metaKeywords = $obj->username . ' account, ' . $seo->metaKey_beer;
        } elseif ('update profile password' == $type) {
            $seo->pageTitle = 'Update password for User Account ' . $obj->username . ' - Two Beer Dudes';
            $seo->metaDescription = 'Two Beer Dudes beer reviews and ratings for members like ' . $obj->username;
            $seo->metaKeywords = $obj->username . ' account password update, ' . $seo->metaKey_beer;
        } elseif ('private messages' == $type) {
            $seo->pageTitle = 'Private message for ' . $obj->username . ' - Two Beer Dudes';
            $seo->metaDescription = 'Two Beer Dudes beer private messages for ' . $obj->username;
            $seo->metaKeywords = $obj->username . ' private messages, ' . $seo->metaKey_beer;
        }
        /*elseif (key_exists('seoType', $config) && $config['seoType'] == 'reviewEstablishment')
        {
            $pageTitle .= 'Create an establishment review of ' . $config['breweryName'] . ' in ' . $config['breweryCity'] . ', ' . $config['breweryState'] . ' - Two Beer Dudes';
            $metaDesc = 'Two Beer Dudes establishment review for ' . $config['breweryName'] . ' in ' . $config['breweryCity'] . ', ' . $config['breweryState'];
            $metaKey = $config['breweryName'] . ' in ' . $config['breweryCity'] . ', ' . $config['breweryState'] . ' establishment review, ' . $metaKey_beer;
        }
        elseif (key_exists('seoType', $config) && $config['seoType'] == 'eventPics')
        {
            $pageTitle .= $config['eventName'] . ' on ' . $config['eventDate'] . ' - Two Beer Dudes';
            $metaDesc = 'Two Beer Dudes visit to ' . $config['eventName'] . ' in ' . $config['city'] . ', ' . $config['state'];
            $metaKey = $config['eventName'] . ', ' . $config['city'] . ', ' . $config['state'] . $metaKey_beer;
        }
        elseif (array_key_exists('forum_sub_topic', $config))
        {
            $pageTitle = $config['forum_sub_topic'] . ' Forum - Two Beer Dudes';
            $metaDesc = $config['forum_sub_topic'] . ' - ' . $config['description'];
            $metaKey = 'beer, news, beer news, American craft beer industry';
        }
        elseif (array_key_exists('forum_thread', $config))
        {
            $pageTitle = $config['forum_thread'] . ' - Two Beer Dudes';
            $metaDesc = $config['forum_thread'] . ' in ' . $config['sub_topic_name'] . ' Forum';
            $metaKey = $config['forum_thread'] . ', beer, American craft beer, homebrew, beer forum, home brewing';
        }
        elseif (key_exists('seoType', $config) && $config['seoType'] == 'user_beer')
        {
            $pageTitle = 'American Craft Beer Reviews by ' . $config['user_name'] . ' - Two Beer Dudes';
            $metaDesc = 'American craft beer reviews and ratings for ' . $config['user_name'];
            $metaKey = 'beer, news, beer news, American craft beer industry';
        }*/
        elseif ('establishment rating' == $type) {
            $seo->pageTitle = $obj->name . ' - Two Beer Dudes';
            $seo->metaDescription = $obj->name . ' in ' . $obj->city . ', ' . $obj->stateFull . ', beer reviews and ratings ' . $obj->name;
            $seo->metaKeywords = $obj->name . ', ' . $obj->city . ', ' . $obj->stateFull . ', ' . $seo->metaKey_beer;
        } elseif ('establishment category' == $type) {
            $seo->pageTitle = $obj->category . ' in ' . $obj->stateFull . ' - Two Beer Dudes';
            $seo->metaDescription = $obj->category . ' in ' . $obj->stateFull . ', beer reviews and ratings for ' . $obj->category . ' in ' . $obj->stateFull;
            $seo->metaKeywords = $obj->category . ', ' . $obj->stateFull . ', ' . $seo->metaKey_beer;
        }

        return $seo;
    }
}
?>