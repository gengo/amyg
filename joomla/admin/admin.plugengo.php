<?php
	
	include(dirname(__FILE__).'/myGengoClass.php');
	
	function myGengo_load()
	{
	  error_reporting(E_ALL & ~E_NOTICE);

		$document=& JFactory::getDocument();
        $document->setTitle('myGengo');


		myG::i()->setApplication('joomla');
		
		myG::i()->css();
		myG::i()->js();
		myG::i()->getLanguages();
		myG::i()->process();

		echo '<div class="wrap">';
		
		  echo "<div >";
			echo myG::i()->formBalance();
		  echo "</div>";
		  
		  echo "<div >";
			echo myG::i()->formJobs();
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
	myGengo_load();
	
	
	
	
?>