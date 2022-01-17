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
defined('MOODLE_INTERNAL') || die();

global $CFG, $USER, $DB;
require_once($CFG->dirroot . '/availability/condition/sslcommerz/lib.php');

global $CFG, $USER;
$tranid = required_param('tran_id', PARAM_TEXT);

if (isset($_POST) && !empty($_POST)) {
    $valuec = optional_param('value_c', '', PARAM_RAW);
    $valueb = required_param('value_b', PARAM_INT);
    $banktranid = required_param('bank_tran_id', PARAM_TEXT);
    $cardtype = required_param('card_type', PARAM_TEXT);
    $valued = required_param('value_d', PARAM_INT);
    $valuea = required_param('value_a', PARAM_INT);

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


    $DB->insert_record("availability_sslcommerz_tnx", $data);

    $url = $CFG->wwwroot . '/?redirect=0';
    if ($valued) {
        $url = $CFG->wwwroot . '/availability/condition/sslcommerz/view.php?cmid=' . $valued;

    }
    redirect($url, 'successful payment', null, \core\output\notification::NOTIFY_SUCCESS);

} else {
    redirect($CFG->wwwroot . '/?redirect=0', 'Something went wrong', null, \core\output\notification::NOTIFY_SUCCESS);

}


