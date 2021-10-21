<?php
 This file is part of Moodle - http://moodle.org/
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
 * Version info.
 *
 * @package    availability_sslcommerz
 * @copyright  2021 Brain station 23 ltd <>  {@link https://brainstation-23.com/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// You must use the right namespace (matching your plugin component name).
namespace availability_sslcommerz;

defined('MOODLE_INTERNAL') || die();

class condition extends \core_availability\condition {
// Any data associated with the condition can be stored in member
// variables. Here's an example variable:
protected $allow;

public function __construct($structure) {
// Retrieve any necessary data from the $structure here. The
// structure is extracted from JSON data stored in the database
// as part of the tree structure of conditions relating to an
// activity or section.
// For example, you could obtain the 'allow' value:
$this->allow = $structure->allow;

// It is also a good idea to check for invalid values here and
// throw a coding_exception if the structure is wrong.
}

public function save() {
// Save back the data into a plain array similar to $structure above.
return (object)array('type' => 'name', 'allow' => $this->allow);
}

public function is_available($not,
\core_availability\info $info, $grabthelot, $userid) {
// This function needs to check whether the condition is true
// or not for the user specified in $userid.

// The value $not should be used to negate the condition. Other
// parameters provide data which can be used when evaluating the
// condition.

// For this trivial example, we will just use $allow to decide
// whether it is allowed or not. In a real condition you would
// do some calculation depending on the specified user.
$allow = $this->allow;
if ($not) {
$allow = !$allow;
}
return $allow;
}

public function get_description($full, $not, \core_availability\info $info) {
// This function just returns the information that shows about
// the condition on editing screens. Usually it is similar to
// the information shown if the user doesn't meet the
// condition (it does not depend on the current user).
$allow = $not ? !$this->allow : $this->allow;
return $allow ? 'Users are allowed' : 'Users not allowed';
}

protected function get_debug_string() {
// This function is only normally used for unit testing and
// stuff like that. Just make a short string representation
// of the values of the condition, suitable for developers.
return $this->allow ? 'YES' : 'NO';
}
}