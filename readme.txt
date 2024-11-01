=== SM Debug Bar ===

Contributors: sethmatics, bigj9901
Donate link: http://sethmatics.com/extend/plugins/sm-debug-bar/
Tags: developer, debug, admin bar, PHP, print_r
Requires at least: 3.1
Tested up to: 3.3
Stable tag: 1.2

EASY TO USE! Dropdown console for wordperss enthusiasts to print and watch the value of PHP variables.

== Description ==

EASY TO USE! Developers who build plugins and themes everyday know the constant hassle of finding the proper place to print out PHP variables and objects to see whats going on. This plugin will help you with that process by giving you a simple function to call, that automatically appends a printed version of your variable/object/array and sends it to a hidden DIV that only loads when the admin bar loads, and only loads when logged in with administrative permissions in wordpress.

So whats the catch? Well, using utilities like this always prooves difficult because knowing how it works is often complex. We have simplified the process as much as possible. Here is an example:

USAGE -> Quickstart: Open any php file that you know is being loaded and add "dbug($GLOBALS)" to print all global variables.
1. You built a new array thats holding some custom meta data loaded on the page.
2. For some reason, you think its empty, or the values are not acting like you expect.
3. Next step is typically to print it out right?
4. With this plugin activated, simply add the following to your file without the quotes "dbug($myarray);"
5. Maybe its 4 arrays and a string you are trying to follow, no problem "dbug($myarray1); dbug($myarray2); dbug($myarray3); dbug($myarray4); dbug($mystring1);"
6. Now load the page, click the "Debug" link at the top of the screen, watch the debug menu slide out, and notice all your variables are printed nicely for your viewing.


Plugin provided by http://sethmatics.com/.

Features include:

* Secured so that only logged in administrators can ever see the Debug panel.
* Ability to append as many variables for printing as desired.
* Now COMPLETELY compatable with the premium "ClassiPress" theme which can be purchased at [ClassiPress](http://appthemes.com/cp/go.php?r=2505&i=b3  "www.appthemes.com")
* Enter a set of "watched" variables you want printed so that you can view them without FTP (for portable developers)
* Added some jQuery collapse/expand functions to watched array variables

Features Coming soon:

* jQuery styling and enhanced array viewing by animated "tree" like array exploring. Navigate your arrays like you would a files and folders.
* code coloring - we realize grey and white is boring and difficult to read when you have alot of it. We plan to take care of that.
* plugin options - currently we are not allowing any options to be chosen, but setting color formats, capabilities that can view the panel, and more.
* ability to choose (by checkbox) standard wordpress variables to watch and track instead of all variables requiring manual entry.

Don't forget to rate our plugin so we know how we are doing!

== Installation ==

To install the plugin manually:

1. Extract the contents of the archive (zip file)
2. Upload the sm-content-widgets folder to your '/wp-content/plugins' folder
3. Activate the plugin through the Plugins section in your WordPress admin
4. There are no options, the debug panel will now be on the admin-bar. See usage in description for more info.

== Changelog ==

Version 1.2

- Added assets banner for plugins directory featured image

Version 1.1.1

- Fixed jQuery no-conflict issue

Version 1.1.0

- Fixed plugin to work on IIS servers
- Fixed plugin css to work with WordPress 3.3 "Sonny"
- Added javascript to turn on tooltip helpers
- Fixed CSS to properly hide the debug panel when javascript is off or crashes

Version 1.0.2

- Added an administrative panel with options related to the plugin
- You can now add variables you want to watch right from the wp-admin panel (see screenshot)

== Upgrade Notice ==

Version 1.0.2

- Fixed styling which was corrupted by WordPress 3.2 styles.

Version 1.0.1

- Fixed the problem causing the dropdown to fail inside the wp-admin

Version 1.0.0

- Built debug bar architecture and tested only in Firefox.

== Frequently Asked Questions ==

Q: How do I use the plugin?

A: Usage instructions are available in the general description tab.

Q: No really, just give me some code to look at?

A: Use the php function dbug() and pass it anything, it will attempt to print it in the console. i.e. dbug($wpdb);

Q: Where is the console located?

A: It can only be veiwed by users with admin permissions in WordPress, and it can be found in the new wp-admin-bar at the top of all pages.

Q: Wait, I don't see any bar accross my pages?

A: Then you most likely have the bar disabled. Go into your user profile and look for the "Show Admin Bar" section.

== Screenshots ==

1. A sample of the debug bar with variables printed.
2. Admin Panel Options (1.0.2)