<?php 

require_once 'vendor/autoload.php';

class EmailBuilder {
	const HTML_HEADER = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Equipo Fisiomer</title><style type="text/css">body{background-color: #f7f7f7;}h1,h2,h3{font-family: "Arial Black", Gadget, sans-serif;color:#31698a;margin:0px;padding: 0px;}article{padding-top: 10px;font-family: "Verdana", Geneva, sans-serif;color:#36648b;text-align: justify;}info{color:#0e2f44;}</style></head><body><h1>Equipo Fisiomer</h1><h3>Rehabilitación</h3>';
	const HTML_FOOTER = '</body></html>';
	const DEBUG_MODE = false;
	const HOST = "mx1.hostinger.mx";
	const USERNAME = "no-responder@fisiomer.com.mx";
	const PASSWORD = "wYrjG3BsY5db";
	const PORT = 465;

	const FROM = "no-responder@fisiomer.com.mx";
	const NAME_FROM = "Equipo Fisiomer";


	private $emails = array();
	private $htmlContent;
	private $plainTextContent;
	private $attachedFiles = array();
	private $subject;

	private $justEmails = true;

	public function __construct($emails = array(), $subject, $htmlBody, $plainTextBody, $attachedFiles = array(), $justEmails = true){
		$this->emails = $emails;
		$this->htmlContent = self::HTML_HEADER.$htmlBody.self::HTML_FOOTER;
		$this->subject = $subject;
		$this->plainTextContent = $plainTextBody;
		$this->attachedFiles = $attachedFiles;
		$this->justEmails = $justEmails;
	}

	public function send(){
		$ret = false;
		if(count($this->emails)>0){

			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			if(self::DEBUG_MODE){
				$mail->SMTPDebug=3;
			}

			$mail->isSMTP();
			$mail->Host = self::HOST;  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = self::USERNAME;                 // SMTP username
			$mail->Password = self::PASSWORD;                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = self::PORT;                                    // TCP port to connect to

			$mail->setFrom(self::FROM, self::NAME_FROM);

			foreach ($this->emails as $index => $val) {
				if($this->justEmails){
					$mail->addAddress($val);
				}else{
					$mail->addAddress($val['correo'], $val['nombre']);
				}				
			}

			if(count($this->attachedFiles)>0){
				foreach ($this->attachedFiles as $key => $file) {
					$mail->addAttachment($file); // $file representa a la ruta del archivo a agregar
				}
			}
			
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $this->subject;
			$mail->Body    = $this->htmlContent;
			$mail->AltBody = $this->plainTextContent;
			if(false /*&& !$mail->send()*/) {
			    if(self::DEBUG_MODE){
			    	echo 'Message could not be sent.';
			     	echo 'Mailer Error: ' . $mail->ErrorInfo;
			    }
			    return false;
			} else {
				if(self::DEBUG_MODE){
					echo 'Message has been sent';
				}
				return true;
			}
		}

		return $ret;
	}


}