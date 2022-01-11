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

if ($ADMIN->fulltree) {

    // Settings.
    $settings->add(new admin_setting_heading('availability_sslcommerz_settings', '',
        get_string('pluginname_desc', 'availability_sslcommerz')));

    $settings->add(new admin_setting_configtext('availability_sslcommerz/apiurl',
        get_string('apiurl', 'availability_sslcommerz'),
        get_string('apiurl_desc', 'availability_sslcommerz'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('availability_sslcommerz/requestedurl',
        get_string('requestedurl', 'availability_sslcommerz'),
        get_string('requestedurl_desc', 'availability_sslcommerz'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('availability_sslcommerz/sslstoreid',
        get_string('businessstoreid', 'availability_sslcommerz'),
        get_string('businessstoreid_desc', 'availability_sslcommerz'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('availability_sslcommerz/sslstorepassword',
        get_string('businessstorepassword', 'availability_sslcommerz'),
        get_string('businessstorepassword_desc', 'availability_sslcommerz'), '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('availability_sslcommerz/prod_environment',
        get_string('prod_environment', 'availability_sslcommerz'),
        get_string('prod_environment_desc', 'availability_sslcommerz'), 'false', PARAM_TEXT));

}
