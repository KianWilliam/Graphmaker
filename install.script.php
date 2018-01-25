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
defined('_JEXEC') or die();

JLoader::import('joomla.filesystem.folder');
JLoader::import('joomla.filesystem.file');

class GraphmakerInstallerScript
{
	function postflight( $type, $parent )
	{
        if(!JPluginHelper::getPlugin('content', 'graphmaker') && JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'graphmaker' . DIRECTORY_SEPARATOR . 'graphmaker.php'))
		{
			$db = JFactory::getDBO();
			$query = "UPDATE #__extensions SET enabled='1' WHERE element='graphmaker'";
			$db->setQuery($query);
			$db->query();
		}
        
        
    }
}
