#Infostander Backend

##Information
This project consists of the backend for infostander. The backend is a symfony project. 

###Helpful commands
To clear cache
<pre>
php app/console cache:clear
</pre>

##Installation instructions
###Get composer
With brew (global install)
<pre>
$ brew install composer
</pre>

Without brew. Go to project directory:

<pre>
$ curl -sS http://getcomposer.org/installer | php
</pre>

This will download composer.phar to the project directory.

###Install dependencies for project
With brew:
<pre>
$ composer install
</pre>

Without brew:
<pre>
$ php composer.phar install
</pre>

If there are problems with this, try with apc.enable_cli = Off in php.ini or from the cli:
<pre>
php -d apc.enable_cli=Off composer.phar install
</pre>

###Setup project
<pre>
$ cp app/config/parameters.yml.dist app/config/parameters.yml
</pre>

Fill in relevant settings.


###Setup DB and after changing an entity
<pre>
$ php app/console doctrine:schema:update
</pre>
(--force)

###Fix permissions
Make sure the webserver has access to 
<pre>
app/cache/
app/logs/
</pre>

Furthermore, make sure the webserver has access to 
<pre>
web/images/
</pre>
which is the folder where the uploaded images will reside.

###Set up webserver
<pre>
http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
</pre>

###Check system configuration
Check that local system is properly configured for Symfony.

<pre>
$ php app/check.php
</pre>

> Look for ERROR or WARNING and correct these.

Check config link in browser to run check requirements for the web server.

<pre>
http://[path_to_project/app/web]/config.php
</pre>

Fix problems.


###Make super-user
<pre>
$ php app/console fos:user:create [admin_username] [test@example.com] [p@ssword] --super-admin
</pre>


###Ready to go!

