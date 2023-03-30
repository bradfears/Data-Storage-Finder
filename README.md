# Data Storage Finder
The Data Storage Finder is a tool to help users choose among a number of available storage services.

INSPIRED BY: CORNELL DATA MANAGEMENT SERVICE GROUP AND CORNELL INFORMATION TECHNOLOGIES CUSTOM DEVELOPMENT GROUP

This software is being shared free of cost and with no restrictions on re-use or modification. The code is provided “as is” without warranty of any kind, express or implied, and the author takes no responsibility for maintenance or upgrades.

## Background
The original version of the Data Storage Finder (DSF) was created by the Cornell University Data Management Service Group and Information Technologies Custom Development Group. The [original DSF](https://github.com/CU-CommunityApps/CD-finder) was a Drupal extension written for Drupal 8. The Cornell DSF works on Drupal 10 with some slight modifications, but the source code repository has not been updated in several years. This new version of the DSF was written in PHP/JQuery/CSS and uses a MySQL/MariaDB database. Though some features are different from the original DSF, the primary functionality remains the same.

Configuring the Data Storage Finder will take some time and planning. You are literally building a portfolio of all data storage services available at your institution. Depending on how you configure it, you could specify things like cost, availability, where to get support, etc. You also need to build a list of questions to help filter results. There are many things to do, so let's get started!

## Requirements
- PHP v7+
- MySQL/MariaDB
- Web server (Apache, Nginx, etc.)

## Installation
1. Get a copy of the latest relase from this repository. Refer to the **Releases** section on the right of this page.
2. Extract the files and place them somewhere in your website directory (e.g., /var/www/html/dsf).
3. Create a MySQL/MariaDB database. The local database user should be granted ALL PRIVILEGES.
4. Import the dsf_structure.sql file from the *database* directory in this repository.

## Configuration
1. Edit your config.php file to specify database name, user, etc. Also, **very important**, change your SALT.
2. Create your first user by pointing your browser to <domain>/dsf/install.php. You should delete install.php after the first user is created.
3. Log into the Administration Menu with your newly created user (i.e. <domain>/dsf/admin).
4. On the Admin Menu index page, pay attention to the text in red. Steps need to be followed in order or things will not make sense.  
  
    * FIRST, create your navigation menu. These menu items are questions that make up your filtering pane. Examples might be "What is the purpose of data storage", or "What is the classification of your data?"  
  
    * SECOND, add capabilities for each navigation menu item. These are the checkboxes that users can select to filter results. Examples might be "Backup", "Share files", or "Collaborate with colleagues".  
  
    * THIRD, add field types. These field types describe each storage service. Examples might be "Brief Description", "Cost", or "Capacity".  
  
    * FOURTH, it's time to add actual items. Click *Add Item* from the Admin Menu, then give the item a name and subtitle. These will be diplayed on the tiles that are presented to the user. After you add an item, go back and EDIT the item. Now you will see the fields you defined, such as "Cost". Enter the appropriate information for each field type.  

