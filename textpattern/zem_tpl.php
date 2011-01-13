<?php

// Either copy classTextile.php to your plugin directory, or uncomment the following
// line and edit it to give the location where classTextile.php can be found
#ini_set('include_path', ini_get('include_path') . ':/full/path/to/textile');

if (empty($test)) {
	file_put_contents(dirname(__FILE__).'/plugin.txt', compile_plugin(dirname(__FILE__).'/myg_translations_plugin.php'));
}

// -----------------------------------------------------

function extract_section($lines, $section) {
	$result = "";
	
	$start_delim = "# --- BEGIN PLUGIN $section ---";
	$end_delim = "# --- END PLUGIN $section ---";

	$start = array_search($start_delim, $lines) + 1;
	$end = array_search($end_delim, $lines);

	$content = array_slice($lines, $start, $end-$start);

	$content = join("\n", $content);
	
	//embeded localizations texpattern
		
		$localizations = '';
		$dir = dirname(__FILE__).'/myGengoLanguages/';
		if ($dh = @opendir($dir)) 
		{
			while(($file = readdir($dh)) !== false)
			{
				if( $file != "." and $file != "..")  
				{
				  $localizations .= "\n".' $language[\''.str_replace('.dtd', '', $file).'\'] = \''.str_replace("'", "\'", file_get_contents($dir.$file)).'\';'."\n";
				}            
			}
		}

	$content = str_replace('//DO_NOT_REMOVE_HERE_MY_GENGO_CLASS', ' ?>'.trim(file_get_contents(dirname(__FILE__).'/myGengoClass.php')).'<?php ', $content);

	$content = str_replace('//__TEXTPATTERN_LOCALIZATION__', $localizations, $content);
	
	return $content;

}

function compile_plugin($file='') {
	global $plugin;

	if (empty($file))
		$file = $_SERVER['SCRIPT_FILENAME'];

	if (!isset($plugin['name'])) {
		$plugin['name'] = basename($file, '.php');
	}

	# Read the contents of this file, and strip line ends
	$content = file($file);
	for ($i=0; $i < count($content); $i++) {
		$content[$i] = rtrim($content[$i]);
	}

	//print_r($content);
	$plugin['help'] = extract_section($content, 'HELP');
	$plugin['code'] = extract_section($content, 'CODE');


	@include('classTextile.php');
	if (class_exists('Textile')) {
		$textile = new Textile();
		$plugin['help'] = $textile->TextileThis($plugin['help']);
	}

	$plugin['md5'] = md5( $plugin['code'] );



	return '# '.$plugin['name'].' v'.$plugin['version'].'
# '.$plugin['description'].'
# '.$plugin['author'].'
# '.$plugin['author_uri'].'

# ......................................................................
# This is a plugin for Textpattern - http://textpattern.com/
# To install: textpattern > admin > plugins
# Paste the following text into the \'Install plugin\' box:
# ......................................................................
' . "\n\n" . trim(chunk_split(base64_encode(serialize($plugin)), 72)). "\n";

}


?>
