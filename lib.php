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
 * Support functions
 *
 * @package availability_sslcommerz
 * @copyright  2021 Brain station 23 ltd <>  {@link https://brainstation-23.com/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function availability_sslcommerz_find_condition($conditions) {
    foreach ($conditions->c as $cond) {
        if (isset($cond->c)) {
            return availability_sslcommerz_find_condition($cond);
        } else if ($cond->type == 'sslcommerz') {
            return $cond;
        }
    }
    return null;
    // TODO: handle more than one sslcommerz in same context.
}

/**
 * Extend course navigation to add a link to the transactions report.
 *
 * @param navigation_node $parentnode
 * @param stdClass $course
 * @param context_course $context
 */
function availability_sslcommerz_extend_navigation_course(navigation_node $parentnode, stdClass $course, context_course $context) {

    if (has_capability('availability/sslcommerz:managetransactions', context_system::instance())) {
        $parentnode->add(
            get_string('transactionsreport', 'availability_sslcommerz'),
            new moodle_url('/availability/condition/sslcommerz/transactions.php', ['courseid' => $course->id]),
            null, null, null,
            new pix_icon('i/payment', '')
        );
    }
}
