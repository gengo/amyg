This is a set of plug-ins that allows you to use myGengo services (human-translation) directly from the admin interface of Textpattern, Frog CMS, WordPress, Joomla!, and NucleusCMS.

For users (how to install):
=============

Please see the <a href="http://titobouzout.github.com/amyg/">home-page</a> to get installation instructions.

For developers:
=============

  o Welcome! If you have any question or something please font hesitate and ask via issue tracker or email.

  o These plug-ins share the "localization files" and a "PHP Class" which is identically to all the plug-ins.

  o To make distribution of changes to that files easy, I created a file called "compiler.php" which when executed will distribute the changes made to the templates ( the PHP class and the localizations ) to the plugins here.
  
o 
The template PHP class that is copied to all the plugins when executing "compiler.php" is located into /z_template_class/myGengoClass.php
  
o 
The template localizations files that is copied to all the plugins when executing "compiler.php" is located into /z_template_localizations/*

o <b>NOTE:</b> You will notice that these files(templates) are present(duplicated) into each individual plug-in folder. But don't edit these, edit the "master/template" files, because the compiler will overwrite any change made.

o Basically the important information(code) into each plug-in folder is the "plug-in CMS registration" which allows you to find "the translate/myGengo" menu-item on a menu of a CMS. When you click that menu the execution of the class starts which loads all the interface.

o I decided to share most of the code even if it is not "perfect" behavior because makes things simple to maintain.

A complete flow of changes:
------------------------

Imagine this situation:

 - some change to localizations
 - some change to the class

Flow to make some changes:

* Download this repository to a folder in a server.
* Make any change needed to the class /z_template_class/myGengoClass.php
* Make any change needed to the localizations  /z_template_localizations/ ( such: en.dtd )
 - <b>Be</b> sure to make the same change to the other locales.
* execute "compiler.php"
* find your created new version into the folder /z_distribution/ (example: /z_distribution/wordpress.zip)
 - <b>be sure</b> you don't break any other CMS with the modifications of the class.
* test your build into the CMS to make sure all works as excepted.
* do these steps as many times you need.
* Done?
* Search for the last version number marked on this repository and bump one number in all the files o this repository. ( with the exception of /z_home-page/index.html
 - Be sure to log your version changes into the /z_home-page/index.html 
* submit the patch and tag the code with that version.(mm I'm not sure of this step.)

