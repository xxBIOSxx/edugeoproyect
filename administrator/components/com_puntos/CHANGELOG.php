<?php defined('_JEXEC') or die(); ?>
Hotspots changelog

# infobubble problems with IE8
# if we use geolocation centering it creates problem for single view of hotspots
# language improvements in the menu options (otherwise we had untranslated strings)
# the sys. language file was not loading available translations
# error in the language file
# show all tabs on map was not working propery
# in some situations changing categories was not working properly in frontend
# welcome message was wrong right after the installation when the configuration was never saved
# when importing from sobipro the access field was not properly set
# sorting was not working properly in backend marker view
# fixing XSS vulnerability in the mail function
# insufficient escaping leads to a possible SQL injection 2
# insufficient escaping leads to a possible SQL injection
# users with editState permissions were not able to change the hotspots published state in the frontend
# search was not working when the string we were searching for was containing "id"
# when we had a multilingual site the infowindow was making 2 requests to show the data instead of 1
~ changed link to the JED for the core version
# when using the search the menu was always showing up even though the user has closed it
~ content plugin is now only executed on featured view if we have the code in the introtext
+ added option for width to the content plugin
+ added option for zoom to the content plugin
# hotspots adding now per default uses the center & zoom set in the global config
# RSS was not working on joomla 3 & with php strict mode on
# cannot select FAHRENHEIT as temperature unit for the weather layer
+ added latest hotspots module
+ Link author to jomsocial, CB etc
# KML was not respecting the state of a KML file (published, unpublished)
~ KMLs now are cleared when we switch between categories
# it was not possible to save a sample image for the category
# select "show all tabs on map" was refreshing the map instead of showing all the tabs on the map...
+ added AUP plugin
+ added option to execute the content plugin in K2
- removed the category shadow as the latest google maps API has deprecated it and it is no longer available in the visual refresh
+ added option to exclude a category from frontend submission
# the markers on the custom tiles were not clickable
# Infobubble - hiding markers when we use single instance of Infobubble - more info: https://code.google.com/p/google-maps-utility-library-v3/issues/detail?id=264
# wrong values for boundary and default centering
+ added an option to set a custom zoom level when centering using the user's location
+ added high accuracy option to the user's geolocation (this will use HTML5 geolocation)
+ added an option to center the map using the user's location (based on IP)
+ adding a show readmore option for each hotspot in the backend
# markers were disappearing on the map when first clicked
~ the markerImage class deprecated in the last version of google Maps API, so moving to the new icon class
# on IE searches that contain UTF8 characters don't return any results
# destroying the infobubble from the dom when we close it
~ turned on autopan for the infobubble
# when the menu was hidden we could not resize the map
~ moved some options to the map tab where they fit better
+ added configuration option for Pan Control
+ added configuration option for Zoom Control
+ added configuration option for Map type Control
+ added configuration option for Scale Control
+ added configuration option for Street view Control
+ added configuration option for Overview map Control
+ added configuration option for Scrollwheel Control
# a notice was display on the form view in frontend when we had a picture
# CB plugin was not displaying the infowindows and markers on the map
+ added an option to hide the menu on the right on start
+ added an option to hide the quick search form
+ added an option to hide the directions button for the marker
# no spaces when showing the user name
# clicking on a marker in the menu was not working on iphone/ipad
+ added plugin to link to Flexicontent
+ added plugin to link (read more) hotspots to external sites
+ added draggable directions option
+ added an option to provide API key & always using an https url to load the map
+ added the visualRefresh option
# fixed - height of infowindow content was bigger than the height of the container
+ added an option to hide the zoom button
# map in single view was not loading properly when the weather api was on
# address was not properly formatted on some places
+ added panoramio layer support
+ added bicycling layer support
+ added transit layer support
+ added traffic layer support
+ added weather api support
+ added clouds layer support
# the hotspots order setting was not having any effect
+ added fullscreen map option
# show author option was missing
+ adding reverse geocoding option in the backend
# the color picker for custom tile marker was not working on joomla > 3
# added filter="raw" to the styled map textarea
# the joomla installer cannot copy empty folders and shows a warning. Adding index.html file to the kml folder because of that
# fixed - the content plugin is now only executed when we have the appropriate context. Otherwise we get a high performance penalty
# date_format was of wrong type
# the geocoder feature in the backend was not working
# sending a mail about new hotspots to moderators was not working
# added missing GPL licenses to some files
# some layout improvements in the backend of joomla 3/3.1
+ added styled maps feature
# fixed PHP strict warning when creating a hotspot
# user was not able to edit their hotspots in frontend even if the edit.own permission was set
# fixing the CB plugin - should now work on joomla 3
# read more link was wrong in search results
~ updated the ccomment plugin integration to be compatible with ccomment5
# a language string was not translated in the javascript
# links to k2 were not SEF
# deleting tiles was not functioning on joomla 3
+ added hotspots-fullscreen class to the body when in fullscreen (allows template devs to hide elements)
# the countmarkers function was not properly working on joomla3
# fixed KML was not working properly on j3
# more php strict fixes
# language for each hotspot is taken into account when we present it in the frontend
# fixing some php strict warnings in the router
# frontend marker picture upload setting was not taken into account
# the title of the hotspot in single view was not shown
# when saving hotspot on microsoft server the publish_down date was incorrectly set to the current date of saving
# content - Hotspots plugin is now compatible with joomla 3.0
+ adding SQL SERVER compatibility
# some layout issues with the installer on Joomla 3.0
+ adding a copy link button
~ category-shadow is now required in the category settings
+ adding metadata information on map view
# map was not refreshing the locations properly when the search tab was selected
# fixed layout issues on joomla 3.0
# date was not respecting the format setting on the maps view
# fixed a bug with the jomsocial plugin
# some hotspots were not shown on the map and in the menu (bug introduced with the new sorting)
# the check all button in backend was not functioning properly on j3.0
# search by street/country/town was not working - thanks to Grazing Cat .Inc for providing us with a fix
# sorting hotspots by name ASC in the menu
# single hotspots was not rendered when ccomment was selected in the options, but it was not actually installed on the user's site
# sql error when hotspots order is set to created time
~ the install script not allows the installation only on joomla >= 2.5.6
# userhotspots was not working on joomla 3.0
# the map was missing from "submit-hotspots" view in the frontend on joomla3
# readmore was showing in the menu even though "Marker detailpage" was set to no
~ making the links to k2 a little better
~ running the intro text through the code of the SEF plugin
# the search - hotspots plugin was not properly working on joomla 2.5 and 3.0
+ Added support for Joomla 3.0
~ setting sticky to 0 when importing from sobi - this way one can edit a hotspot without losing the coordinates
# fixed - edit button in hotspots view was not working
# fixed - infowindow is not centered on the screen when we finish the ajax request
+ updating the CB plugin and adding it to the main package
+ sobiPRO import - added support for the GeoMap field
# fixed - import from sobiPRO was not working
~ making the router function a little more clever. We try to match the hotspot category against the start category selected in the menu
# fixed - map was not working on IE8
# fixed - when editing a hotspot from the frontend the wrong category was selected
# publish/unpublish hotspots redirects to the wrong view
# zoom not working in single view
~ don't rely on $ - avoids conflicts with incorrectly included jquery libraries
# fixed - readmore in menu firing wrong click event
# fixed - search results not showing custom markers
~ search now has pagination and respects the list lenght limit set in the options
# fixed - infoWindow closes when moving around. Now it only closes when one changes the category
# fixed - js bug preventing the map in single view to show the location of the hotspot
# fixed - wrong redirects when entering a hotspot
# fixed - the geolocate button was not click-able in frontend submit form
# fixed - js error when loading the map with custom tiles
# fixed - wrong markers shown when a tab is closed
# fixed - when copying a search link and opening it in another tab the map now loads properly
+ updating the stats module to show number of tiles + option to delete the tiles
# the hotspot name was shown in the div for the marker_address...
~ removing \t \n \r from the ajax response - should reduce the file size a little
# fixed - comma is missing between address and town (infowindow)
# fixed -> wrong tiles generated when searching for something
# updated the liveupdate library - this fixes a bug with wrong version number displayed (no version number displayed)
~ moving to downloadid instead of username&password for live update
- removed the information view as we use overview module in the dashboard for this
# fixed - miltiple KMLs were not shown in the same category
+ added marker length parameter in the settings - controls how many markers are shown at once on the map
+ added custom tiles support
# fixed - hardcoded language strings "There are X hotspots in your current view"
# fixed - unable to save KML files
+ sticky marker option is saves as param - this way when one edits a hotspot and sticky is set to no - we won't replace address or lat & lng values
# search was not properly clearing the results in the menu
# description of categories was not visible in fullscreen
~ continue to look for hotspots when we move around even if the infowindow is open
+ added - option to hide the categories
# fixed - hiding menu hides categories as well
# fixed - menu is now showing uploaded image
# fixed - menu is now respecting the selected settings for country, author, date, address, user_inferface
# fixed - form view was not loading map in IE8
# fixed - some files were missing defined('_JEXEC') ...
# fixed - changing the hotspots.xml file so that it won't install on joomla 1.5 anymore
# fixed - use JString::substr for UTF-8 compatibility when copying images
# fixed - KML view was missing title image
# fixed - not showing edit form when SEF urls were on
# fixed - frontend input hotspots form had invalid html & display issues
# fixed - map not loading under IE8
# fixed - full screen not working under IE9
# an url to the search was not properly executing a search request
# user permission problems when editing in frontend fixed
# discription not shown in single view
# zoom control options were too big when submitting a new hotspot
# language strings updated
+ implemented request: https://compojoom.com/forum/55-wishlist/16432-search-results
~ improved update from 2.0.5
~ fixing hotspot submission bugs
+ added new content plugin that allows you to show a hotspot in an article by using {hotspots hotspot=12} where 12 is the hotspot id
~ when moving around hotspots are displayed way better!
# fixed "close tab" bug
# fixed the zoom bug (thanks Nedyalko)
# fixed layout issue in category edit screen (thanks for the report to Ed)
~ improvements on the submit marker js
# fix for the sticky marker bug (thanks for the report to Ed)
# creating link to a single hotspot had a wrong modal window for selection (thanks for the report to Ed)
# Could not delete categories and hotspots in the backend (thanks for the report to Johnny)
# fixed a bug in the rss module in the backend
# the update db script was not adding index keys for gmlat and gmlng
# the package was containing unnecessary images...
~ adding css for the next and previous buttons
# fixed a bug in the "show all tabs" option
~ layout improvements
+ clicking on marker in streetview will open the infowindow in the streetview mode
~ performance improvements to the JS hotspots Module
~ finding out the category of the article + using ContentHelperRoute to create the link to the hotspots (com_content)
~ using K2HelperRoute class for K2 links
# fixed typo that caused the links to 3rd party components to not be created
# fixed bug with tabs in single view
# fixed the search... (it seems that it was broken in beta1...)
+ added support for publish_up and down in frontend
# add marker view was not fully translatable
~ adding back picture upload for markers
# custom marker image was not used in frontend
# fixed issue with searching and pagination in the backend
# some custom marker fields were not loaded
# backend hotspots was not loading on some linux servers
~ updated some languages
+ added turkish language - thanks for the translation!
+ added a loader module to show loading progress
+ added option to start without a category - centered on the directions
+ added new dashboard in backend
~ improvement to the frontend
+ added send mail plugin for hotspot
~ now it is possible to update from 2.0 to this version
+ frotnend now respects joomla's ACL for adding hotspots
# fixed bug in the RSS feed
~ further refactored js -it is starting to look really good!!!
+ added send js module
+ added print js moduule
# fixed date bug on 2.5
# unable to open hotspot
# saving settings was redirecting to wrong view
~ refactored javascript (using namespace compojoom.hotspots from now on)
+ added turkish frontend translation
# fixed notices
~ removed unnecessary queries
~ removed legacy code
~ speed improvements category layout
# some parts of the interface were nto translated
~ speed improvements to the Hotspots layout in the backend (large DBs - 100 000+ hotspots)
+ added basic ACL support in the backend
# fixed clicking on KML's title didn't bring the edit screen
# fixed wrong links in menu (components dropdown)
+ added sobiPro import
# fixed bug in link_to display in backend
+ added plugin system enabling us to link to 3rd party components
+ installer now installs all plugins
# corrected some js errors
# removed notices
# create a link to single hotspots not working
+ added Italian translation
+ added ukrainian translation
# fixed a problem creating a KML entry in the backend
+ KML files are now shown on map (make sure that you are not accessing the site through localhost!)
# couldn't create hotspots trough the Backend again - due to missing sql fields in the sql file
# couldn't create hotspots trough the Backend
+ we can now choose as many start/default categories as we want
+ added KML views in the backend
~ changed backend edit screen
+ added custom marker image
+ added server side Boundary method
+ new javascript engine relying on "modules"