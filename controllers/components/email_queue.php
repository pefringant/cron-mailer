<?php
App::import('Component', 'Email');

class EmailQueueComponent extends EmailComponent {
	
/**
 * QueuedEmail Model handler
 * 
 * @var object
 */
	var $QueuedEmail = null;
	
/**
 * Initialize component
 * 
 * @param object $controller Instantiating controller
 * @param array $settings Component settings
 */
	function initialize(&$controller, $settings = array()) {
		parent::initialize($controller, $settings);
		
		$this->delivery = 'db';
		
		$this->QueuedEmail = ClassRegistry::init('CronMailer.QueuedEmail');
	}
	
/**
 * Saves email data in the database
 * 
 * @param mixed $content Either an array of text lines, or a string with contents
 * @param string $template Template to use when sending email
 * @param string $layout Layout to use to enclose email body
 * @return boolean Success 
 */
	function _db($content = null, $template = null, $layout = null) {
		// Process mail content
		if ($template) {
			$this->template = $template;
		}

		if ($layout) {
			$this->layout = $layout;
		}

		if (is_array($content)) {
			$content = implode("\n", $content) . "\n";
		}

		$this->htmlMessage = $this->textMessage = null;
		if ($content) {
			if ($this->sendAs === 'html') {
				$this->htmlMessage = $content;
			} elseif ($this->sendAs === 'text') {
				$this->textMessage = $content;
			} else {
				$this->htmlMessage = $this->textMessage = $content;
			}
		}

		$message = $this->_wrap($content);

		if ($this->template === null) {
			$message = $this->_formatMessage($message);
		} else {
			$message = $this->_render($message);
		}
		
		// Save in db
		$data = array(
			'from'             => $this->from,
			'replyTo'          => $this->replyTo,
			'readReceipt'      => $this->readReceipt,
			'return'           => $this->return,
			'headers'          => serialize($this->headers),
			'additionalParams' => serialize($this->additionalParams),
			'attachments'      => serialize($this->attachments),
			'subject'          => $this->subject,
			'textMessage'      => $this->textMessage,
			'htmlMessage'      => $this->htmlMessage,
		);
		
		if (is_array($this->to)) {
			foreach ($this->to as $to) {
				$all[] = array_merge(array('to' => $to), $data);
			}
			
			return $this->QueuedEmail->saveAll($all);
		} else {
			$data = array_merge(array('to' => $this->to), $data);
			
			$this->QueuedEmail->create($data);
			
			return $this->QueuedEmail->save() == true;
		}
	}
	
/**
 * Removes all queued emails from queue
 * 
 * @param boolean $reset If true, truncates queue table. If false, 
 * deletes all records but does not reset the count of the auto-incrementing primary key.
 */
	function clearQueue($reset = true) {
		if ($reset) {
			return $this->QueuedEmail->truncate();
		}
		
		return $this->QueuedEmail->deleteAll('1=1');
	}
}