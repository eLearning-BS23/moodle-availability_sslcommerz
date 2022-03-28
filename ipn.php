<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * sslcommerz enrolments plugin settings and presets.
 *
 * @package    availability_sslcommerz
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// @codingStandardsIgnoreLine This script does not require login.
require(__DIR__ . '/../../../config.php');
require_once("lib.php");
global $DB, $CFG, $USER, $PAGE;

require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->libdir . '/filelib.php');
set_exception_handler('availability_sslcommerz_ipn_exception_handler');

// Read all the data from sslcommerz and get it ready for later;
// we expect only valid UTF-8 encoding, it is the responsibility
// of user to set it up properly in sslcommerz business account
// it is documented in docs wiki.


$tranid = required_param('tran_id', PARAM_TEXT);
$valuec = optional_param('value_c', '', PARAM_INT);
$valueb = required_param('value_b', PARAM_INT);
$banktranid = required_param('bank_tran_id', PARAM_TEXT);
$cardtype = required_param('card_type', PARAM_TEXT);
$valued = required_param('value_d', PARAM_INT);
$valuea = required_param('value_a', PARAM_INT);
$valid = required_param('val_id', PARAM_INT);

$req = 'cmd=_notify-validate';

$data = new stdClass();
$data->userid = $valuec;
$data->contextid = (int)$valueb;
$data->sectionid = $valuea;
$data->memo = $banktranid;
$data->tax = 0;
$data->payment_status = 'Completed';
$data->txn_id = $tranid;
$data->payment_type = $cardtype;
$data->timeupdated = time();
$data->item_name = $course->fullname ?? 'test';

// Open a connection back to SSLCommerz to validate the data.


$valid = urlencode($valid);
$storeid = urlencode(get_config('availability_sslcommerz')->sslstoreid);
$storepasswd = urlencode(get_config('availability_sslcommerz')->sslstorepassword);
$requestedurl = (get_config("availability_sslcommerz")->requestedurl .
    "?val_id=" . $valid . "&store_id=" . $storeid . "&store_passwd=" . $storepasswd . "&v=1&format=json");

$env = get_config('availability_sslcommerz')->prod_environment ?? false;

$curl = new curl();
$curl->setopt(array(
    'CURLOPT_POST' => 1,
    'CURLOPT_TIMEOUT' => 30,
    'CURLOPT_CONNECTTIMEOUT' => 30,
    'CURLOPT_RETURNTRANSFER' => true,
    'CURLOPT_FOLLOWLOCATION' => true,
    'CURLOPT_SSL_VERIFYPEER' => $env
));
$result = $curl->post($requestedurl, $req);


if (strlen($result) > 0) {
    $fullname = format_string($course->fullname, true, array('context' => $context));

    // Use the queried course's full name for the item_name field.


    $result = json_decode($result);

    if ($result->status == 'VALIDATED' || $result->status == 'VALID') {

        if ($DB->record_exists('availability_sslcommerz_tnx', array('parent_txn_id' => $tranid))) {
            $DB->update_record('availability_sslcommerz_tnx', $data);
        } else {
            $DB->insert_record("availability_sslcommerz_tnx", $data);
        }
        die;
    } else if ($result->status == "FAILED") {
        availability_sslcommerz_message_error(get_string('paymentfail', 'availability_sslcommerz', $fullname), $data);
    } else if ($result->status == "CANCELLED") {
        availability_sslcommerz_message_error(get_string('paymentcancel', 'availavility_sslcommerz'), $data);
    } else {
        availability_sslcommerz_message_error(get_string('paymentinvalid', 'availavility_sslcommerz'), $data);
    }
}


/**
 * Sends message to admin about error
 *
 * @param string $subject
 * @param stdClass $data
 */
function availability_sslcommerz_message_error($subject, $data) {

    $userfrom = core_user::get_noreply_user();
    $recipients = get_users_by_capability(context_system::instance(), 'availability/sslcommerz:receivenotifications');

    if (empty($recipients)) {
        // Make sure that someone is notified.
        $recipients = get_admins();
    }

    $site = get_site();

    $text = "$site->fullname: SSLCommerz transaction problem: {$subject}\n\n";
    $text .= "Transaction data:\n";

    if ($data) {
        foreach ($data as $key => $value) {
            $text .= "* {$key} => {$value}\n";
        }
    }

    foreach ($recipients as $recipient) {
        $message = new \core\message\message();
        $message->component = 'availability_sslcommerz';
        $message->name = 'payment_error';
        $message->userfrom = core_user::get_noreply_user();
        $message->userto = $recipient;
        $message->subject = "SSLCommerz ERROR: " . $subject;
        $message->fullmessage = $text;
        $message->fullmessageformat = FORMAT_PLAIN;
        $message->fullmessagehtml = text_to_html($text);
        $message->smallmessage = $subject;
        message_send($message);
    }
}

/**
 * Silent exception handler.
 *
 * @param Exception $ex
 * @return void - does not return. Terminates execution!
 */
function availability_sslcommerz_ipn_exception_handler($ex) {
    $info = get_exception_info($ex);

    $logerrmsg = "availability_sslcommerz IPN exception handler: " . $info->message;
    if (debugging('', DEBUG_NORMAL)) {
        $logerrmsg .= ' Debug: ' . $info->debuginfo . "\n" . format_backtrace($info->backtrace, true);
    }
    mtrace($logerrmsg);
    exit(0);
}
