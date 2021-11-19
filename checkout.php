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


global $CFG, $USER;
require_once($CFG->dirroot . '/availability/condition/sslcommerz/lib.php');
/* PHP */

$custom = explode('-', $_POST['custom']);
$courseid = $custom[2];
$postdata = array();
$postdata['store_id'] = get_config('availability_sslcommerz')->sslstoreid;
$postdata['store_passwd'] = get_config('availability_sslcommerz')->sslstorepassword;
$postdata['total_amount'] = $_POST['amount'];
$postdata['currency'] = $_POST['currency_code'];
$postdata['tran_id'] = "MD_COURSE_" . uniqid();
$postdata['success_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/success.php?id=" . $courseid;
$postdata['fail_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/fail.php?id=" . $courseid;
$postdata['cancel_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/cancel.php?id=" . $courseid;
$postdata['ipn_url'] = $CFG->wwwroot . "/availability/condition/sslcommerz/ipn.php?id=" . $courseid;

$postdata['cus_name'] = $_POST['os0'];
$postdata['cus_email'] = $_POST['email'];
$postdata['cus_add1'] = $_POST['address'];
$postdata['cus_add2'] = "";
$postdata['cus_city'] = $_POST['city'];
$postdata['cus_state'] = "";
$postdata['cus_postcode'] = "1000";
$postdata['cus_country'] = $_POST['country'];
$postdata['cus_phone'] = "";
$postdata['cus_fax'] = "";

// OPTIONAL PARAMETERS.
$postdata['value_a'] = $_POST['custom'];
$postdata['value_b'] = $courseid;
$postdata['value_c'] = $_POST['userid'];
$postdata['value_d'] = $_POST['courseid'];

$data = new stdClass();

$data->userid = (int)$_POST['userid'];
$data->courseid = (int)$courseid;
$data->item_name = (int)$_POST['item_name'];
$data->contextid = (int)$custom[2];
$data->sectionid = (int)$custom[3];
$data->instanceid = (int)$_POST['cmid'];
$data->payment_currency = $_POST['currency_code'];
$data->payment_status = 'Pending';
$data->txn_id = $postdata['tran_id'];
$data->timeupdated = time();

$cmid = $_POST['cmid'];
$sectionid = $data->contextid;

if (!$cmid && !$sectionid) {
    print_error('invalidparam');
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
if ($_POST['amount'] == $cost) {
    require_login();
    // REQUEST SEND TO SSLCOMMERZ.
    $directapiurl = get_config("availability_sslcommerz")->apiurl;
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $directapiurl);
    curl_setopt($handle, CURLOPT_TIMEOUT, 30);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($handle, CURLOPT_POST, 1);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // KEEP IT FALSE IF YOU RUN FROM LOCAL PC.
    $content = curl_exec($handle);
    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if ($code == 200 && !(curl_errno($handle))) {
        curl_close($handle);
        $sslcommerzresponse = $content;
    } else {
        curl_close($handle);
        echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
        exit;
    }
// PARSE THE JSON RESPONSE.
    $sslcz = json_decode($sslcommerzresponse, true);
    if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
        // THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
        // echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
        echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
        // ... header("Location: ". $sslcz['GatewayPageURL']);
        exit;
    } else {
        echo "JSON Data parsing error!";
    }
} else {
    echo "Amount not match";
}




