# SSLCommerz Availability Condition for Moodle
----------------------------------------------

Moodle Availability SSLCommez is a Moodle Availability Condition plugin, with this plugins, you can put a price in any course content and ask for a SSLCommerz payment to allow access.

The person in charge has to configure the enrolment method on the course. For accessing resource or activity a charge or cost can be associated.

It works with "course modules and resources".

Install
-------

* Put these files at moodle/availability/condition/sslcommerz/
 * You may use composer
 * or git clone
 * or download the latest version from https://github.com/eLearning-BS23/moodle-availability_sslcommerz
 * You must also use HTTPS on your Moodle site

Usage
-----

This works like the [sslcommerz enrol plugin](https://docs.moodle.org/en/sslcommerz_enrolment), but instead of restricting the full course, you can restrict individual activities, resources or sections (and you can combine it with other availability conditions, for example, to exclude some group from paying using an "or" restriction set).

For each restriction you add, you can set a business email address, cost, currency, item name and item number.

