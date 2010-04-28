<?php
class CronMailerShell extends Shell {
	
/**
 * Required models
 * 
 * @var array
 */
	var $uses = array('CronMailer.QueuedEmail');
	
/**
 * Mailer settings, same as the core EmailComponent
 * To overridde, create a file in APP/config/cron_mailer.php like so :
 * $config['CronMailer'] = array(
 * 		'setting' => value
 * );
 * 
 * @var array
 * @link http://book.cakephp.org/view/1284/Class-Attributes-and-Variables
 */
	var $settings = array(
		'charset' => 'utf-8',
		'sendAs' => 'both',
		'delivery' => 'mail',
		'xMailer' => 'CakePHP Email Component',
		'filePaths' => array(),
		'smtpOptions' => array(
			'port'=> 25, 
			'host' => 'localhost', 
			'timeout' => 30
		),
		'messageId' => true,
		'limit' => 50,
	);
	
/**
 * CronMailer component handler
 * 
 * @var object
 */
	var $CronMailer = null;
	
/**
 * Initialize shell
 */
	function initialize() {
		parent::initialize();
		
		if (Configure::load('cron_mailer') !== false) {
			$this->settings = array_merge($this->settings, Configure::read('CronMailer'));
		}
		
		App::import('Component', 'CronMailer.CronMailer');
		
		$this->CronMailer =& new CronMailerComponent();
	}
	
/**
 * Main shell function
 */
	function main() {
		$queue = $this->QueuedEmail->find('all', array('limit' => $this->settings['limit']));
		
		if (empty($queue)) {
			exit;
		}

		$this->CronMailer->_set($this->settings);
		
		foreach ($queue as $email) {
			$this->CronMailer->to               = $email['QueuedEmail']['to'];
			$this->CronMailer->from             = $email['QueuedEmail']['from'];
			$this->CronMailer->replyTo          = $email['QueuedEmail']['replyTo'];
			$this->CronMailer->readReceipt      = $email['QueuedEmail']['readReceipt'];
			$this->CronMailer->return           = $email['QueuedEmail']['return'];
			$this->CronMailer->headers          = unserialize($email['QueuedEmail']['headers']);
			$this->CronMailer->additionalParams = unserialize($email['QueuedEmail']['additionalParams']);
			$this->CronMailer->attachments      = unserialize($email['QueuedEmail']['attachments']);
			$this->CronMailer->subject          = $email['QueuedEmail']['subject'];
			$this->CronMailer->htmlContent      = $email['QueuedEmail']['htmlMessage'];
			$this->CronMailer->textContent      = $email['QueuedEmail']['textMessage'];
			$this->CronMailer->template         = 'dummy'; // required to trigger the _render function in the CronMailerComponent
			
			if ($this->CronMailer->send()) {
				$this->QueuedEmail->delete($email['QueuedEmail']['id']);
			}
			
			$this->CronMailer->reset();
		}
	}
	
/**
 * Overrides _welcome function for silent execution
 */
	function _welcome() {

	}
}