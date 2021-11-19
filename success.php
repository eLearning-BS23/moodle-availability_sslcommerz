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

global $CFG, $USER;
require_once($CFG->dirroot.'/availability/condition/sslcommerz/lib.php');
require_login();

global $CFG, $USER;
$custom =$_POST['value_a'];
$custom = explode('-', $custom);
$data = new stdClass();
$data->userid = $_POST['value_c'];
$data->contextid = (int)$_POST['value_b'];
$data->sectionid = $custom[3];
$data->memo = $_POST['bank_tran_id'];
$data->tax = 0;
$data->payment_status = 'Completed';
$data->txn_id = $_POST['tran_id'];
$data->payment_type = $_POST['card_type'];
$data->timeupdated = time();


$DB->insert_record("availability_sslcommerz_tnx", $data);


redirect($CFG->wwwroot . '/course/view.php?id='.$_POST['value_d'], 'successful payment', null, \core\output\notification::NOTIFY_SUCCESS);


