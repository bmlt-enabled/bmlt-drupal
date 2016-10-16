DESCRIPTION
-----------

The Basic Meeting List Toolbox (BMLT) is a powerful, database-driven system for tracking NA meetings.
It is NOT an official product of [NA](http://na.org). Rather, it is a project designed and implemented by
NA members, and meant to be used by official NA Service bodies.

This project implements a "standalone satellite." It does not have to be integrated into a content management
system.

REQUIREMENTS
------------

The project requires a functioning [BMLT root server](http://bmlt.magshare.net/root-server).
It does not implement a root server, but connects to an existing one.
It requires a Web server capable of executing PHP 5.0 or above.

This class uses the BMLT Satellite Base Class, which is available on [BitBucket, here](https://bitbucket.org/bmlt/bmlt-satellite-base-class).

INSTALLATION
------------

You install the uncompressed 'bmlt' directory (from whichever zipped file represents your Drupal instance) in /sites/all/modules/ (You may need to create the "modules"
directory).
Enable the module in the usual manner.
This module operates through the use of text filters/input formats. You need to set up a text filter that uses the
"Add a BMLT instance inline in text" filter.

CHANGELIST
----------
***Version 3.4.0* ** *- October 16, 2016*

- Reintroduced support for the Google API Key.

***Version 3.3.9* ** *- May 22, 2016*

- Now detect escape key to close meeting details overlay.
- The mobile JS file was importing a non-HTTPS Google Maps API. I changed this to use the HTTPS version.
- Made a change to the way the Google Maps API is called (now make sure I include SSL version).

***Version 3.3.7* ** *- May 2, 2016*

- Tweaked this README file to compensate for the new Atlassian format.
- Added a repo icon.
- Added [Doxygen](http://doxygen.nl) documentation.

***Version 3.3.6* ** *- April 21, 2016*

- Made it so that the details in the standard shortcode can be dismissed by clicking anywhere. I also now display a visible translucent mask.
- Fixed a bug, in which the [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) did not style correctly. The default Drupal styles clobber the built-in ones, so I had to reinforce the built-in ones a bit.

***Version 3.3.4* ** *- April 14, 2016*

- Refactored to make the code more straightforward and reusable.
- Refactored this file for better markdown display.
- Fixed a bug, in which the proper throbber was not being displayed where multiple themes are on the same page for the [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcode.
- Added a "Complex Table" preset to the unit test (Displays multiple [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcodes).

***Version 3.3.3* ** *- April 9, 2016*

- Fixes a bug that could possibly cause issues with unparameterized instances of [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/).

***Version 3.3.2* ** *- April 9, 2016*

- Added a "Breaker Div" to the end of the meeting list.
- Work on improving code quality.
- Added more style hooks to the [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcode display.
- Fixed a minor bug in the [bmlt_simple](http://bmlt.magshare.net/satellites/simple/) and [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcodes, where supplying just a settings ID would be ignored.

***Version 3.3.1* ** *- April 6, 2016*

- Made the weekday tab overflow hidden.
- The format circles now float to the right.
- Added a display for days with no meetings.
- Fixed a bug in the [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcode, where the loading throbber would get replaced too quickly when selecting weekday tabs.
- Corrected a bug that allowed "00:00" times (should be "Midnight").
- Fixed a bug in the "simple map search" that displayed the info windows offset.

***Version 3.3.0* ** *- April 4, 2016*

- Made it so that we can have specialized themes, amied at only certain shortcodes.
- Major rewrite of the [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcode, to improve responsive design.

***Version 3.2.4* ** *- April 1, 2016 (Happy April Fools'!)*

- Broke the table styling out into separate files that are all loaded at once. This allows a lot more flexibility when implementing the table display.
- Tweaked the GNYR style.
- The JavaScript had a fundamental error that prevented multiple instances of the table. That's been fixed.

***Version 3.2.3* ** *- March 30, 2016*

- Got rid of an undeclared variable warning.
- Fixed a bug that caused rendering issues with the new table shortcode on Internet Exploder.
- Fixed a minor style issue, where the selection triangle would flow below the text in large text situations.
- Changed the styling for the selected header triangle to make the table display a bit more responsive.

***Version 3.2.2* ** *- March 29, 2016*

- This should really fix things. Honest.
    
***Version 3.2.1* ** *- March 29, 2016*

- Fixes a JavaScript error introduced in 3.2.0.
    
***Version 3.2.0* ** *- March 29, 2016*

- Significant addition of the new [bmlt_table](http://bmlt.magshare.net/satellites/the-fast-table-display/) shortcode.
- Removed unecessary items from admin screen.
- Bug fixes.
    
***Version 3.1.0* ** *- March 8, 2016*

- Added support for HTTPS.
- Added Italian localization.
    
***Version 3.0.29* ** *- January 10, 2016*

- Added support for a runtime language selector as a cookie. If you set a cookie named "bmlt_lang_selector," and set its simple string value to an ISO 639-1 or ISO 639-2 **SUPPORTED** language, that will select the client language.

***Version 3.0.28* ** *- August 15, 2015*

- Added Portuguese Translation.

***Version 3.0.27* ** *- May 25, 2015*

- Updated the base class (CSS fixes, mostly).
    
***Version 3.0.26* ** *- January 31, 2015*

- Fixed an issue with the extra fields display in the regular shortcode display details.
- Fixed an issue where the arbitrary fields were actually creating too many results.
- Now hide the distance_in_km/miles parameters in the meeting details (these are internal parameters).

***Version 3.0.25* ** *- November 23, 2014*

- Fixed a CSS issue with the admin display map. Some themes (especially responsive ones) declare a global max-width for images. This hoses Google Maps, and has to be compensated for.
- Added full support for arbitrary fields. This was an important capability that was left out after Version 3.X
    
***Version 3.0.24* ** *- July 31, 2014*

- Fixed an issue where some cURL calls were being rejected by tinfoil servers.
- Fixed an admin issue, where new settings would display a bogus ID.

***Version 3.0.23* ** *- July 17, 2014*

- Added Danish localization, and fixed a minor admin bug.

***Version 3.0.21* ** *- February 23, 2014*

- This adds fixes for servers that run on non-standard TCP ports.

***Version 3.0.20* ** *- December 31, 2013*

- Fixed a character set issue that affected Internet Exploder.

***Version 3.0.19* ** *- December 7, 2013*

- Added French localization

***Version 3.0.18* ** *- September 7, 2013*

- Fixed a couple of minor German localization issues.
- Fixed some JavaScript issues with the [[bmlt_mobile]] shortcode.
    
***Version 3.0.17* ** *- July 1, 2013*

- Corrected German localization.
- Added the ability to specify which day weeks begin (in Europe, it is common for weeks to begin on Monday).

***Version 3.0.16* ** *- May 22, 2013*

- Added German localization.

***Version 3.0.15* ** *- May 19, 2013*

- Fixed a small issue, in which entering text into the CSS field in the admin window would not "dirtify" the settings.
    
***Version 3.0.14* ** *- May 18, 2013*

- Fixed an issue, in which the AJAX URI got borked.
    
***Version 3.0.12* ** *- May 16, 2013*

- Removed some useless calls to the server. These were leftovers from the original version, and slowed down the page load.

***Version 3.0.11* ** *- May 13, 2013*

- Reduced the number of times that the marker redraw is called in the standard [[bmlt]] shortcode handler.
- Fixed an issue with CSS that caused displayed maps to get funky.

***Version 3.0.10* ** *- May 5, 2013*

- Fixed an issue where "too many reds" would show up as map result of the first search.

***Version 3.0.8* ** *- April 28, 2013*

- Added support for display of military time.

***Version 3.0.7* ** *- April 21, 2013*

- The string search was being improperly handled. This has been fixed.
    
***Version 3.0.5* ** *- April 16, 2013*

- Fixed a Swedish translation error.
- This should autodetect locale, now.
- Fixed some warnings in Drupal 7.
    
***Version 3.0.4* ** *- April 15, 2013*

- Fixes a JavaScript bug, caused by the work on the root server.
    
***Version 3.0.3* ** *- April 15, 2013*

- Fixes a Swedish localization issue.
    
***Version 3.0.0* ** *- January 26, 2013*
- Major rewrite of the default shortcode.
    
***Version 2.2.2* ** *- May 13, 2012*

- Fixed a nasty bug in the admin interface that could create multiple empty settings.
    
***Version 2.2.1* ** *- March 28, 2012*

- Adds an alert to the new map search, informing the user when no meetings are found.
    
***Version 2.2* ** *- December 31, 2011*

- Introduces some fixes for validation errors in the new map search.
- Removed some errant CSS.
- Now strip out the [[bmlt_mobile]] shortcode if the page is not a mobile page. This allows the shortcode to be used, as the comment version is stripped by "code cleaners."
    
***Version 2.1.24***

- Fixes a JavaScript Error with the new map search on Internet Explorer.
    
***Version 2.1.23***

- Improved some of the basic styles in the new map search info windows.
    
***Version 2.1.22***

- Addresses a bug in Mozilla Firefox, that prevents the use of the popup menus in the multi-day (red) map icons.

***Version 2.1.21***

- Fixes a couple of minor theme/style issues.
- Mitigates a strange Firefox bug that caused weird page loads when closing the location area.

***Version 2.1.20***

- Added the new shortcode "bmlt_map." Very useful.
                
***Version 2.1.19***

- Addressed a condition that could result in warnings if the error level is set to ERR_ALL

***Version 2.1.18***

- Now have the bmlt_changes shortcode.

***Version 2.1.17***

- Refactored the shared code into a submodule.

***Version 2.1.16***

- Added some more data to the displays for meeting info in the mobile client.
    
***Version 2.1.15***

- Reformatted the two Drupal-specific files to use Drupal coding standards.
    
***Version 2.1.14***

- Fixed a very strange bug that seems to cause error 500s on some servers. Not sure why the fix worked, but it does. This only manifested when doing an "address only" search in mobile mode.

***Version 2.1.13***

- Fixed a bug that prevented the Contact US form from being displayed.

***Version 2.1.12***

- Replaced a nice, efficient 'implode' with a primitive, kludgy loop, because Drupal 7 thinks implode() deserves a coding warning.
- Did a bit of code cleanup to make the Coder module happy-ish.

***Version 2.1.11* ** *- May 8, 2011*

- Fixed an error in the parameter loads that interfered with advanced search functions.
    
***Version 2.1.10* ** *- May 7, 2011*

- Fixed a JavaScript error that prevented saves.
