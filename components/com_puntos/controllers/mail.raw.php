<?php


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.mail.helper');


class PuntosControllerMail extends JControllerLegacy
{
	public function send()
	{
		JSession::checkToken() or die('Invalid Token');
		$input = JFactory::getApplication()->input;
		$view = $this->getView('mail', 'raw');
		$user = JFactory::getUser();
		$config = JFactory::getConfig();

		$imglink = $input->getString('imglink', null);


		if ($user->id)
		{
			$from = $user->get('email');
			$sender = $user->get('name');
		}
		else
		{
			$from = $input->getString('sender-email', null);
			$sender = $input->getString('sender', null);
		}

		$mailto = $input->getString('mailto', null);
		$subject = $input->getString('subject', null);
		$bodytext = $input->getString('bodytext', null);

		if (!JMailHelper::isEmailAddress($mailto) || !JMailHelper::isEmailAddress($from))
		{
			$view->setError(JText::_('COM_PUNTOS_INVALID_EMAIL'));
			$view->setLayout('error');
			$view->display();
			return false;
		}


		if (!$this->validate($imglink))
		{
			$view->setError(JText::_('COM_PUNTOS_SOMETHING_WAS_WRONG_IMGLINK'));
			$view->setLayout('error');
			$view->display();
			return false;
		}

		$message = '<p>' . JText::sprintf('COM_PUNTOS_MAP_EXCERPT_SENT',
				JURI::base(),
				$config->get('sitename'),
				$from,
				$sender) . '</p>';
		$message .= '<p><img src="' . $this->preventXSS($imglink) . '" /></p>';
		$message .= '<p>' . JText::_('COM_PUNTOS_MESSAGE') . ':</p>';
		$message .= '<p>' . $bodytext . '</p>';
		$message .= '<p>' . JText::_('COM_PUNTOS_URL_IMAGE')
			. ':<br />' . $this->preventXSS($imglink) . '</p>';

		$mailer = JFactory::getMailer();
		if ($mailer->sendMail($from, $sender, $mailto, $subject, $message, true))
		{
			$view->setLayout('mailsent');
			$view->display();
		}
		else
		{
			$view->setError(JText::_('COM_PUNTOS_SOMETHING_WAS_WRONG_EMAIL_NOT_SENT'));
			$view->setLayout('error');
			$view->display();
		}
	}


	public function validate($data)
	{

	
		$data = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x19])/', '', $data);

		
		$search = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/ie';
		$data = preg_replace($search, "chr(hexdec('\\1'))", $data);
		$search = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/ie';
		$data = preg_replace($search, "chr('\\1')", $data);

	
		$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

		foreach ($ra1 as $ra1word)
		{
			if (stripos($data, $ra1word) !== false)
			{
				return false;
			}
		}
		return true;
	}

	/**
	 *
	 * @param type $data
	 *
	 * @return type
	 */
	public function preventXSS($data)
	{
		return htmlentities($data, ENT_QUOTES, 'UTF-8');
	}

}
