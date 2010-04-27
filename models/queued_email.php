<?php
class QueuedEmail extends CronMailerAppModel
{
	var $name = 'QueuedEmail';
	
	/**
	 * Truncates table queued_emails
	 * 
	 * @return boolean Success
	 */
	function truncate() {
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		
		return $db->truncate('queued_emails');
	}
}