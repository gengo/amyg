<?php

/**
 * The plugGengo plugin allows users to order translation services from myGengo website.
 *
 * @package plugGengo
 *
 * @author Tito Bouzout <http://titobouzout.github.com/amyg/>
 * @version 1.0.6
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 */

  include(dirname(__FILE__).'/myGengoClass.php');
  
  if(
	 $_POST['mygengo_action'] != 'print_job' &&
	 $_POST['mygengo_action'] != 'print_job_comments' &&
	 $_POST['mygengo_action'] != 'get_cost'
	 
	)
  {
	$strRel = '../../../'; 
	require($strRel . 'config.php');
	include_libs('PLUGINADMIN.php');
  }

	/**
	  * Create admin area
	*/

    if(
	 $_POST['mygengo_action'] != 'print_job' &&
	 $_POST['mygengo_action'] != 'print_job_comments' &&
	 $_POST['mygengo_action'] != 'get_cost'
	 
	)
	$oPluginAdmin  = new PluginAdmin('mygengo');

	function myGengo_load()
	{
	  error_reporting(E_ALL & ~E_NOTICE);

		myG::i()->setApplication('nucleus');
		
		myG::i()->css();
		myG::i()->js();
		myG::i()->getLanguages();

		myG::i()->process();
		
		echo '<div class="wrap">';
		
		  echo "<div>";
			echo myG::i()->formBalance();
		  echo "</div>";
		  
		  echo "<div>";
			echo myG::i()->formJobs();
		  echo "</div>";
		  
		  echo "<div>";
			echo myG::i()->formNewJob();
		  echo "</div>";
		  
		  echo "<div>";
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
	
	  if(
	 $_POST['mygengo_action'] != 'print_job' &&
	 $_POST['mygengo_action'] != 'print_job_comments' &&
	 $_POST['mygengo_action'] != 'get_cost'
	 
	)
	  $oPluginAdmin->start('');
	  
	myGengo_load();
	
	  if(
	 $_POST['mygengo_action'] != 'print_job' &&
	 $_POST['mygengo_action'] != 'print_job_comments' &&
	 $_POST['mygengo_action'] != 'get_cost'
	 
	)
	  $oPluginAdmin->end('');

	
	
	
?>
