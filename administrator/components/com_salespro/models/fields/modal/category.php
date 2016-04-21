<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

class JFormFieldModal_Category extends JFormField {

	protected $type = 'Modal_Category';
	protected function getInput() {

		// Load language
		JFactory::getLanguage()->load('com_salespro', JPATH_ADMINISTRATOR);

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();

        $script[] = '	function selectItem($id,$name) {';
		$script[] = '		document.getElementById("jform_request_id_id").value = $id;';
        $script[] = '		document.getElementById("jform_request_id_name").value = $name;';
        $script[] = '       SqueezeBox.close();';
		$script[] = '		return false;';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_salespro&amp;view=categories&amp;layout=modal&tmpl=component';

		if ((int) $this->value > 0)	{
			$db	= JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('name'))
				->from($db->quoteName('#__spr_categories'))
				->where($db->quoteName('id') . ' = ' . (int) $this->value);
			$db->setQuery($query);

			try	{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)	{
				JError::raiseWarning(500, $e->getMessage());
			}
		}

		if (empty($title))
		{
			$title = JText::_('Select a category');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active article id field.
		if (0 == (int) $this->value) {
			$value = '';
		} else {
			$value = (int) $this->value;
		}

		// The current article display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="'.JHtml::tooltipText('COM_SPR_CHANGE_CATEGORY').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JSELECT').'</a>';


		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return implode("\n", $html);
	}
}