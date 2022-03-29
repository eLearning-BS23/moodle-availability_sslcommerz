<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Strings for component 'availability_sslcommerz', language 'en', version '3.8'.
 *
 * @package     availability_sslcommerz
 * @category    string
 * @copyright  2021 Brain station 23 ltd <>  {@link https://brainstation-23.com/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'SSLCommerz';
$string['title'] = 'SSLCommerz Payment';
$string['prod_environment'] = 'Production environment';
$string['prod_environment_desc'] = 'KEEP IT FALSE IF YOU RUN FROM LOCAL PC';

$string['transactionsreport'] = 'SSLCommerz availability payments';
$string['sscoommerzaccepted'] = 'SSLCommerz payments accepted';
$string['sendpaymentbutton'] = 'Send payment via SSLCommerz';
$string['ajaxerror'] = 'Error contacting server';
$string['apiurl'] = 'Api Url';
$string['apiurl_desc'] = 'sslcommerz Api v3 url';
$string['businessemail'] = 'Business email';
$string['businessemail_desc'] = 'The email address of your business sslcommerz account';
$string['businessstoreid'] = 'Store Id';
$string['businessstoreid_desc'] = 'The Store Id Provided from sslcommerz';
$string['businessstorepassword'] = 'Store Password';
$string['businessstorepassword_desc'] = 'The Store Password Provided from sslcommerz';
$string['continue'] = 'Click here and go back to Moodle';
$string['cost'] = 'Cost';
$string['course'] = 'Course';
$string['currency'] = 'Currency';
$string['description'] = 'Require users to make a payment via SSLCommerz to access the activity or resource.';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during sslcommerz enrolments';
$string['eitherdescription'] = 'You make a <a href="{$a}">payment with SSLCommerz</a>';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the activity or resource availability is valid. If set to zero, the availability duration will be unlimited by default.';
$string['error_businessemail'] = 'You must provide a business email.';
$string['error_cost'] = 'You must provide a cost and it must be greater than 0.';
$string['error_itemname'] = 'You must provide an item name.';
$string['error_itemnumber'] = 'You must provide an item number.';
$string['expiredaction'] = 'Enrolment expiry action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['itemname'] = 'Item name';
$string['itemname_help'] = 'Name of the item to be shown on SSLCommerz form';
$string['itemnumber'] = 'Item number';
$string['messageprovider:payment_error'] = 'Payment errors';
$string['messageprovider:payment_pending'] = 'Pending payments';
$string['mailadmins'] = 'Notify admin';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['notdescription'] = 'You have not sent a <a href="{$a}">payment with SSLCommerz</a>';
$string['paymentcompleted'] = 'Your payment was accepted and now you can access the activity or resource. Thank you.';
$string['paymentinstant'] = 'Use the button below to pay and access the activity or resource.';
$string['paymentpending'] = 'Thank you for your payment! The item that you purchased will be available soon.';
$string['paymentrequired'] = 'You must make a payment via SSLCommerz to access the activity or resource.';
$string['pluginname_desc'] = 'The sslcommerz module allows you to set up paid course resources or activities (like-quiz).  If the cost for any course activity is zero, then students are not asked to pay for the activity or resourse. ';
$string['paymentfail'] = 'Payment was not valid. Please contact with the merchant.';
$string['payment_missmatch'] = 'Payment Amount mismatch.';
$string['error_occured'] = 'JSON Data parsing error!';
$string['error'] = 'FAILED TO CONNECT WITH SSLCOMMERZ API!';
$string['paymentinvalid'] = 'Invalid Information.';
$string['paymentcancel'] = 'Payment Cancelled.';
$string['erripninvalid'] = 'Instant payment notification has not been verified by sslcommerz.';
$string['requestedurl'] = 'Requested Url';
$string['requestedurl_desc'] = 'Requested Url Without parameter';
$string['SSLCommerz:managetransactions'] = 'Manage payment transactions';
$string['SSLCommerz:receivenotifications'] = 'Receive payment notifications';
$string['status'] = 'Allow sslcommerz enrolments';
$string['status_desc'] = 'Allow users to use sslcommerz to attempt activity or use resource of a course by default.';
$string['usermissing'] = 'User {$a} doesn\'t exist';
$string['coursemissing'] = 'Course "{$a}" doesn\'t exist';
$string['paymendue'] = 'Amount paid is not enough "{$a}"?';
$string['notifications'] = 'Payment Successful';
$string['notificationdatahasnotbeenmigrated'] = 'Notification data has not been migrated';
$string['sslcommerzaccepted'] = '';

