<?php
App::import('Component', 'Email');

class MailerComponent extends EmailComponent {

/**
 * HTML content
 * 
 * @var string
 */
	var $htmlContent = '';
	
/**
 * Text content
 * 
 * @var string
 */
	var $textContent = '';
	
/**
 * Render the contents using already processed html and/or text content.
 *
 * @return array Email ready to be sent
 * @access private
 */
	function _render() {
		if ($this->sendAs === 'both') {
			if (!empty($this->attachments)) {
				$msg[] = '--' . $this->__boundary;
				$msg[] = 'Content-Type: multipart/alternative; boundary="alt-' . $this->__boundary . '"';
				$msg[] = '';
			}
			$msg[] = '--alt-' . $this->__boundary;
			$msg[] = 'Content-Type: text/plain; charset=' . $this->charset;
			$msg[] = 'Content-Transfer-Encoding: 7bit';
			$msg[] = '';

			$msg = array_merge($msg, explode("\n", $this->textContent));

			$msg[] = '';
			$msg[] = '--alt-' . $this->__boundary;
			$msg[] = 'Content-Type: text/html; charset=' . $this->charset;
			$msg[] = 'Content-Transfer-Encoding: 7bit';
			$msg[] = '';

			$msg = array_merge($msg, explode("\n", $this->htmlContent));
			
			$msg[] = '';
			$msg[] = '--alt-' . $this->__boundary . '--';
			$msg[] = '';

			return $msg;
		}

		if (!empty($this->attachments)) {
			if ($this->sendAs === 'html') {
				$msg[] = '';
				$msg[] = '--' . $this->__boundary;
				$msg[] = 'Content-Type: text/html; charset=' . $this->charset;
				$msg[] = 'Content-Transfer-Encoding: 7bit';
				$msg[] = '';
			} else {
				$msg[] = '--' . $this->__boundary;
				$msg[] = 'Content-Type: text/plain; charset=' . $this->charset;
				$msg[] = 'Content-Transfer-Encoding: 7bit';
				$msg[] = '';
			}
		}
		
		$varName = $this->sendAs . 'Content';

		$msg = array_merge($msg, explode("\n", $this->$varName));

		return $msg;
	}
}