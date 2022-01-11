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

require(__DIR__ . '/../../../config.php');


global $CFG, $USER;
require_once($CFG->dirroot . '/availability/condition/sslcommerz/lib.php');
$error = optional_param('error', '', PARAM_TEXT);
$valued = optional_param('value_d', '', PARAM_TEXT);
$error = $error ?? 'Payment cancelled by user';
$url = $CFG->wwwroot . '/?redirect=0';
if ($valued) {
    $url = $CFG->wwwroot . '/availability/condition/sslcommerz/view.php?cmid=' . $valued;
}
redirect($url, $error, null, \core\output\notification::NOTIFY_ERROR);


