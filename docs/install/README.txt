Crustacean Database Project |
-----------------------------

Live project URL: https://mmp-dec21.dcs.aber.ac.uk/project/project/index.php

Live login details:
Email: test@example.com
Password: test

NOTE: The server uses a self-signed certificate, your browser will
likely flag this up as a security problem. You will have to tell the browser to
ignore the warning to proceed. The website and the server are configured to redirect
ALL http traffic to https instead

---Installation instructions---

Developed on:
PHP Version: 7.3.14
MySQL (MariaDB) Version: 10.3.22

1. Upload the website files to your host
  1.1. The website doesn't have to be in the root directory
       of your website, you can upload them to a sub-directory too

2. Import the import_sample.sql into your database, this contains sample data
   NOTE: This also contains sample administrative users
  2.1. Alternatively, you can import import_empty.sql if you don't
       need any sample data
   NOTE: There are 4 SQL files. Files with "_nodb" at the end
   WILL NOT create a new database. Files without "_nodb" WILL
   create a new database called "crustacean_db".

3. Edit system/config.php
  3.1. Set the URL to your own
  3.2. Set the contact email to your own
  3.3. Set the sender email to your own
  3.4. Edit the database connection details at the bottom

4. Edit contact_mailer.php and system/elements/pages/contact_body.php
   NOTE: The contact form relies on Google's reCaptcha v3, which requires an API key
   to function. You will have to edit those files and include your API key.
   You can get one for free from Google: https://www.google.com/recaptcha/intro/v3.html

5. OPTIONAL: If you're running MySQL and have the option of
enabling Event Scheduler, you can import old_resreq_remove.sql and
old_resreq_invalidate.sql
This will automatically remove password reset requests older
than a month and invalidate all reset requests older than a week
Alternatively, you can add a cron job (old_resreq), file
has no extension. You will need to edit the file to specify
the location of the system/cron.php file within your system
  5.1. If running crontab, please allow the script to access
  system/log/crontab_log (no extension). If on a UNIX system, simply
  "chmod" 777 both the directory and the file. This will
  allow systemwide access to the directory and file.
  If you, however, want to restrict the access, and know what you are doing,
  you are free to do so as long as PHP has access to the file.

You can change various other settings in system/config.php
relating to the system.

-----------------------------------------------------------
Once set up, the default administrative user details are: |
Email: admin@example.com                                  |
Password: Br^8uBB8bm8Bpt42                                |
-----------------------------------------------------------