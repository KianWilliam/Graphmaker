<?php 
/*
 * @package component graphmaker for Joomla! 3.x
 * @version $Id: com_graphmaker 1.0.0 2017-9-10 23:26:33Z $
 * @author Kian William Nowrouzian
 * @copyright (C) 2017- Kian William Nowrouzian
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 
 This file is part of graphmaker.
    graphmaker is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    graphmaker is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with graphmaker.  If not, see <http://www.gnu.org/licenses/>. 
*/
?>
<?php 

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldMygraphsmanager extends JFormField {
	
	protected $type = 'mygraphsmanager';
	
	protected function getInput() {

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("JURI='" . JURI::root() . "'");
		$path = 'administrator/components/com_graphmaker/models/fields/mygraphs/';
		JHTML::_('behavior.modal');		
		JHTML::_('stylesheet', $path . 'graphs.css');
		JHTML::_('script', $path . 'graphs.js');
		$html = '<input name="' . $this->name . '" id="mygraphs" type="hidden" value="' . $this->value . '" />'
				. '<input name="myaddgraph" id="myaddgraph"  type="button" value="' . JText::_('COM_GRAPHMAKER_ADD_GRAPH_TEXT') . '"  onclick="javascript:addgraphmy();" />'
				. '<ul id="mygraphslist" style="clear:both;"></ul><p></p>'
				. '<input name="myaddgraph" id="myaddgraph" type="button"  value="' . JText::_('COM_GRAPHMAKER_ADD_GRAPH_TEXT') . '"  onclick="javascript:addgraphmy();" />';

		return $html;
	}
		
	protected function getLabel()
	{
		return $this->label;
	}
}




