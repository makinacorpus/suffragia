About Suffragia
=========

Suffragia is a project for managing the counting of elections electronically

Install
=========

1. Fill the parameters.yml or use the session variables:
	http://symfony.com/doc/current/service_container/parameters.html
1. Put the source code on online server
2. Set up the dataBase with your local mysql server (or other db, and change in config.yml):
	* http://symfony.com/doc/current/doctrine.html
	* php bin/console doctrine:database:create
	* php bin/console doctrine:schema:update --force
3. Create a admin and promote this user:
 php bin/console fos:user:create myUser myUser@email.com myPassword
 php bin/console fos:user:promote myUser ROLE_ADMIN

