Meta-course synchronization to groups
=========================================

[![Build Status](https://api.travis-ci.org/LafColITS/moodle-local_metasync.png)](https://api.travis-ci.org/LafColITS/moodle-local_metasync)

Metasync creates and maintains groups in metacourses that reflect the enrollment of the linked courses.

Requirements
------------
- Moodle 3.2 (build 2016120500 or later)

Installation
------------
Copy the metasync folder into your Moodle /local directory and visit your Admin Notification page to complete the installation.

Usage
-----
After installation you may need to synchronize existing meta-course groups, to do this manually run the "Resynchronize meta course groups" [scheduled task](https://docs.moodle.org/32/en/Scheduled_tasks).

Any future amendments to enrollments in 'child' courses will be reflected in 'parent' course groups.

## Acknowledgements

This is plugin is based on [local_metagroup]( https://github.com/paulholden/moodle-local_metagroups) by Paul Holden.

Authors
------

- Willy Lee (wlee@carleton.edu)
- Charles Fulton (fultonc@lafayette.edu)