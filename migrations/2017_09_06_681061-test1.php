<?php
$helper = require_once('base.php');

// Write sql here
// $helper see base.php
$helper->query("ALTER TABLE `salary_handle` ADD COLUMN jb DOUBLE NOT NULL default 0 AFTER `fangb`;");
$helper->query("ALTER TABLE `salary_handle` ADD COLUMN hb DOUBLE NOT NULL default 0 AFTER `jb`;");
$helper->query("ALTER TABLE `salary_handle` ADD COLUMN qqj DOUBLE NOT NULL default 0 AFTER `fanb`;");

// salary_history
$helper->query("ALTER TABLE `salary_history` ADD COLUMN jb DOUBLE NOT NULL default 0 AFTER `fangb`;");
$helper->query("ALTER TABLE `salary_history` ADD COLUMN hb DOUBLE NOT NULL default 0 AFTER `jb`;");
$helper->query("ALTER TABLE `salary_history` ADD COLUMN qqj DOUBLE NOT NULL default 0 AFTER `fanb`;");
