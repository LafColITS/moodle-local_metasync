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
 * Observers definition for local_metasync.
 *
 * @package    local_metasync
 * @copyright  2014 Willy Lee (wlee@carleton.edu)
 * @copyright  2014 Paul Holden (pholden@greenhead.ac.uk)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = array(

    array(
        'eventname' => '\core\event\user_enrolment_created',
        'callback'  => '\local_metasync\observers::user_enrolment_created',
    ),

    array(
        'eventname' => '\core\event\user_enrolment_deleted',
        'callback'  => '\local_metasync\observers::user_enrolment_deleted',
    ),

    array(
        'eventname' => '\core\event\user_enrolment_updated',
        'callback'  => '\local_metasync\observers::user_enrolment_updated',
    ),
);
