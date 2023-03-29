# Data Storage Finder
The Data Storage Finder is a tool to help users choose among a number of available storage services.

INSPIRED BY: CORNELL DATA MANAGEMENT SERVICE GROUP AND CORNELL INFORMATION TECHNOLOGIES CUSTOM DEVELOPMENT GROUP

This software is being shared free of cost and with no restrictions on re-use or modification. The code is provided “as is” without warranty of any kind, express or implied, and the author takes no responsibility for maintenance or upgrades.

## Background
The original version of the Data Storage Finder (DSF) was created by the Cornell University Data Management Service Group and Information Technologies Custom Development Group. The [original DSF](https://github.com/CU-CommunityApps/CD-finder) was a Drupal extension written for Drupal 8. The Cornell DSF works on Drupal 10 with some slight modifications, but the source code repository has not been updated in several years. This new version of the DSF was written in PHP/JQuery/CSS and uses a MySQL/MariaDB database. Though some features are different from the original DSF, the primary functionality remains the same.

Configuring the Data Storage Finder will take some time. You are literally building a portfolio of all data storage services available at your institution. Depending on how you configure it, you could specify things like cost, availability, where to get support, etc. You also need to build a list of questions to help filter results. There are many things to do, so let's get started!

## Installation
1. Get a copy of the latest relase from this repository. Refer to the **Releases** section on the right of this page.
2. Extract the files and place them somewhere in your website directory (e.g., /var/www/html/dsf).
3. Create a MySQL/MariaDB database. The local database user should be granted ALL PRIVILEGES.

## Configuration
1. Edit your config.php file to specify database name, user, etc. Also, **very important**, change your SALT.
2. Log into the Administration Menu (i.e. <domain>/dsf/admin).

