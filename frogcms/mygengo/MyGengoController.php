<?php

/**
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This file is part of Frog CMS.
 *
 * Frog CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Frog CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Frog CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * The myGengo plugin allows users to order translation services from myGengo website.
 *
 * @package mygengo
 *
 * @author Tito Bouzout <http://titobouzout.github.com/amyg/>
 * @version 1.0.6
 * @since Frog version 0.9.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 */
	
	include(dirname(__FILE__).'/myGengoClass.php');
	
	class MyGengoController extends PluginController
	{
		public static function _checkPermission()
		{
			AuthUser::load();
			if ( ! AuthUser::isLoggedIn())
			{
				redirect(get_url('login'));
			}
		}
		public function __construct()
		{
			$this->setLayout('backend');
			$this->assignToLayout('sidebar', new View('../../plugins/mygengo/views/sidebar'));
		}
		public function index()
		{
		  $this->_checkPermission();
		  $this->display('mygengo/views/index');
		}
	
	}

	function myGengo_load()
	{
	  error_reporting(E_ALL & ~E_NOTICE);

		myG::i()->setApplication('frog');
		
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
	

	
	

?>