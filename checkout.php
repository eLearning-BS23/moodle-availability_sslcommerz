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
 * sslcommerz availability condition plugin - support for user accessing activity or resource
 *
 * @package    availability_sslcommerz
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/lib/filelib.php');
defined('MOODLE_INTERNAL') || die();

global $CFG, $USER, $DB;
require_once($CFG->dirroot . '/availability/condition/sslcommerz/lib.php');
/* PHP */

$customarray = required_param('custom', PARAM_RAW);
$amount = required_param('amount', PARAM_FLOAT);
$currencycode = required_param('currency_code', PARAM_TEXT);
$os0 = required_param('os0', PARAM_TEXT);
$email = required_param('email', PARAM_TEXT);
$address = required_param('address', PARAM_TEXT);
$city = required_param('city', PARAM_TEXT);
$country = required_param('country', PARAM_TEXT);
$userid = required_param('userid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$currencycode = required_param('currency_code', PARAM_TEXT);


$custom = explode('-', $customarray);
$courseid = $custom[2];
$postdata = array();
$postdata['store_id'] = get_config('availability_sslcommerz')->sslstoreid;
$postdata['store_passwd'] = get_config('availability_sslcommerz')->sslstorepassword;
$postdata['total_amount'] = $amount;
$postdata['currency'] = $currencycode;
$postdata['tran_id'] = "MD_COURSE_" . uniqid();
$postdata['success_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/success.php?id=" . $courseid;
$postdata['fail_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/fail.php?id=" . $courseid;
$postdata['cancel_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/cancel.php?id=" . $courseid;
$postdata['ipn_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/ipn.php?id=" . $courseid;

$postdata['cus_name'] = $os0;
$postdata['cus_email'] = $email;
$postdata['cus_add1'] = $address;
$postdata['cus_add2'] = "";
$postdata['cus_city'] = $city;
$postdata['cus_state'] = "";
$postdata['cus_postcode'] = "1000";
$postdata['cus_country'] = $country;
$postdata['cus_phone'] = "";
$postdata['cus_fax'] = "";

// OPTIONAL PARAMETERS.
$postdata['value_a'] = (int)$custom[3];
$postdata['value_b'] = $courseid;
$postdata['value_c'] = $userid;
$postdata['value_d'] = $cmid;
$data = new stdClass();

$data->userid = (int)$userid;
$data->courseid = (int)$courseid;
$data->contextid = (int)$custom[2];
$data->sectionid = (int)$custom[3];
$data->instanceid = (int)$cmid;
$data->payment_currency = $currencycode;
$data->payment_status = 'Pending';
$data->txn_id = $postdata['tran_id'];
$data->timeupdated = time();

$sectionid = $data->sectionid;

if (!$cmid && !$sectionid) {
    moodle_exception('invalidparam');
}

if ($cmid) {
    $availability = $DB->get_record('course_modules', ['id' => $cmid], 'course, availability', MUST_EXIST);
    $contextid = $DB->get_field('context', 'id', ['contextlevel' => CONTEXT_MODULE, 'instanceid' => $cmid]);
    $urlparams = ['cmid' => $cmid];
} else {
    $availability = $DB->get_record('course_sections', ['id' => $sectionid], 'course, availability', MUST_EXIST);
    $contextid = $DB->get_field('context', 'id', ['contextlevel' => CONTEXT_COURSE, 'instanceid' => $availability->course]);
    $urlparams = ['sectionid' => $sectionid];
}

$conditions = json_decode($availability->availability);
$sslcommerz = availability_sslcommerz_find_condition($conditions);


$cost = format_float($sslcommerz->cost, 2, false);
$env = get_config('availability_sslcommerz')->prod_environment ?? false;
if ($amount == $cost) {
    require_login();
    // REQUEST SEND TO SSLCOMMERZ.
    $directapiurl = get_config("availability_sslcommerz")->apiurl;

    $curl = new curl();
    $curl->setopt(array(
        'CURLOPT_POST' => 1,
        'CURLOPT_TIMEOUT' => 30,
        'CURLOPT_CONNECTTIMEOUT' => 30,
        'CURLOPT_RETURNTRANSFER' => true,
        'CURLOPT_FOLLOWLOCATION' => true,
        'CURLOPT_POSTFIELDS' => $postdata,
        'CURLOPT_SSL_VERIFYPEER' => $env
    ));
    $result = $curl->post($directapiurl, $postdata);

    $code = json_decode($result)->status;

    if ($code == 'SUCCESS') {
        $sslcommerzresponse = $result;
    } else {
        echo get_string('error', 'availability_sslcommerz');
        exit;
    }
    // PARSE THE JSON RESPONSE.
    $sslcz = json_decode($sslcommerzresponse, true);
    if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
        echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
        exit;
    } else {
        echo get_string('error_occured', 'availability_sslcommerz');
    }
} else {
    echo get_string('payment_missmatch', 'availability_sslcommerz');
}




