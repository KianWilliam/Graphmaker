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

use Joomla\Registry\Registry;


class GraphmakerModelCategory extends JModelList
{
	
	protected $_item = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	
	protected $_category = null;

	/**
	 * The list of other graphmaker categories.
	 */
	protected $_categories = null;

	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'ordering', 'a.ordering',
			);
		}

		parent::__construct($config);
	}

	
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{
			if (!isset($this->_params))
			{
				$params = new Registry;
				$item->params = $params;
				$params->loadString($item->params);
			}

			if ($this->getState('load_tags', true))
			{
				$item->tags = new JHelperTags;
				$item->tags->getItemTags('com_graphmaker.graph', $item->id);
			}
		}

		return $items;
	}

	
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($this->getState('list.select', 'a.*'))
			->from($db->quoteName('#__graphmaker') . ' AS a');
			//->where('a.access IN (' . $groups . ')');

		if ($categoryId = $this->getState('category.id'))
		{
			$query->where('a.catid = ' . (int) $categoryId)
				->join('LEFT', '#__categories AS c ON c.id = a.catid');
				//->where('c.access IN (' . $groups . ')');
		}

		$state = $this->getState('filter.published');

		if (is_numeric($state))
		{
			$query->where('a.published = ' . (int) $state);
		}
		else
		{
			$query->where('(a.published IN (0,1,2))');
		}

		$nullDate = $db->quote($db->getNullDate());
		$date = JFactory::getDate();
		$nowDate = $db->quote($date->format($db->getDateFormat()));

		

		$search = $this->getState('list.filter');

		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(a.title LIKE ' . $search . ')');
		}



		$query->order($db->escape($this->getState('list.ordering', 'a.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_graphmaker');
		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));

		$orderCol = $app->input->get('filter_order', 'ordering');

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'ordering';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->input->get('filter_order_Dir', 'ASC');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		$id = $app->input->get('id', 0, 'int');
		$this->setState('category.id', $id);

		$user = JFactory::getUser();

		// Load the parameters.
		$this->setState('params', $params);
	}

	
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new Registry;

			if ($active)
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_items', 1) || $params->get('show_empty_categories', 0);
			$categories = JCategories::getInstance('Graphmaker', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			if (is_object($this->_item))
			{
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;

				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}

				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}


	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_parent;
	}

	
	public function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_leftsibling;
	}

	
	public function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_rightsibling;
	}

	
	public function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_children;
	}

	
	public function hit($pk = 0)
	{
		$input    = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk    = (!empty($pk)) ? $pk : (int) $this->getState('category.id');
			$table = JTable::getInstance('Category', 'JTable');
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}
}
