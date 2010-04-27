<?php 
/* SVN FILE: $Id$ */
/* Cames schema generated on: 2010-04-27 10:04:30 : 1272355350*/
class CronMailerSchema extends CakeSchema {
	var $name = 'CronMailer';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $queued_emails = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
		'to' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'from' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'replyTo' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'readReceipt' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'return' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'headers' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'additionalParams' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'attachments' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'subject' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'textMessage' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'htmlMessage' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);
}
?>