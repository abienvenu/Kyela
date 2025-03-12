Kyela
=====

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

* Install docker and git
* Download the application:
```bash
$ git clone https://github.com/abienvenu/Kyela.git
```
* Build and run the application in production mode:
```bash
$ cd Kyela && make build && make start-prod && make fixtures
```

To update the code to the latest Kyélà version, run:
```bash
$ cd Kyela && make upgrade
```

Developing
----------

Would you like to tweak Kyélà, trying your very own features ?

* Build and run the application in developer mode:
```bash
$ cd Kyela && make build && make start && make fixtures
```
* Edit the code!

Contributing
------------

Please share with us any cool feature or improvement you made!
