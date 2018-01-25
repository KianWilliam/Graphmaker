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
var graphnums;

var counter = 1;


var type;
var title;
var valuefield;
var fillcolors=[];
var linealpha;
var fillalphas;
var showballoon;
var linecolor;
var bullet;
var dashlength;
var bulletborderalpha;
var bulletalpha;
var bulletsize;
var stackable;
var bulletcolor;
var bulletbordercolor;
var bulletborderthickness;
var balloontext;
var axisvalues=[];
var verticalvalues=[];





function jInsertEditorText(text, editor) {
	var newEl = new Element('span').set('html', text);
	var valeur = newEl.getChildren()[0].getAttribute('src');
	$(editor).value = valeur;
	addthumbnail(valeur, editor);
}


function addgraphmy(graphid, type, title, valuefield,  fillcolors, linealpha, fillalpha, showballoon, linecolor,  bullet, dashlength,  bulletborderalpha, bulletalpha, bulletsize, stackable, bulletcolor, bulletbordercolor, bulletborderthickness, balloontext, axisvalues, verticalvalues)
{
	


	graphid = graphid || counter;
	
	type=type || "line";
	title=title || "(e.g cars, airplanes, ...)";
	valuefield=valuefield || "(e.g cars, airplanes, ...)";
	fillcolors = fillcolors || [1]
	fillcolors[0]= fillcolors[0] || "#0066e3";
	fillcolors[1]= fillcolors[1] || "#802ea9";
	linealpha=linealpha || 0.5;
	fillalpha=fillalpha || 0.8;
	showballoon=showballoon || 1;
	linecolor = linecolor ||  "#7ab7f5";
	bullet = bullet || "round";
	dashlength = dashlength || 2;
	bulletborderalpha = bulletborderalpha || 1;
	bulletalpha = bulletalpha || 1;
	bulletsize = bulletsize || 15;
	stackable = stackable || false;
	bulletcolor = bulletcolor || "#5d7ad9"
	bulletbordercolor = bulletbordercolor || "#ffffff";
	bulletborderthickness = bulletborderthickness || 3;
	balloontext=balloontext || "<div style='margin-bottom:30px;text-shadow: 2px 2px rgba(0, 0, 0, 0.1); font-weight:200;font-size:30px; color:#ffffff'>[[value]]</div>"
	axisvalues = axisvalues || [1];
	verticalvalues = verticalvalues || [1];
		


	var graph = new Element('li', {
		'class': 'mygraph',
		'id': 'mygraph' + counter
	});
	
	graph.set('html', '<div class="mygraphhandle"><div class="mygraphnumber">Graph Number: ' + counter + '</div></div>'+
	'<div class="del"><input name="mygraphdelete' + counter + '" class="mygraphdelete" type="button" value="' + Joomla.JText._('COM_GRAPHMAKER_REMOVE', 'Remove Graph') + '" onclick="javascript:removegraph(this.getParent().getParent());" />'+
	'</div>'+
	'<div class="graph"><input type="hidden" name="graphid" class="graphid" value="'+counter+'"  /></div>'+
	'<div class="graph">Type:<input type="text" name="graphtype" value="'+type+'" class="graphtype" readonly></div>'+
	'<div class="graph">Title:(Title of the product to be evaluated in vertical axis, NO CAPITAL LETTER! eg. cars)<input type="text" name="producttitle" value="'+title+'" class="producttitle" /></div>'+
	'<div class="graph">Value Field:(Name of the product to be evaluated in the vertical axis eg. cars)<input type="text" name="productvalue" value="'+valuefield+'" class="productvalue" /></div>'+
	'<div class="graph">Fill Colors 1:<input type="color" name="fillcolors1" value="'+fillcolors[0]+'" class="fillcolors1" /></div>'+
	'<div class="graph">Fill Colors 2:<input type="color" name="fillcolors2" value="'+fillcolors[1]+'" class="fillcolors2" /></div>'+
	'<div class="graph">Line Alpha:(The float value acts like opacity of graph)<input type="text" name="linealpha" value="'+linealpha+'" class="linealpha" /></div>'+
	'<div class="graph">Fill Alpha:(The float value acts like opacity of graph)<input type="text" name="fillalphas" value="'+fillalpha+'" class="fillalphas" /></div>'+
	'<div class="graph">Show Balloon:(input 1 for Show or 0 for Hide)<input type="text"  name="showballoon"  class="showballoon" value="'+showballoon+'" /></div>'+
	'<div class="graph">Line Color:(The color of graph)<input type="color" name="linecolor" value="'+linecolor+'" class="linecolor" /></div>'+ 
	'<div class="graph">Bullet shape:(eg. none, round, square, rectangle, diamond, triangleUp, triangleDown, triangleLeft, triangleRight, bubble, line, yError, xError)<input type="text" name="bullet" value="'+bullet+'" class="bullet" /></div>'+
	'<div class="graph">Dash Length:(best view with value:2)<input type="text" name="dashlength" value="'+dashlength+'" class="dashlength"/></div>'+
	'<div class="graph">Bullet Border Alpha:(best view with value:1)<input type="text" name="bulletborderalpha" value="'+bulletborderalpha+'" class="bulletborderalpha" /></div>'+
	'<div class="graph">Bullet Alpha:(best view with value:1)<input type="text" name="bulletalpha" value="'+bulletalpha+'" class="bulletalpha" /></div>'+
	'<div class="graph">Bullet Size:(eg. 15)<input type="text" name="bulletsize" value="'+bulletsize+'" class="bulletsize" /></div>'+
	'<div class="graph">Stackable:input true or false(type false for best result):<input type="text"  name="stackable"  class="stackable" value="'+stackable+'" /></div>'+
	'<div class="graph">Bullet Color:<input type="color" name="bulletcolor" value="'+bulletcolor+'" class="bulletcolor" /></div>'+
	'<div class="graph">Bullet Border Color:<input type="color" name="bulletbordercolor" value="'+bulletbordercolor+'" class="bulletbordercolor" /></div>'+
	'<div class="graph">Bullet Border Thickness:(best view with value:3)<input type="text" name="bulletborderthickness" value="'+bulletborderthickness+'" class="bulletborderthickness" /></div>'+
    '<div class="graph">Balloon Text:(Leave it the way it is to have best view)<textarea name="balloontext' + counter + '"  class="balloontext">'+balloontext+'</textarea></div>'+
	'<div class="explanation">You may view all graphs together in one view or just reveal as many as you want, make line alpha equal with 0 so that the grap will not be appeared.</div>'+
	'<div class="graph">To input the data for your graph you may click on addValue Button.<br /><b>Remember:</br> 1-Do not repeat the data for axis value, e.g for year only one 1999, do not input two 1999 for axis value.<br>2-Input Data in axisvalues in order e.g 1999-2000-2001-... do not input 2000-1999-20001 <br /> 3-Data in axis value must be in the range you input under category axis settings, e.g if range is 1999-2005, do not input 2006 in axisvalues <br />4-To reveal negative values in axisvalues do not forget to make parseDates under category axis settings false </b><input name="mygraphvalues' + counter + '" class="mygraphvalues" type="button" value="' + Joomla.JText._('COM_GRAPHMAKER_ADD', 'Add Values') + '" onclick="javascript:addValues(this.getParent().getParent(), 0, 0,this.getParent().getParent().getProperty(\'id\'));" /></div>');
	
	
	document.id('mygraphslist').adopt(graph);
	
	for(var l=0; l<axisvalues.length; l++)
	{
		if(axisvalues.length>1)
		{
					

		 addValues(graph, axisvalues[l], verticalvalues[l], graph.getProperty('id'));
		}
	}

	storegraph();
	makesortables();
	SqueezeBox.initialize({});
	SqueezeBox.assign(graph.getElement('a.modal'), {
		parse: 'rel'
	});	

	counter++;
}
function addValues(el, axisvalue, verticalvalue, lid)
{

		
	   var vals = new Element('div', {'id':'vholders'+lid, 'class':'valueholders'});
	vals.set('html', '<div class="exp">Axis value for year e.g: 1999 , vertical value for sold cars e.g: 121</div><div class="graph" title="(e.g input year: 1999)"><label>Axis Value:</label><input type="text" name="axisvalue" value="'+axisvalue+'" class="axisvalue"/></div>'+
       '<div class="graph" title="(e.g sold car value for 1999, input: 121)"><label>Vertical Value:</label><input type="text" name="verticalvalue" value="'+verticalvalue+'" class="verticalvalue"/></div>'+
       '<div class="graph" style="padding-bottom:5px;"><input name="mygraphremovevalues" class="mygraphremovevalues" type="button" value="' + Joomla.JText._('COM_GRAPHMAKER_REMOVE', 'Remove') + '" onclick="javascript:removeValues(this.getParent().getParent());" /></div>');
	 	document.id(lid).adopt(vals);
			storegraph();
     makesortables();
	SqueezeBox.initialize({});
	SqueezeBox.assign(el.getElement('a.modal'), {
		parse: 'rel'
	});	
		
}
function removeValues(el)
{
	el.destroy();
	slide['axisvalues']=[1];
    slide['verticalvalues']=[1];

	storegraph();
	
}
function storegraph()
{
   var i = 0;
    var graphs = new Array();
	document.id('mygraphslist').getElements('.mygraph').each(function(el) {
		
		var slide = new Object();
        slide['axisvalues']=[1];
        slide['verticalvalues']=[1];
        slide['fillColors']=[1];

		slide['id'] = el.getElement('.graphid').value;		
		slide['type'] = el.getElement('.graphtype').value;
		slide['title']=el.getElement('.producttitle').value;		
        slide['valueField']=el.getElement('.productvalue').value;	
		slide['fillColors'][0]=el.getElement('.fillcolors1').value;	
        slide['fillColors'][1]=el.getElement('.fillcolors2').value;
		slide['lineAlpha']=el.getElement('.linealpha').value;	
        slide['fillAlphas']=el.getElement('.fillalphas').value;	
        slide['showBalloon']=el.getElement('.showballoon').value;	
        slide['lineColor']=el.getElement('.linecolor').value;	
        slide['bullet']=el.getElement('.bullet').value;
        slide['dashLength']=el.getElement('.dashlength').value;		
        slide['bulletBorderAlpha']=el.getElement('.bulletborderalpha').value;
        slide['bulletAlpha']=el.getElement('.bulletalpha').value;		
        slide['bulletSize']=el.getElement('.bulletsize').value;			
        slide['stackable']=el.getElement('.stackable').value;	
        slide['bulletColor']=el.getElement('.bulletcolor').value;	
        slide['bulletBorderColor']=el.getElement('.bulletbordercolor').value;			
		slide['bulletBorderThickness']=el.getElement('.bulletborderthickness').value;
		slide['balloonText']=el.getElement('.balloontext').value;
		
		//console.log('valueholderslength:'+el.getElements('.valueholders').length);
		//if(el.getElements('.valueholders').length!=0)
		//{
			for(var k=0; k<el.getElements('.valueholders').length; k++)
			{
				slide['axisvalues'][k]=el.getElements('.valueholders')[k].getElement('.axisvalue').value;
				slide['verticalvalues'][k]=el.getElements('.valueholders')[k].getElement('.verticalvalue').value;
						//console.log('allelements:'+el.getElements('.valueholders')[0].getElement('.verticalvalue').value);

			}
		//}
		graphs[i] = slide;

		i++;
	});
							

	graphs = JSON.encode(graphs);
	console.log(graphs);
	
	graphs = graphs.replace(/"/g, "|qq|");
	document.id('mygraphs').value = graphs;
	

}

function makesortables() {
	var sb = new Sortables('mygraphslist', {
		/* set options */
		clone: true,
		revert: true,
		handle: '.mygraphhandle',
		/* initialization stuff here */
		initialize: function() {

		},
		/* once an item is selected */
		onStart: function(el, clone) {
			el.setStyle('background', '#add8e6');
			clone.setStyle('background', '#ffffff');
			clone.setStyle('z-index', '1000');
		},
		/* when a drag is complete */
		onComplete: function(el) {
			el.setStyle('background', '#fff');
			//storesetwarning();
		},
		onSort: function(el, clone) {
			clone.setStyle('z-index', '1000');
		}
	});
}



function removegraph(graph) {
	if (confirm(Joomla.JText._('COM_GRAPHMAKER_REMOVE', 'Remove this graph') + ' ?')) {
		graph.destroy();
		counter--;
		storegraph();
	}
}

function callgraphs() {
	
	var graphs = JSON.decode(document.id('mygraphs').value.replace(/\|qq\|/g, "\""));

	if (graphs) {
		graphs.each(function(slide) {
			addgraphmy(slide['id'],
			        slide['type'],
					slide['title'],
					slide['valueField'],
					slide['fillColors'],
					slide['lineAlpha'],
					slide['fillAlphas'],
					slide['showBalloon'],
					slide['lineColor'],
					slide['bullet'],
					slide['dashLength'],
					slide['bulletBorderAlpha'],
					slide['bulletAlpha'],
					slide['bulletSize'],
					slide['stackable'],
					slide['bulletColor'],
                    slide['bulletBorderColor'],
					slide['bulletThickness'],
					slide['balloonText'],
				    slide['axisvalues'],
					slide['verticalvalues']
					);
		});
		
	}
	
}


window.addEvent('domready', function() {
	
	callgraphs();
	var script = document.createElement("script");
	script.setAttribute('type', 'text/javascript');
	script.text = "Joomla.submitbutton = function(task){"
			+ "storegraph();"
			+ "if (task == 'graph.cancel' || document.formvalidator.isValid(document.id('graph-form'))) {	Joomla.submitform(task, document.getElementById('graph-form'));"
			+ "if (self != top) {"
			+ "window.top.setTimeout('window.parent.SqueezeBox.close()', 1000);"
			+ "}"
			+ "} else {"
			+ "alert('Formulaire invalide');"
			+ "}}";
	document.body.appendChild(script);
});
