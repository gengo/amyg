<?php

# LGPL

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Uncomment and edit this line to override:
# $plugin['name'] = 'abc_plugin';
$plugin['version'] = '1.0.6';
$plugin['author'] = 'Tito Bouzout';
$plugin['author_uri'] = 'http://titobouzout.github.com/amyg/';
$plugin['description'] = 'Order translations from myGengo services directly from the admin interface. Allows you to request human translation of your website content such blog post, etc.';
$plugin['type'] = 1; // 0 for regular plugin; 1 if it includes admin-side code

@include_once(dirname(__FILE__).'/zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---

h1. plugGengo Textpattern plugin

Under the content area a new tab "Translations" will appear
that allows order translations from myGengo services.

You should regiter an account into myGengo website 
to get API keys in order to use this plugin.

# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---

	// Add a new tab to the Content area.
	// "test" is the name of the associated event; "testing" is the displayed title
	if (@txpinterface == 'admin') {
		$myevent = 'myGengo';
		
		error_reporting(E_ALL & ~E_NOTICE);
		 
		myG::i()->setApplication('textpattern');
		
		$mytab = myG::i()->s('translations');

		// Set the privilege levels for our new event
		add_privs($myevent, '1,2');

		// Add a new tab under 'extensions' associated with our event
		//register_tab("extensions", $myevent, $mytab);
		register_tab("content", $myevent, $mytab);

		// 'zem_admin_test' will be called to handle the new event
		register_callback("myg_admin_tab", $myevent);
	}

	function myg_admin_tab($event, $step)
	{
		pagetop("plugGengo", myG::i()->s('world.speaking.your.language'));
		
		myG::i()->css();
		myG::i()->js();
		myG::i()->getLanguages();
		myG::i()->process();

		echo "<div  style=\"width:80%;margin:0 auto;\">";
		  echo myG::i()->formBalance();
		echo "</div>";
		
		echo "<div  style=\"width:80%;margin:0 auto;\">";
		   myG::i()->formJobs();
		echo "</div>";
		
		echo "<div  style=\"width:80%;margin:0 auto;\">";
		  echo myG::i()->formNewJob();
		echo "</div>";
		
		echo "<div  style=\"width:80%;margin:0 auto;\">";
		  echo myG::i()->formAPI();
		echo "</div>";
		
		echo '
			<div style="padding:10px;width:80%;margin:0 auto;">
			
				<br/><br/><br/>
				<a href="http://mygengo.com/">
				  <img src="http://titobouzout.github.com/amyg/powered.png" border="0"/>
				</a>
				<br/>
				<em>
				'.myG::i()->s('footer.one').'
				
				</em><br/>
				<em>
				'.myG::i()->s('footer.two').'
				  
				  
				</em>
				
			</div>
			';
			echo "<div style='padding:10px;width:80%;margin:0 auto;'>";
			  echo myG::i()->formChangeLanguage();
			echo "</div>";
			
		myG::i()->jsEnd();
	}
	
	//DO_NOT_REMOVE_HERE_MY_GENGO_CLASS
	
	
# --- END PLUGIN CODE ---

?>
