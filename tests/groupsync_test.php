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
 * Unit tests for the local_metasync plugin.
 *
 * @package    local_metasync
 * @category   test
 * @copyright  2018 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

class local_metasync_groupsync_testcase extends advanced_testcase {
    public function test_groupsync() {
        global $DB;

        // Housekeeping.
        $this->setAdminUser();
        $this->resetAfterTest(true);

        // Enable meta enrollments.
        $enabled = enrol_get_plugins(true);
        $enabled['meta'] = true;
        $enabled = array_keys($enabled);
        set_config('enrol_plugins_enabled', implode(',', $enabled));

        // First scenario: two child courses and a parent course.
        $child1 = $this->getDataGenerator()->create_course(array('shortname' => 'Child1'));
        $child2 = $this->getDataGenerator()->create_course(array('shortname' => 'Child2'));
        $parent = $this->getDataGenerator()->create_course();

        // Prep for manual enrollments.
        $manual = enrol_get_plugin('manual');
        $manual1 = $DB->get_record('enrol', array('courseid' => $child1->id, 'enrol' => 'manual'), '*', MUST_EXIST);
        $manual2 = $DB->get_record('enrol', array('courseid' => $child2->id, 'enrol' => 'manual'), '*', MUST_EXIST);

        // Add a group to each child course.
        $child1group = $this->getDataGenerator()->create_group(array('courseid' => $child1->id, 'name' => 'Group of Four'));
        $child2group = $this->getDataGenerator()->create_group(array('courseid' => $child2->id, 'name' => 'Group of Six'));

        // Create ten users; the first four are enrolled in child1, the last six in child2.
        $users = array();
        for ($u = 1; $u <= 10; $u++) {
            $users["user{$u}"] = $this->getDataGenerator()->create_user(array('username' => "user{$u}"));
            if ($u <= 4) {
                $manual->enrol_user($manual1, $users["user{$u}"]->id);
                $this->getDataGenerator()->create_group_member(array('userid' => $users["user{$u}"]->id, 'groupid' => $child1group->id));
            } else {
                $manual->enrol_user($manual2, $users["user{$u}"]->id);
                $this->getDataGenerator()->create_group_member(array('userid' => $users["user{$u}"]->id, 'groupid' => $child2group->id));
            }
        }

        // Sanity check.
        $this->assertEquals(4, count(groups_get_members($child1group->id)));
        $this->assertEquals(6, count(groups_get_members($child2group->id)));

        // Link the courses.
        $enrol = enrol_get_plugin('meta');
        $enrol->add_instance($parent, array('customint1' => $child1->id));
        $enrol->add_instance($parent, array('customint1' => $child2->id));
        enrol_meta_sync($parent->id);

        // Check the parent groups.
        $parentgroup1id = groups_get_group_by_name($parent->id, 'Child1');
        $parentgroup2id = groups_get_group_by_name($parent->id, 'Child2');
        $this->assertEquals(4, count(groups_get_members($parentgroup1id)));
        $this->assertEquals(6, count(groups_get_members($parentgroup2id)));

        // Test unenrollment.
        $manual->unenrol_user($manual1, $users['user1']->id);
        enrol_meta_sync($parent->id);
        $parentgroup1id = groups_get_group_by_name($parent->id, 'Child1');
        $this->assertEquals(3, count(groups_get_members($parentgroup1id)));

        // Test enrollment in new course.
        $manual->enrol_user($manual2, $users['user1']->id);
        enrol_meta_sync($parent->id);
        $parentgroup1id = groups_get_group_by_name($parent->id, 'Child2');
        $this->assertEquals(7, count(groups_get_members($parentgroup1id)));
    }
}
