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
 * Upgrade code for install
 *
 * @package   availability_sslcommerz
 * @copyright  2021 Brain station 23 ltd <>  {@link https://brainstation-23.com/}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * upgrade this availability condition
 * @param int $oldversion The old version of the assign module
 * @return bool
 */
function xmldb_availability_sslcommerz_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2020022000) {

        // Define field sectionid to be added to availability_sslcommerz_tnx.
        $table = new xmldb_table('availability_sslcommerz_tnx');
        $field = new xmldb_field('sectionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'contextid');

        // Conditionally launch add field sectionid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Sslcommerz savepoint reached.
        upgrade_plugin_savepoint(true, 2020022000, 'availability', 'sslcommerz');
    }
    return true;
}
