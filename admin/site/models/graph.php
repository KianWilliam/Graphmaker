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
jimport('joomla.application.component.modelitem');
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_graphmaker/tables');

class GraphmakerModelGraph extends JModelItem
{
		protected $item = null;

	public function getTable($type="Graph", $prefix="GraphmakerTable", $config=array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	protected function populateState()
	{
		$app = JFactory::getApplication();
		$pk = $app->input->get('id');
		$this->setState('graph.id', $pk);
		$params = new JRegistry;
		$params->loadString($app->getParams());
		$this->setState('params', $params);
	parent::populateState();

	}
	public function getItem($pk = null)
	{
		
		 if(!isset($this->item))
		 {
			 				$pk = (!empty($pk)) ? $pk : (int) $this->getState('graph.id');
							
							$table = $this->getTable();
				            $table->load($pk);
							$this->item=$table;
							if(!isset($this->item))
							{
								echo "error!";
							}
							else
							{
								$params = new JRegistry;								
								$params->loadString($this->item->params, 'JSON');								
								$this->item->params = $params;
								$params->loadString($this->getState('params'));							    	
							     //true to be recursive just in case
							    $params->merge($this->item->params, true);	
                                $this->item->params = $params;
								}

		 }
			     
				  return $this->item;

	}
}


