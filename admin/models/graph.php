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
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
class GraphmakerModelGraph extends JModelAdmin
{
	
	
	public function getTable($type = 'Graph', $prefix = 'GraphmakerTable', $config = array())
	{
		$table = JTable::getInstance($type, $prefix, $config);
		return $table;
	}
	public function getItem($pk = null){
		$item = parent::getItem($pk);
		
		if(property_exists($item, "visual") && is_array($item->visual) == false){
			$registry = new JRegistry();
			$registry->loadString($item->visual,'JSON');
			$item->visual = $registry->toArray();						
		}
		
		return($item);
	}
	public function getForm($data = array(), $loadData = true)
	{
		
		jimport('joomla.form.form');
		
		// Get the form.
		$form = $this->loadForm('com_graphmaker.graph', 'graph', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.		
		$data = JFactory::getApplication()->getUserState('com_graphmaker.edit.graph.data', array());
		
		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}
		
		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__graphmaker');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		
	}
	protected function getReorderConditions($table)
	{
		$condition = array();
		return $condition;
	}
	
	
}


