# RPG Slackbot

This is an attempt to build a slack app with basic RPG bot capabilities for _developers_. You'll have a [design type](http://design-types.net/types.html) and a few RPG like actions you can use.

The idea behind this is to have app_mentions (events) and slash commands to create/list/show and modify user accounts in the internal database. 

So you can add skills like "sarcasm" or other funstuff.

I'll also add a larger list of design patterns you can either use or let the bot post to the channel to make a point. 
The characters will also be able to have some design patterns as skills.

Basically I'm just wasting my time, fiddling around with the slack app API, getting back on track with symfony and experimenting with architectural concepts.

## Attention

At this point there is no specific endpoint security!!!
Either handle this by yourself or configure the firewall to only accept requests from slack itself and your specific user!

## Installation

If you want to install this on your own server, you need 

* a slack app
* a domain, reachable from the internet
* database (SQL)
* php 8.x

## XP system

Experience will be accumulated by giving users XP - This will be calculated realtime to a level with an arithmetic levelling progression, based on a quadratic equation.

## TODO

* [ ] Add a useful property system
* [ ] Add working attributes like strength etc. per default, per character
* [ ] Finish delete command with cascade
* [ ] Add better endpoint documentation
* [ ] Add skill system
* [ ] Add equipment system
* [x] Fix phpstan config/errors :-D
* [ ] Add design patterns as base for an achievement/skill system (and see if the ubiquitous language is correct)
* [ ] Rethink current slack functions, check if they aren't a separate domain and can be moved out
* [ ] Research if it's better to have a query/handler solution instead of using the service directly
