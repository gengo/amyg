<?php

class NP_mygengo extends NucleusPlugin {

	function getName() 		  { return 'plugGengo'; }
	function getAuthor()  	  { return 'Tito Bouzout'; }
	function getURL()  		  { return 'http://titobouzout.github.com/amyg/'; }
	function getVersion() 	  { return '1.0.6'; }
	function getDescription() { return 'Order translations from myGengo services directly from the admin interface. Allows you to request human translation of your website content such blog post, etc.';	}

	function supportsFeature($what) {
	  return 1;
	}

	function install() {
	}
	
	function unInstall() {
	}

	function getEventList() {
		return array('QuickMenu');
	}
	
	function hasAdminArea() {
		return 1;
	}

	function init()
	{
	}
	
	function event_QuickMenu(&$data) {
		global $member;

		// only show to logged users
		if (!($member->isLoggedIn())) return;

		array_push(
			$data['options'], 
			array(
				'title' => 'myGengo',
				'url' => $this->getAdminURL(),
				'tooltip' => 'Order translations from myGengo services directly from the admin interface. Allows you to request human translation of your website content such blog post, etc.'
			)
		);
	}
}

?>