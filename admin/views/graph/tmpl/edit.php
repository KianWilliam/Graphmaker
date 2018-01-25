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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$graphID = $this->item->id;
$document = JFactory::getDocument();
$backstyles = "
.view-graph  label
{
	width:242px !important;	
}
";
$document->addStyleDeclaration($backstyles);
?>
<script type="text/javascript">
	/*Joomla.submitbutton = function(task)
	{
		if (task == 'cubebanner.cancel' || document.formvalidator.isValid(document.id('cubebanner-form'))) {
			Joomla.submitform(task, document.getElementById('cubebanner-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_CUBEGALLERY_VALIDATION_FORM_FAILED'));?>');
		}
	}*/
</script>
<form action="<?php echo JRoute::_('index.php?option=com_graphmaker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="graph-form" class="form-validate">
               <div class="width-60 fltlft">
				    <fieldset class="adminform">
						<legend><?php echo empty($this->item->id) ? JText::_('COM_GRAPHMAKER_NEW') : JText::sprintf('COM_GRAPHMAKER_GRAPH_SETTINGS', $this->item->id); ?></legend>
						<ul class="adminformlist">
								<li><?php echo $this->form->getLabel('title'); ?>
				                <?php echo $this->form->getInput('title'); ?></li>

				                <li><?php echo $this->form->getLabel('alias'); ?>
				                <?php echo $this->form->getInput('alias'); ?></li>
				                
				                <li><?php echo $this->form->getLabel('published'); ?>
				                <?php echo $this->form->getInput('published'); ?></li>				             

				                <li><?php echo $this->form->getLabel('id'); ?>
				                  <?php echo $this->form->getInput('id'); ?></li>
								  
								  <li><?php echo $this->form->getLabel('catid'); ?>
				                  <?php echo $this->form->getInput('catid'); ?></li>
						</ul>
						
					</fieldset>
			   </div>
			   <div class="width-40 fltrt">
			   <?php
				   $fieldSets = $this->form->getFieldsets('params');
                       foreach ($fieldSets as $name => $fieldSet) :
	                      echo JHtml::_('sliders.panel', JText::_($fieldSet->label), $name.'-params');
	                          if (isset($fieldSet->description) && trim($fieldSet->description)) :
		                              echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
	                           endif;
	           ?>
	                             <fieldset class="panelform">
		                             <ul class="adminformlist">
		                                 <?php foreach ($this->form->getFieldset($name) as $field) : ?>
			                                <li><?php echo $field->label; ?>
			                                <?php echo $field->input; ?></li>
		                                  <?php endforeach; ?>
		                             </ul>
	                            </fieldset>
                     <?php endforeach; ?>
			   <input type="hidden" name="task" value="" />
	          <?php echo JHtml::_('form.token'); ?>
			   
			   </div>
			   
</form>
