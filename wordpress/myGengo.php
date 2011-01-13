<?php
/**
 * @package plugGengo
 * @version 1.0.6
 */
/*
Plugin Name: plugGengo
Plugin URI: http://mygengo.com/#
Description: Order translations from myGengo services directly from the admin interface. Allows you to request human translation of your website content such blog post, etc.
Author: Tito Bouzout
Version: 1.0.6
Author URI:http://titobouzout.github.com/amyg/
*/

include(dirname(__FILE__).'/myGengoClass.php');

//wordpress registration
add_action( 'admin_menu', 'myGengo_config_page' );

function myGengo_config_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('tools.php', __('myGengo'), __('myGengo'), 'manage_options', 'myGengo', 'myGengo_load');
}
//ends wordpress registration


	function myGengo_load()
	{
	  error_reporting(E_ALL & ~E_NOTICE);

		myG::i()->setApplication('wordpress');
		
		myG::i()->css();
		myG::i()->js();
		myG::i()->getLanguages();
		myG::i()->process();

		echo '<div class="wrap">';
		
		  echo "<div >";
			echo myG::i()->formBalance();
		  echo "</div>";
		  
		  echo "<div >";
			 myG::i()->formJobs();
		  echo "</div>";
		  
		  echo "<div >";
			echo myG::i()->formNewJob();
		  echo "</div>";
		  
		  echo "<div >";
			echo myG::i()->formAPI();
		  echo "</div>";
		  
			echo '
			  <div>
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
			  
			echo "<div>";
			  echo myG::i()->formChangeLanguage();
			echo "</div>";
			
			  echo '
			</div>';
		
		myG::i()->jsEnd();
	}
	

?>