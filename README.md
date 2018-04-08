Kyela
=====
[![Docker image](https://images.microbadger.com/badges/image/abienvenu/kyela.svg)](https://microbadger.com/images/abienvenu/kyela "Docker image")
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abienvenu/Kyela/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abienvenu/Kyela/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/abienvenu/Kyela/badges/build.png?b=master)](https://scrutinizer-ci.com/g/abienvenu/Kyela/build-status/master)
[![Code Climate](https://codeclimate.com/github/abienvenu/Kyela/badges/gpa.svg)](https://codeclimate.com/github/abienvenu/Kyela)
[![Latest Stable Version](https://img.shields.io/packagist/v/abienvenu/kyela.svg)](https://packagist.org/packages/abienvenu/kyela)
[![License](http://img.shields.io/badge/license-AGPL%203.0-red.svg)](https://github.com/abienvenu/Kyela/blob/master/LICENSE)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/mini.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)

Participation polls for group events

Features
--------

* Create one or many polls
* Each poll has a randomized URL; only people who get the link can have access
* Suggest one or many dates
* Customize the choices (text and color), add more choices
* User comments
* Simple usage, no authentication
* Mobile friendly
* No ad, no fee, no spying, just OpenSource

Basic Usage
-----------

* Point your browser to a website hosting the Kyélà application, like http://kyela.net
* Pick a name and click "Create" to create a new poll
* Bookmark the URL of the poll
* Add participants and events
* Share the URL of the poll with your friends via email or chat
* Enjoy!

Server Installation
-------------------

Maybe you want your own private Kyélà server firewalled somewhere to protect your super-secret meetings.
Or Maybe you want to run a customized, cooler version, for your private team of even for the public.
Anyway, your are free to do it in the frame of the AGPL license.

#### Simple Docker container

This is the very simplest way to have Kyélà running, suitable for test or demo purpose:

* Install docker
* Download and run the application :
```bash
$ docker run -d --name kyela -p 8042:80 abienvenu/kyela
```
* Point your browser to http://localhost:8042/

After a reboot, you may want to start the application again:
```bash
$ docker start kyela
```

To update the code to the latest Symfony and Kyélà version, run:
```bash
$ docker commit kyela # in case the update goes wrong
$ docker exec kyela composer update
```

NOTE: In this case, all the data lives inside the container, including polls created by your users.
Good point: if you move the container somewhere else, the data goes with it.
However, if you remove the container, the data is DELETED.

#### Docker container with a named volume

Using a named volume is more suitable for production use.

```bash
$ docker volume create --name kyela-data
$ docker run -d --name kyela -p 8042:80 -v kyela-data:/var/www/kyela/data --restart always abienvenu/kyela
```

The named volume can be easily backed up (cf. https://docs.docker.com/engine/tutorials/dockervolumes/#/backup-restore-or-migrate-data-volumes).
This technique enables you to pull newer Docker images of the kyela application, remove the old container, and instanciate a new one using the same data volume:
```bash
$ docker pull abienvenu/kyela
$ docker stop kyela
$ docker rm kyela
$ docker run -d --name kyela -p 8042:80 -v kyela-data:/var/www/kyela/data --restart always abienvenu/kyela
```

### Native

Native installation is deprecated, but you can still [try it here](NATIVE_INSTALL.md).

Customisation
-------------

To edit or extend the FAQ or About page, you just have to add entries in Resources/translations/faq.en.yml (or about.en.yml).

For further customisation, you have to edit the templates or the code.
Because of the licence (GNU Affero GPL-3.0), you must publish the modified code as soon as your project is publicly online.
See below how to develop and contribute to the Kyélà project.

Developing
----------

Would you like to tweak Kyélà, trying your very own features ?
You need not install anything but Docker, and mount the code into a running container. Here is how:

* Download the code at https://github.com/abienvenu/Kyela/archive/master.zip
* Unzip it
* Launch the kyela docker image, mounting the directory of the unzipped code:
```bash
$ docker run -p 8042:80 -v /where/you/unzipped/the/code/Kyela-master:/var/www/kyela/src/Abienvenu/KyelaBundle -d --name kyela-dev abienvenu/kyela
```
* Point your web browser to http://localhost:8042/app_dev.php
* Use your favorite editor to modify the files into the Kyela-master directory, and see the results in your browser

If you use an advanced PHP editor like PHPStorm, you may want to give it access to the vendor directory so it can parse the librairies.
Here is how to get a copy of it:
```bash
$ docker cp kyela-dev:/var/www/kyela/vendor Kyela-master/vendor
```

Contributing
------------

Please share with us any cool feature or improvement you made! All you need is Github account. Then:

* Fork the Kyélà project https://github.com/abienvenu/kyela (see https://help.github.com/articles/fork-a-repo/)
* Clone your fork of the Kyélà project: ```git clone https://github.com/YOUR-USERNAME/Kyela```
* Like in the previous "Developing" section, launch the kyela docker image, with the code mounted:
```bash
$ docker run -p 8042:80 -v /where/you/cloned/the/code/Kyela:/var/www/kyela/src/Abienvenu/KyelaBundle -d --name kyela-git abienvenu/kyela
```
* Point your web browser to http://localhost:8042/app_dev.php
* Use your favorite editor to modify the code
* Test your code: ```docker exec kyela-git phpunit -c app```
* Make a pull request: https://help.github.com/articles/creating-a-pull-request/

Your contribution will be reviewed, and probably merged into the main project.

CHANGELOG
---------
* 1.7.1 :
  - Upgrade from Symfony 2.8 to Symfony 3.4
* 1.7.0 :
  - Upgrade base components (PHP, Symfony, PHPUnit...)
  - Added first Behat tests
  - Deleted the "Contact" page, because of spam hassle
* 1.6.10 :
  - Added "subtitle" for events
  - Added a link below choices to add another option
* 1.6.9 :
  - Graphical tweaks
  - Added the "tips" section
* 1.6.8 :
  - Added /frame for easy integration from remote websites
  - Event name is now optional (yes, there are use cases)
* 1.6.7 :
  - Added confirmation popup if you act for another participant
* 1.6.6 :
  - Fix for sortability that would prevent mobiles from scrolling the participant list
  - Now you have to click on the participant name to move it up or down
* 1.6.5 :
  - Added the ability to sort participants via drag'n drop
* 1.6.4 :
  - Added documentation about developing and contributing
  - Fix for favicon.ico
* 1.6.3 :
  - Added German translation (thanks to NoodleBB)
* 1.6.2 :
  - Bootstrap and Jquery are no longer included in the source code, but fetched via composer
* 1.6.1 :
  - The email contact is now configurable via an environment variable
  - Fix for empty event names
* 1.6.0 :
  - Participations are now set using AJAX, without reloading the whole poll page
* 1.5.10 :
  - Fixed the Head lines and Bottom lines that would be reset to blank as soon as you edit a poll
  - Unit tests are now in Dockerfile, so we should not have any more broken Docker images
  - Removed deprecated code
* 1.5.8 :
  - Embedded Dockerfile with the application source code
* v1.5.6 :
  - Bugfix for date comparison with Sqlite
* v1.5.5 :
  - Added limit for "Archives" to avoid memory outages
* v1.5.4 :
  - Better documentation
  - Docker compatibility
* v1.5.0 :
  - Code refactoring: replaced traits by controller inheritance
* v1.4.2 :
  - Fix : do not display "add comment" if there is no participant
* v1.4.1 :
  - Added protection against dumb crawlers
  - Explained cookie policy
* v1.4 :
  - Content enhancements on Homepage/Faq/About
  - New example: holidays
  - Ability to add dates without specifying name nor time
* v1.3 :
  - Added placeholders
  - Date and time are now optional
* v1.2 :
  - Added ability to add an icon for each choice
  - Removed the idea of a separate file about-me.en.yml and faq-me.en.yml - just edit the files
* v1.1 : Many small improvements
  - Ability to add a participant directly from poll view
  - Hide Choice priority, this is purely internal data
  - Better ergonomy for choice reordering
  - Fixed the bug when creating a Poll/Comment/Participant/Event with only spaces
  - Fixed choice ordering in poll view
* v1.0 : Added ability to add comments
* v0.9 : Added ability to lock a poll
* v0.8 : Added ability to add custom HTML above and below the poll
* v0.7.1 : Critical fix for creating choices
* v0.7 : Added access to past events
* v0.6 : Added fixtures and images for examples
* v0.5 : Added ability to reorder choices
* v0.4 : Added ability to switch language English/French
* v0.3.2 : Added time widget
* v0.3 : Added deletion confirmation, autofocus, fixed poll deletion
* v0.2 : Added contact page, default URL and choices for new polls
* v0.1 : First (mostly) working release

TODO
----
* Timezones ?
* Sort participants alphabetically ?
* Make a knpbundles readme
* Code improvements (see Scrutinizer)

FUTURE (MAY BE) FEATURES
------------------------

* Descriptive placeholders
* Put Glyphicons on every button, including standard "Save", "Cancel"...
* For a Poll, customize "Total" color cell at certain thresholds
* Notification subscriptions
* Syndication
* Aggregate/Anonymous mode, for events with lots of participants: the grid only displays total numbers, you can add yourself, then you get a personal link to modify/delete your participation
* Integration with personal agendas (Google, Yahoo...)
* Make a logo, a decent favicon

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/big.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)
[![knpbundles.com](http://knpbundles.com/abienvenu/Kyela/badge)](http://knpbundles.com/abienvenu/Kyela)
