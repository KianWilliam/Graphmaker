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
class GraphmakerViewCategory extends JViewCategory
{
		
	
	protected  $extension = 'com_graphmaker';

	protected  $defaultPageTitle = 'COM_GRAPHMAKER_DEFAULT_PAGE_TITLE';
	
	protected $viewName = 'graph';

	

	public function display($tpl = null)
	{
		$this->commonCategoryDisplay();

		foreach ($this->items as $item)
		{
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$temp       = $item->params;
			$item->params = clone $this->params;
			$item->params->merge($temp);

			
		}

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function prepareDocument()
	{
		parent::prepareDocument();

		$menu = $this->menu;
		$id = (int) @$menu->query['id'];

		if ($menu && ($menu->query['option'] != $this->extension || $menu->query['view'] == $this->viewName || $id != $this->category->id))
		{
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] !== 'com_graphmaker' || $menu->query['view'] === 'graph' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => GraphmakerHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$this->pathway->addItem($item['title'], $item['link']);
			}
		}

	}

}