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

defined('MOODLE_INTERNAL') || die();

// @codingStandardsIgnoreLine This script does not require login.
require(__DIR__ . '/../../../config.php');
require_once("lib.php");
global $DB, $CFG, $USER, $PAGE;

require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

// Read all the data from PayPal and get it ready for later;
// we expect only valid UTF-8 encoding, it is the responsibility
// of user to set it up properly in PayPal business account
// it is documented in docs wiki.


$tranid = required_param('tran_id', PARAM_TEXT);
$valuec = optional_param('value_c','', PARAM_RAW);
$valueb = required_param('value_b', PARAM_INT);
$banktranid = required_param('bank_tran_id', PARAM_TEXT);
$cardtype = required_param('card_type', PARAM_TEXT);
$valued = required_param('value_d', PARAM_INT);
$valuea = required_param('value_a', PARAM_INT);
$$valid = required_param('val_id', PARAM_RAW);

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

$user = $DB->get_record("user", array("id" => $data->userid), "*", MUST_EXIST);
$course = $DB->get_record("course", array("id" => $data->courseid), "*", MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

$PAGE->set_context($context);
//
//$plugininstance =
//    $DB->get_record("enrol", array("id" => $data->instanceid, "enrol" => "sslcommerz", "status" => 0), "*", MUST_EXIST);
$plugin = availability_get_plugin('sslcommerz');


// Open a connection back to SSLCommerz to validate the data.


$valid = urlencode($$valid);
$storeid = urlencode(get_config('availability_sslcommerz')->sslstoreid);
$storepasswd = urlencode(get_config('availability_sslcommerz')->sslstorepassword);
$requestedurl = (get_config("availability_sslcommerz")->requestedurl . "?val_id=" . $valid . "&store_id=" . $storeid . "&store_passwd=" . $storepasswd . "&v=1&format=json");

$env = get_config('availability_sslcommerz')->prod_environment ?? false;

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $requestedurl);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, $env); // IF YOU RUN FROM LOCAL PC.

$result = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

$result = json_decode($result);


if ($result) {

    if (!empty($SESSION->wantsurl)) {
        $destination = $SESSION->wantsurl;
        unset($SESSION->wantsurl);
    } else {
        $destination = $CFG->wwwroot . "/course/view.php?id=$course->id";
    }

    $fullname = format_string($course->fullname, true, array('context' => $context));


    // Use the queried course's full name for the item_name field.
    $data->item_name = $course->fullname;
    $data->payment_status = $result->status;

    $coursecontext = $PAGE->set_context(context_course::instance());
    switch ($result->status) {
        case 'VALID':
            $DB->insert_record("availability_sslcommerz_tnx", $data);

            break;


        case 'FAILED':
            $data->payment_status = 'Processing';
            redirect($destination, get_string('paymentfail', 'availability_sslcommerz', $fullname));

            break;

        case 'CANCELLED':

            echo "Payment was Cancelled";

            break;

        default:
            echo "Invalid Information.";

            break;
    }
}
