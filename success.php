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
global $DB, $CFG, $USER, $PAGE;

require_once($CFG->dirroot . '/availability/condition/sslcommerz/lib.php');
require_once("lib.php");


require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

// Read all the data from PayPal and get it ready for later;
// we expect only valid UTF-8 encoding, it is the responsibility
// of user to set it up properly in PayPal business account
// it is documented in docs wiki.

requiere_login();

$tranid = required_param('tran_id', PARAM_TEXT);
$valuec = optional_param('value_c', '', PARAM_INT);
$valueb = required_param('value_b', PARAM_INT);
$banktranid = required_param('bank_tran_id', PARAM_TEXT);
$cardtype = required_param('card_type', PARAM_TEXT);
$valued = required_param('value_d', PARAM_INT);
$valuea = required_param('value_a', PARAM_INT);
$valid = required_param('val_id', PARAM_RAW);

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


$availability = $DB->get_record('course_sections', ['id' => $data->sectionid], 'course, availability', MUST_EXIST);
$contextid = $DB->get_field('context', 'id', ['contextlevel' => CONTEXT_COURSE, 'instanceid' => $availability->course]);
$urlparams = ['sectionid' => $data->sectionid];
$conditions = json_decode($availability->availability);
$course = $DB->get_record('course', ['id' => $availability->course]);


$user = $DB->get_record("user", array("id" => $data->userid), "*", MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

$PAGE->set_context($context);

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
$result = json_decode($result);

if ($result->status == 'VALID') {

    $DB->insert_record("availability_sslcommerz_tnx", $data);

    $url = $CFG->wwwroot . '/?redirect=0';
    if ($valued) {
        $url = $CFG->wwwroot . '/availability/condition/sslcommerz/view.php?cmid=' . $valued;

    }
    redirect($url, get_string('paymentcompleted', 'availability_sslcommerz'), null, \core\output\notification::NOTIFY_SUCCESS);
} else {
    redirect($CFG->wwwroot . '/?redirect=0', get_string('paymentfail',
        'availability_sslcomerz'), null,
        \core\output\notification::NOTIFY_WARNING);
}

