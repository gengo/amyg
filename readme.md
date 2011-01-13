This is a set of plug-ins that allows you to use myGengo services (human-translation) directly from the admin interface of Textpattern, Frog CMS, WordPress, Joomla!, and NucleusCMS.

For users
=============

Please see the <a href="http://titobouzout.github.com/amyg/">home-page</a> to get installation instructions.

For developers:
=============

* Welcome! If you have any question or something please ask via issue tracker or email.

* These plug-ins share the "localization files" and a "PHP Class" which is identically to all the plug-ins.

* To make distribution of changes to that files easy, I created a file called "compiler.php" which when executed will distribute the changes ( the PHP class and the localizations ) to all the plugins here.
  
  * The template "PHP class" that is copied to all the plugins when executing "compiler.php" is located into /z_template_class/myGengoClass.php
  
  * The template "localizations files" that is copied to all the plugins when executing "compiler.php" is located into /z_template_localizations/*

	  * <b>NOTE:</b> You will notice that these files(templates) are present(duplicated) into each individual plug-in folder. But don't edit these, edit the "master/template" files, because the compiler will overwrite any change made.

* Basically the important information(code) into each plug-in folder is the "plug-in CMS registration" which allows you to find "the translate/myGengo" menu-item on a menu of a CMS. When you click that menu the execution of the class starts which loads all the interface.

o I decided to share most of the code even if it is not "correct" behavior because this really makes things simple to maintain.

A example of a complete flow of changes:
------------------------

Imagine this situation:

 - some change to localizations
 - some change to the class
 - some change to the plug-ins

Flow to make the changes:

* Download this repository to a folder in a server.
* Make any change needed to the class /z_template_class/myGengoClass.php
* Make any change needed to the localizations  /z_template_localizations/ ( such: en.dtd )
* Make any change needed to the "CMS Registration"  ( such : /wordpress/ )
  * <b>Be sure</b> to make the same change to the other locales.
* execute "compiler.php"
* find your created new version into the folder /z_distribution/ (example: /z_distribution/wordpress.zip)
  * <b>be sure</b> you don't break any other CMS with the modifications of the class.
* test your build into the CMS to make sure all works as excepted.
* do these steps as many times you need.
* Done?
 - Repeat the test into the other CMS.
* Search for the last version number marked on this repository and bump one number in all the files o this repository. ( with the exception of /z_home-page/index.html
 - Be sure to log your version changes into the /z_home-page/index.html page
* submit the patch and tag the code with that version.(mm I'm not sure of this step.)

