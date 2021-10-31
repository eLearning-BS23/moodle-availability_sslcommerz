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

use mod_lti\local\ltiservice\response;


require(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot.'/availability/condition/sslcommerz/lib.php');
require_login($course, true, $cm);

global $CFG, $USER;



//$course = $DB->get_record('course', ['id' => $availability->course]);

//
//$cmid = optional_param('cmid', 0, PARAM_INT);
//$sectionid = optional_param('sectionid', 0, PARAM_INT);
//$paymentid = optional_param('paymentid', null, PARAM_ALPHANUM);
//
//
//$conditions = json_decode($availability->availability);
//$sslcommerz = availability_sslcommerz_find_condition($conditions);
//
//if (is_null($sslcommerz)) {
//    print_error('no sslcommerz condition for this context.');
//}
//
//$course = $DB->get_record('course', ['id' => $availability->course]);




//require_login($course, true, $cm);

$context = \context::instance_by_id($contextid);
$tnxparams = ['userid' => $USER->id, 'contextid' => $contextid, 'sectionid' => $sectionid];

//// payment successful
//if ($DB->record_exists('availability_sslcommerz_tnx', $tnxparams + ['payment_status' => 'Completed'])) {
//    unset($SESSION->availability_sslcommerz->paymentid);
//    redirect($context->get_url(), get_string('paymentcompleted', 'availability_sslcommerz'));
//}
//




redirect('/moodle/course/view.php?id=8', 'successful payment', null, \core\output\notification::NOTIFY_SUCCESS);
//redirect('/moodle/availability/condition/sslcommerz/view.php', 'successful payment', null, \core\output\notification::NOTIFY_SUCCESS);

//$DB->get_record('availability_sslcommerz_tnx', ['id' => $sectionid], 'course, availability', MUST_EXIST);



