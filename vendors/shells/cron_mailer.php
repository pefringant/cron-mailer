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
 * Mailer component handler
 * 
 * @var object
 */
	var $Mailer = null;
	
/**
 * Initialize shell
 */
	function initialize() {
		parent::initialize();
		
		if (Configure::load('cron_mailer') !== false) {
			$this->settings = array_merge($this->settings, Configure::read('CronMailer'));
		}
		
		App::import('Component', 'CronMailer.Mailer');
		
		$this->Mailer =& new MailerComponent();
	}
	
/**
 * Main shell function
 */
	function main() {
		$queue = $this->QueuedEmail->find('all', array('limit' => $this->settings['limit']));
		
		if (empty($queue)) {
			exit;
		}

		$this->Mailer->_set($this->settings);
		
		$this->Mailer->template = 'dummy'; // required to trigger the _render function in the MailerComponent
		
		foreach ($queue as $email) {
			$this->Mailer->to               = $email['QueuedEmail']['to'];
			$this->Mailer->from             = $email['QueuedEmail']['from'];
			$this->Mailer->replyTo          = $email['QueuedEmail']['replyTo'];
			$this->Mailer->readReceipt      = $email['QueuedEmail']['readReceipt'];
			$this->Mailer->return           = $email['QueuedEmail']['return'];
			$this->Mailer->headers          = unserialize($email['QueuedEmail']['headers']);
			$this->Mailer->additionalParams = unserialize($email['QueuedEmail']['additionalParams']);
			$this->Mailer->attachments      = unserialize($email['QueuedEmail']['attachments']);
			$this->Mailer->subject          = $email['QueuedEmail']['subject'];
			$this->Mailer->htmlContent      = $email['QueuedEmail']['htmlMessage'];
			$this->Mailer->textContent      = $email['QueuedEmail']['textMessage'];
			
			if ($this->Mailer->send()) {
				$this->QueuedEmail->delete($email['QueuedEmail']['id']);
			}
			
			$this->Mailer->reset();
		}
	}
	
/**
 * Overrides _welcome function for silent execution
 */
	function _welcome() {

	}
}