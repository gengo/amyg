<?php

	ob_start();
	function progress($aString){ echo $aString;flush();ob_flush();}
	progress('<b>Compiler starting work...</b><br/><br/>');
	/***
	
	  This file is an ant script that updates changes made to "master files"(localization and myGengoClass) of this directory
	
	***/
	
	$root = dirname(__FILE__).'/';
	
	/***
	 
	 1 - copying new/updated localizations from z_localizations to each plug-in:
	 
	***/
	
	  progress( 'Copying localization...<br/>');
	  $localizations_directory = $root.'z_template_localizations/';
	  if ($dh = opendir($localizations_directory)) 
	  {
		//foreach localization
		while(($file = readdir($dh)) !== false)
		{
		  if( $file != "." and $file != "..")  
		  {
			//if is a dtd file
			if(preg_match('~dtd$~', $localizations_directory.$file))
			{
			  //delete old localization
			  @unlink($root.'/frogcms/mygengo/myGengoLanguages/'.$file);
			  @unlink($root.'/joomla/admin/myGengoLanguages/'.$file);
			  @unlink($root.'/nucleuscms/mygengo/myGengoLanguages/'.$file);
			  @unlink($root.'/textpattern/myGengoLanguages/'.$file);
			  @unlink($root.'/wordpress/myGengoLanguages/'.$file);
			  //copy new localization
			  copy($localizations_directory.$file, $root.'/frogcms/mygengo/myGengoLanguages/'.$file);
			  copy($localizations_directory.$file, $root.'/joomla/admin/myGengoLanguages/'.$file);
			  copy($localizations_directory.$file, $root.'/nucleuscms/mygengo/myGengoLanguages/'.$file);
			  copy($localizations_directory.$file, $root.'/textpattern/myGengoLanguages/'.$file);
			  copy($localizations_directory.$file, $root.'/wordpress/myGengoLanguages/'.$file);
			}
		  }            
		}
	  }
	  progress( 'Copying localization done!<br/><br/>');
	  
	/***
	 
	 2 - copying new/updated /myGengoClass.php to each plug-in:
	 
	***/
	
	
		progress( 'Copying new class myGengoClass.php...<br/>');
		@unlink($root.'/frogcms/mygengo/myGengoClass.php');
		@unlink($root.'/joomla/admin/myGengoClass.php');
		@unlink($root.'/nucleuscms/mygengo/myGengoClass.php');
		@unlink($root.'/textpattern/myGengoClass.php');
		@unlink($root.'/wordpress/myGengoClass.php');
	  	
		copy($root.'/z_template_class/myGengoClass.php', $root.'/frogcms/mygengo/myGengoClass.php');
		copy($root.'/z_template_class/myGengoClass.php', $root.'/joomla/admin/myGengoClass.php');
		copy($root.'/z_template_class/myGengoClass.php', $root.'/nucleuscms/mygengo/myGengoClass.php');
		copy($root.'/z_template_class/myGengoClass.php', $root.'/textpattern/myGengoClass.php');
		copy($root.'/z_template_class/myGengoClass.php', $root.'/wordpress/myGengoClass.php');
		progress( 'Copying new class myGengoClass.php done!<br/><br/>');
		
		
	/***
	 
	 3 - recompiling textpattern plug-in
	 
	***/
	  progress( 'Recompiling textpattern...<br/>');
	  include($root.'/textpattern/myg_translations_plugin.php');
	  progress( 'Recompiling textpattern done!<br/><br/>');
	  
	/***
	 
	 4 - Trying to zip all the plugins and put the zips into z_distribution ( requires ZipArchive PHP extension )
	 
	***/
	
	if(!class_exists('ZipArchive'))
	{
	  progress( 'Ups ZipArchive class is not defined into your PHP instalation, try install that PHP extension or compile the plug-ins manually.<br/>');
	}
	else
	{
		progress( 'Packing plug-ins for distribution ...<br/>');
		createZip($root.'/frogcms/', 			$root.'/z_distribution/frogcms.zip');
		createZip($root.'/joomla/', 			$root.'/z_distribution/joomla.zip');
		createZip($root.'/nucleuscms/', 		$root.'/z_distribution/nucleuscms.zip');
		//textpattern is a txt file
		@unlink($root.'/z_distribution/textpattern.txt');
		copy($root.'/textpattern/plugin.txt', 	$root.'/z_distribution/textpattern.txt');
		createZip($root.'/wordpress/', 			$root.'/z_distribution/wordpress.zip');
		progress( 'Packing plug-ins for distribution done!<br/><br/>');
	}
	
	// Recursive function to list all files on a directory
	function list_directory($dir, &$list)
	{
		if ($dh = @opendir($dir)) 
		{
			while(($file = readdir($dh)) !== false)
			{
				if( $file != "." and $file != "..")  
				{
					if(!is_dir($dir.$file))
					{
						$list[] = str_replace("\\", "/", $dir.$file);
					}
					if(is_dir($dir.$file)) 
					{
						$newdir = $dir.$file."/";
						chdir($newdir);
						list_directory($newdir, $list);
					} 
				}            
			}
			chdir("..");
		}
		return $list;
	}
	//zips a $folder into a $file
	function createZip($dir, $file)
	{
		//creating zip
		@unlink($file);
		$zip = new ZipArchive();
		if ($zip->open($file, ZIPARCHIVE::CREATE)!==TRUE)
			die("cannot open <".$file.">");
		//adding files
		$a = array();
		$list = list_directory($dir,$a);
		for($a=0;$a<count($list);$a++)
			$zip->addFile($list[$a], str_replace(str_replace("\\", "/", $dir), "", str_replace("\\", "/", $list[$a])));
		//closing zip
		$zip->close();
		sleep(2);//on MAC OS takes time to close the zip file
	}
	
	progress('<b>Compiler finished work!</b>');
?>