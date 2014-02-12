Infostander Backend
===================

#Information
This project consists of the backend for infostander. The backend is a symfony project. 

#Installation instructions
###Get composer
With brew (global install)
<pre>
$ brew install composer
</pre>

Without brew. Go to project directory:

<pre>
$ curl -s http://getcomposer.org/installer | php
</pre>

This will download composer.phar to the project directory.

###Setup project
<pre>
$ cp app/config/parameters.yml.dist app/config/parameters.yml
</pre>

Fill in relevant settings.


###Install dependencies for project
With brew:
<pre>
$ composer install
</pre>

Without brew:
<pre>
$ php composer.phar install
</pre>

###Setup DB
<pre>
$ php app/console doctrine:schema:update
</pre>
(--force)

###Fix permissions
Make sure the webserver has access to 
<pre>
app/cache/
</pre>

and 

<pre>
app/logs/
</pre>


###Check system configuration
Check that local system is properly configured for Symfony.

<pre>
$ php app/check.php
</pre>

> returns 0: mandatory requirements are met, 1 otherwise.

Check config link in browser

<pre>
http://[path_to_project/app/web]/config.php
</pre>

Fix problems.


###Make super-user
<pre>
$ php app/console fos:user:create [admin_username] [test@example.com] [p@ssword] --super-admin
</pre>


###Ready to go!
