# Learn.med, an LMS based on Wordpress, for courses built for xAPI

## What's included:

* [Wordpress](http://wordpress.org)
* [LearnDash](http://www.learndash.com) - an LMS plugin for Wordpress
* [GrassBlade](https://www.nextsoftwaresolutions.com/grassblade-xapi-companion/) - an LTI launcher for pointing to external Tin Can modules
* [Bedrock](http://roots.io/bedrock/) - a development stack for Wordpress
* [Sage](http://roots.io/sage/) - a starter theme for Wordpress by the same folks as Bedrock

## Installation

### Install these first

* [learn-med-stack](https://git.med.uottawa.ca/E-Learning/learn-med-stack/), the local dev stack to run this LMS locally on your machine. This will also ask you to install this repo as a sub-directory of a directory called learn.med. See the installation instructions.
* [npm](https://nodejs.org/download/)
* [Homebrew](http://brew.sh)

### Install rbenv and ruby-build, so you can have the right versions of ruby installed

    brew update
    brew install rbenv ruby-build
    echo 'eval "$(rbenv init -)"' >> ~/.bash_profile
    source ~/.bash_profile  # <= this will save you to have to close and re-open your terminal

### Install a version of ruby for the project

    rbenv install 2.0.0-p247
    rbenv local 2.0.0-p247

### Install the ruby gems that are needed

    gem install bundler
    bundle install

## Modifying the look and feel

1. Open your terminal and go to the `web/app/themes/learn-med-theme/` directory
2. Install the nodejs modules (`npm install`)
3. Install grunt on your system so you can run it from anywhere (`sudo chown -R $USER /usr/local && npm install -g grunt-cli`)
4. Run grunt (`grunt dev`)

### Modifying the courses, lessons, lesson topics and quiz pages

Edit the following files under `web/app/themes/learn-med-theme/`

* `single-sfwd-courses`
* `single-sfwd-lessons`
* `single-sfwd-topics`
* `single-sfwd-quiz`

## Plugins

### Installing a Wordpress plugin

To make sure the plugin is installed for other developers, follow these steps:

In `composer.json`, add a line in the last `"require"` block, following this syntax:

    "wpackagist/<plugin-name>": "<plugin-version>",

*`wpackagist` is a special repository that contains a mirror of all Wordpress plugins adapted to work with `composer`.*

So if we want to install the plugin `advanced-custom-fields` at version `4.3.4`, we'd add the line:

    "wpackagist/advanced-custom-fields": "4.3.4",

Then, run `composer update` through vagrant from the `learn-med-stack` directory to have the new plugin installed:

    vagrant ssh -c 'cd /srv/www/learn.med/current/ && composer update'

## Updating translations

### If the text is in a course or a Wordpress page

1. In production, find the text in Wordpress.
2. Navigate to the French version of the course or page
3. Make the change in French.

### If the text is inside javascript code

1. Make the change in the main.js file
2. Make sure to change all copies of that text string
3. Update the French versions
4. Run `grunt` from `web/app/themes/learn-med-theme/` to compile the javascript again
5. Commit your changes and push to production

### If the text is in php code

1. Update the English text (make sure that it's inside a `<?php _e("TEXT", 'learn.med'); ?>` localization function (`_e()`, or `__()`, or others like it, which Wordpress uses).
2. In Wordpress, go to **WPML** → **Theme and plugins localization** and press **Scan the theme for strings**
3. The context `learn.med` (or whichever other context was the second attribute in the localization function) will have new strings not translated. Press the button labelled **View strings that need translation** next to the context with the new text.
4. Find the string, click **Translations** and add a new translation. The old translation should still be in Wordpress, and you can get to it by using the search function.
5. Click **Translation complete** and click **Save**.
6. Check to make sure the translated text looks good.
7. Push your code to production and perform the same steps.

[Watch a screencast of these steps &rsaquo;](http://recordit.co/FkqpQbcLrA)

## Deploy your code to production or staging servers

### Install Capistrano

Capistrano is used to connect to the production and staging servers and run tasks like deploying code, rolling back to a previous version, and more.

Install bundler

    gem install bundler

Install Capistrano and its plugins by running:

    bundle

If you're running into problems, you might have to install `rbenv`, `ruby-build`, `rbenv-bundler` and running `bundle` again.

### Make sure you can connect to git.med.uottawa.ca via SSH

You have two options:

1. [Generate an SSH key](https://help.github.com/articles/generating-an-ssh-key/) and upload it to your git.med account
2. [Install the Github Desktop](https://desktop.github.com) application, connect to your git.med account with it and keep it running at all times

Either of these will ensure that when you deploy your code to production or staging, that the server will be able to download the latest version of the code from git.med.

### Deploy to production

First, merge any changes made by others in the team to the main master branch

    git checkout master
    git pull origin master
    # resolve conflicts and commit

Then, merge your work to the master branch. The master branch is for stuff that is ready to put in production.

    git merge <name-of-branch-with-modifications>
    # resolve conflicts and commit

Then push your new changes to the master branch on git.med

    git push origin master

If you're upgrading Wordpress to a new major version, back up the database in case you need to rollback.

Run the deploy ask to push the master branch to production

    bundle exec cap production deploy

If you upgraded Wordpress to a new major version, you'll need to also update the database. Just navigate to the site using an admin user and follow the steps.

### Deploy to staging

On staging, it will ask you what branch to pull from when deploying (defaults to the current branch). You'll first need to push your branch to the repo on git.med

    git push origin <name-of-current-branch>

Then run the deploy task and confirm the branch you want deployed when asked.

    bundle exec cap staging deploy

### Rolling back to the last-working copy of what was in production (the previous _release_)

    bundle exec cap production deploy:rollback

This will make a tar archive of what was in production and store in on the server. Then it'll rollback to the previous release.

If you made a major update to Wordpress and the database was updated, you may run into problems rolling back, so it's best to thoroughly (thoroughly!) **test a major update to Wordpress before pushing in production and making a backup of the database**.

### Module content formatting

Classes are available using the common look and feel of the platform to add to a <div> when we have content that needs to be emphasized as important content in an attempt to ensure that we keep a common look and feel and professional quality for the modules as well and make sure that everything is readable and accessible.

 	.importantBoxOne: uOttawa red top and bottom borders with a very light gray background.
	.importantBoxTwo: uOttawa gray top and bottom borders with a very light gray
	.importantBoxGray: uOttawa gray background and white text.
	.importantBoxDarkGray: uOttawa red background and white text.

Also some semantic to add content as a note (sticky note, side note)

	<div class="quote-container-other">
	<i class="pin"></i>
	<div class="note-other yellow-other">
	<cite class="author-other"></cite>
	</div>
	</div>

### Refresh PHP on the server (final step!)

Before your changes will show up the following command must be run (on the server) to restart php-fpm:

    sudo service php5-fpm restart