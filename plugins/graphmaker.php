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
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_graphmaker/models');





class PlgContentGraphmaker extends JPlugin
{
	protected $db;
	
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		
		$document = JFactory::getDocument();
		$style="
				#chartdiv {
	         width		: 96%;
	         height		: 475px;
	        font-size	: 11px;
           	margin-left: auto;
    margin-right: auto;  
  	margin-top:15px;
}	

   
       .amcharts-graph-fill
	   {
		   filter:url(#blur);
	   }
       .amcharts-cursor-fill {
        filter: url(#shadow);
       }
	   text
	   {
		  text-anchor:start !important; 
		 /* fill:deepskyblue;*/
	   }
	    image {display:none;}
		a[href*='amcharts']{display:none !important;}
		g {visibility:visible;}
		/*rect[class*='amcharts-cursor-fill']{fill:rgb(255,255,255);stroke:#ffffff;fill-opacity:1;stroke-opacity:1;visibility:hidden;}*/
		text[class*='amcharts-zoom-out-label'] tspan {visibility:hidden;}
	  
				";
				$document->addStyleDeclaration($style);
		
        $document->addScript(JURI::Base().'components/com_graphmaker/assets/js/amcharts.js');
        $document->addScript(JURI::Base().'components/com_graphmaker/assets/js/serial.js');
        $document->addScript(JURI::Base().'components/com_graphmaker/assets/js/none.js');
		
		if($context=="mod_graphmaker" || $context=="com_graphmaker.graph")
		{
		
		    $mymodel = JModelLegacy::getInstance('Graph','GraphmakerModel');
			$item = $mymodel->getItem($page);
		

	if($context=='mod_graphmaker')
	{
				$mparams = new JRegistry;								
                $mparams->loadString($item->params, 'JSON');								
				$item->params = $mparams;
				$mparams->loadString($params);
							     //true to be recursive just in case
				$mparams->merge($item->params, true);
                $item->params = $mparams;
	}
	
		
		$graphitems = json_decode(str_replace("|qq|", "\"", $item->params->get('graphs')));
		for($g=0; $g<count($graphitems); $g++)
		{
			$graphitems[$g]->stackable=false;
			$graphitems[$g]->showBalloon=intval($graphitems[$g]->showBalloon);
			$graphitems[$g]->lineAlpha=floatval($graphitems[$g]->lineAlpha);
			$graphitems[$g]->fillAlphas=floatval($graphitems[$g]->fillAlphas);
			$graphitems[$g]->dashLength=intval($graphitems[$g]->dashLength);
			$graphitems[$g]->bulletBorderAlpha=intval($graphitems[$g]->bulletBorderAlpha);
			$graphitems[$g]->bulletAlpha=intval($graphitems[$g]->bulletAlpha);
			$graphitems[$g]->bulletSize=intval($graphitems[$g]->bulletSize);
			$graphitems[$g]->bulletBorderThickness=intval($graphitems[$g]->bulletBorderThickness);
		   $graphitems[$g]->id="g".$graphitems[$g]->id;
		}


		$xvals = [];
		$vvals = [];
		$categoryfield=$item->params->get('graph_category_field');

		$catfieldstartval = $item->params->get('graph_category_field_startvalue');
		$catfieldendval = $item->params->get('graph_category_field_endvalue');
		$catvals = [];
		if(isset($catfieldstartval)&&isset($catfieldendval))
		{
			while($catfieldstartval<=$catfieldendval)
		    {
			$catvals[] = $catfieldstartval++;
		    }
		}
		

		//$producttitles = [];
		for($i=0; $i<count($graphitems); $i++)
		{
			for($k=0; $k<count($graphitems[$i]->axisvalues); $k++)
			{
			  $xvals[$i][$k]=$graphitems[$i]->axisvalues[$k];
			   $vvals[$i][$k]=$graphitems[$i]->verticalvalues[$k];
			}
			//$xvals[$i]=$graphitems[$i]->axisvalues;
			//$vvals[$i]=$graphitems[$i]->verticalvalues;
			$producttitles[] = $graphitems[$i]->title;

		}
			if($item->params->get('show_category_title'))
			{
				$db = JFactory::getDbo();
					
					/*$query = $db->getQuery(true);
					$query->select('a.title')
					      ->from('#__categories AS a')
						  ->join('INNER','#__graphmaker as c ON ( a.id=c.catid )')
						  ->where('c.catid = '.$db->quote($page));*/
				$query->select('a.catid')->from($db->quoteName('#__graphmaker').' AS a')->where('id = '.$db->quote($page));
			$db->setQuery($query);
			$result = $db->loadObjectList();

				$query = $db->getQuery(true);
				$query->select('a.title')->from($db->quoteName('#__categories').' AS a')->where('id = '.$db->quote($result[0]->catid));
			$db->setQuery($query);
			$result = $db->loadObjectList();
		echo "<div style='background-color:#978bd6; color:#fff; width:35%;padding:10px; border-radius:5%'>";
		echo "<strong>Graph(s) Belong to Category:".$result[0]->title." </strong>";
		echo "<div style=''><strong> Graph(s) axis-x value is:".$categoryfield." </strong></div>";
		for($h=0; $h<count($producttitles); $h++)
				echo "<div style=''><strong> Graph(s) axis-y value is:".$producttitles[$h]." </strong></div>";
		echo "</div>";
			}

		$noConflict = "var gm = jQuery.noConflict();";
        $document->addScriptDeclaration($noConflict);
		

				

		$graphvalues = "
			var charData=[];
			var categoryfield = '".$categoryfield."';
			var categoryfieldvalues =".json_encode($catvals).";
			var producttitles = ".json_encode($producttitles).";
			var axisvalues = ".json_encode($xvals).";
            var verticalvalues = ".json_encode($vvals).";
			
			
			
				
				
				//console.log('kingkianwilliam');
				
				for(var i=0; i<categoryfieldvalues.length; i++)
				{
					charData[i] = {}
					charData[i][categoryfield] = categoryfieldvalues[i];
					

					
				}
				
				i=0;
				while(i<producttitles.length)
				{
					k=0;
					
					while(k<charData.length)
					{
						for(var l=0; l<categoryfieldvalues.length; l++)
						{

							for(var l1=0; l1<axisvalues[i].length; l1++){
							if(parseInt(categoryfieldvalues[l])==parseInt(axisvalues[i][l1]) && charData[l][producttitles[i]]==null )
							{

								charData[l][producttitles[i]]=verticalvalues[i][l1];
								charData[l][categoryfield] = axisvalues[i][l1];
									break;
						    
							
							}
							}
							
						}
						k++;
					}
					i++;
				}
				
				
                     var chart =  AmCharts.makeChart('chartdiv', {
						     'type':'".$item->params->get('graph_type')."',
							 'fontFamily':'".$item->params->get('font_family')."',
			                 'autoMargins':".$item->params->get('auto_margins').",
                             'addClassNames':".$item->params->get('addclass_names').",
							 'zoomOutText':'',
							 'defs':{
								 'filter':[
								 {
									 'x':'".$item->params->get('blur_x')."',
						             'y':'".$item->params->get('blur_y')."',
						             'width':'".$item->params->get('blur_width')."',
						             'height':'".$item->params->get('blur_height')."',
						             'id':'blur',
									 'feGaussianBlur':{
								          'in':'".$item->params->get('fegaussian_blur_in')."',
                                          'stdDeviation':'".$item->params->get('fegaussian_blur_std_deviation')."'
									 }									 
								 },
									 {
										 
                                            'id': 'shadow',
                                            'width': '".$item->params->get('shadow_width')."',
                                            'height': '".$item->params->get('shadow_height')."',
                                            'feOffset': {
                                                'result': '".$item->params->get('feoffset_shadow_result')."',
                                                'in': '".$item->params->get('feoffset_shadow_in')."',
                                                'dx': '".$item->params->get('shadow_feoffset_dx')."',
                                                 'dy': '".$item->params->get('shadow_feoffset_dy')."'
                                             },
                                            'feGaussianBlur': {
                                            'result': '".$item->params->get('fegaussian_shadow_result')."',
                                            'in': '".$item->params->get('fegaussian_shadow_in')."',
                                            'stdDeviation': '".$item->params->get('fegaussian_shadow_std_deviation')."'
                                             },
                                             'feColorMatrix': {
                                             'result': '".$item->params->get('fecolor_shadow_result')."',
                                             'type': 'matrix',
                                             'values': '0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 .2 0'
                                             },
                                            'feBlend': {
                                            'in': '".$item->params->get('feblend_shadow_in')."',
                                            'in2': '".$item->params->get('feblend_shadow_in2')."',
                                             'mode': 'normal'
                                             }
										 
									 }
								 ]
							 },
							 
							  'fontSize': 15,
                              'pathToImages': '../amcharts/images/',
                              'dataProvider': charData,
                              'dataDateFormat': 'YYYY',
                              'marginTop': ".$item->params->get('margin_top').",
                              'marginRight': ".$item->params->get('margin_right').",
                              'marginLeft': ".$item->params->get('margin_left').",
                              'autoMarginOffset': ".$item->params->get('automargin_offset').",
                              'categoryField': '".$item->params->get('graph_category_field')."',
                              'categoryAxis': {
                                       'gridAlpha': ".$item->params->get('grid_alpha').",
                                       'axisColor': '".$item->params->get('axis_color')."',
                                       'startOnAxis': ".$item->params->get('starton_axis').",
                                       'tickLength': ".$item->params->get('tick_length').",
                                       'parseDates': ".$item->params->get('parse_dates').",
                                       'minPeriod': 'YYYY'
                                     },
									 
									  'valueAxes': [
                                                     {  
													    'ignoreAxisWidth':".$item->params->get('ignore_axis_width').",
													    'stackType': '".$item->params->get('stack_type')."',
														'gridAlpha': ".$item->params->get('grid_alpha').",
														'axisAlpha': ".$item->params->get('axis_alpha').",
														'inside': ".$item->params->get('graph_inside')."
                                                     }
												],
										'graphs':".json_encode($graphitems).",
										 'chartCursor': { 
										                  'cursorAlpha': 1,
										                  'zoomable': '".$item->params->get('graph_zoomable')."',
														  'cursorColor': '".$item->params->get('cursor_color')."',
														  'categoryBalloonColor': '".$item->params->get('cat_balloon_color')."',
														  'fullWidth': ".$item->params->get('full_width').",
														  'categoryBalloonDateFormat': 'YYYY',
														  'balloonPointerOrientation': '".$item->params->get('balloon_pointer_orientation')."'
														 },
										'balloon': {
                                         'borderAlpha': '".$item->params->get('border_alpha')."',
                                         'fillAlpha': '".$item->params->get('fill_alpha')."',
                                         'shadowAlpha': '".$item->params->get('shadow_alpha')."',
                                         'offsetX': ".$item->params->get('offset_x').",
                                         'offsetY': ".$item->params->get('offset_y')."
                                             }
                                        
					 });
					 
					 chart.addListener('dataUpdated', zoomChart);
				
				function zoomChart(){chart.zoomToIndexes(1, charData.length -1);}
		";
		$document->addScriptDeclaration($graphvalues);

		
       
		if(count($graphitems)==0)
			return false;
		else
		{				
		}  
  
            return true;
		}
	
	}

	
}
