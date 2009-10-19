<?php
/**
 * Controller for CMS home page
 *
 * This file will render views from views/dashboard/
 *
 * PHP versions 4 and 5
 *
 * Copyright 2008, Vital Effect
 * Written by Lane Olson
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2008, Lane Olson
 * @version			1.0
 * @lastmodified	$Date: 2008-09-17 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class AnalyticsController extends AppController
{
	var $name = 'Analytics';
	var $uses = array();
	var $helpers = array('Time');
	
	var $Analytics = array();

	function beforeFilter() 
	{
		//App::import('ConnectionManager');
		//$this->Analytics =& ConnectionManager::getDataSource('analytics');
		parent::beforeFilter();
		
	}

	function list_profiles() 
	{
		// List all profiles associated with your account
		$profiles = $this->Analytics->listSources();
		debug($profiles);
		exit;
	}
    

	function index()
	{
		if(!$this->Session->check('Auth.Website') && $this->Auth->user('group_id') == 5)
		{
			$this->redirect(array('controller' => 'websites'));
		}
		App::import('Vendor', 'ga', array('file' => 'googleanalytics.php'));
		
		// create an instance of the GoogleAnalytics class using your own Google {email} and {password}
		$ga = new GoogleAnalytics('laneolson@gmail.com','blamoholics');
	 
		// set the Google Analytics profile you want to access - format is 'ga:123456';
		$ga->setProfile('ga:10081862');
	 
		// set the date range we want for the report - format is YYYY-MM-DD
		$ga->setDateRange('2009-10-01','2009-10-14');
	 
		// get the report for date and country filtered by Australia, showing pageviews and visits
		$report = $ga->getReport(
			array('dimensions'=>'ga:date',
				'metrics'=>'ga:pageviews,ga:visits',
				'sort'=>'ga:date'
				)
			);
	 
		//print out the $report array
		pr($report);
	}
	
	/*
	* $graph['Serie']['Point'] array of points on graph
	* $graph['Serie']['YLabel']; // array of Y Axis Labels: $graph['Serie']['YLabel'][i]['#text']
	* $graph['XAxisLabel']; // Array of x axis labels: $graph['XAxisLabel'][i]['#text']
	*/
	
	function get_report()
	{
		$profileId = $this->siteSettings['Setting']['analytics_profile_id'];
		if(is_numeric($profileId))
		{		
			$cache_name = $this->__createCachePath($profileId);
			$cache_expires = '+24 hours';
	
			$cache_data = cache($cache_name, null, $cache_expires);
			if (empty($cache_data))
			{
				$report = $this->Analytics->report(array(
				    'profile' => $profileId,
				    'report' => 'Dashboard'
				));
				cache($cache_name, serialize($report), $cache_expires);
			} 
			else 
			{
				$report = unserialize($cache_data);
			}
			return $report;
		}
		return false;
	}
	
	function view($type = null)
	{
		if($report = $this->get_report())
		{
			if($type == 'sources')
			{
				$g = $report['AnalyticsReport']['Report']['Pie'];
				$graph = $this->process_pie($g);
			}
			else if($type == 'debug')
			{
				debug($report);
				exit;
			}
			else
			{
				$g = $report['AnalyticsReport']['Report']['Graph'][0];
				$graph = $this->process_line_graph($g);
			}
		
			$this->set('chartString', $graph);
			$this->render('view', 'ajax');
		}
	}
	
	function get_stats($items)
	{
		$stats = array(); 
		foreach($items as $k=>$item)
		{
			$stats[$item['Message']['#text']] = $item['Item']['SummaryValue']['#text'];
		}
		return $stats;
	}
	
	function process_line_graph($g)
	{		
	
		App::import('Vendor', 'chart', array('file' => 'ofc'.DS.'open-flash-chart.php'));
	
		$graph['Title'] = $g['Serie']['Label']['#text'];
		$graph['Y']['Title'] = $g['Serie']['Label']['#text']; // title
		$graph['X']['Title'] = $g['XAxisTitle']; // X axis title;
		$graph['Y']['Steps'] = sizeOf($g['Serie']['YLabel']);
		$graph['Y']['Max'] = array_pop($g['Serie']['YLabel']);
		$graph['Y']['Max'] = $graph['Y']['Max']['#text'];
		foreach($g['Serie']['Point'] as $point)
		{
			$graph['X']['Labels'][] = date('M d', strtotime($point['Label']['#text']));
			$graph['Data'][] = $point['Value']['#text'];
		}
		
		$chart = new open_flash_chart();
		
		$chart->set_bg_colour( '#FFFFFF' );
		
		$area = new area_hollow();
		$area->set_colour( '#0066cc' );
		$area->set_fill_colour( '#0066cc' );
		$area->set_fill_alpha( 0.2 );
		$area->set_tooltip( '#x_label#<br>#key#: #val#' );
		$area->set_values( $graph['Data'] );
		$area->set_key( $graph['Y']['Title'] , 10 );
		$chart->add_element( $area );
		
		$x_labels = new x_axis_labels();
		$x_labels->set_steps( 7 );
		$x_labels->set_colour( '#A2ACBA' );
		$x_labels->set_labels( $graph['X']['Labels'] );
		
		$x = new x_axis();
		$x->set_colour( '#333333' );
		$x->set_grid_colour( '#cccccc' );
		$x->set_offset( false );
		$x->set_steps(7);
		// Add the X Axis Labels to the X Axis
		$x->set_labels( $x_labels );

		$chart->set_x_axis( $x );
		
		/*
		$x_legend = new x_legend( $graph['X']['Title']['#text'] );
		$x_legend->set_style( '{font-size: 16px; color: #666}' );
		$chart->set_x_legend( $x_legend );
		*/
		
		$y = new y_axis();
		$y->set_colour( '#333333' );
		$y->set_grid_colour( '#cccccc' );
		$y->set_range( 0, $graph['Y']['Max'] , floor($graph['Y']['Max']/$graph['Y']['Steps']));
		$chart->add_y_axis( $y );

		return $chart->toPrettyString();
	}
	
	function process_pie($g)
	{
		App::import('Vendor', 'chart', array('file' => 'ofc'.DS.'open-flash-chart.php'));
		$title = new title( 'Traffic Sources' );
		$data = array();
		for($i = 0; $i < sizeof($g['Label']); $i++)
		{
			$data[] = new pie_value($g['RawValue'][$i]['#text'], $g['Label'][$i]['#text']);
		}
		$pie = new pie();
		$pie->set_start_angle( 35 );
		$pie->set_animate( true );
		$pie->set_tooltip( '#val# of #total#<br>#percent# of 100%' );
		$pie->set_values( $data );

		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->set_bg_colour( '#FFFFFF' );
		$chart->add_element( $pie );

		$chart->x_axis = null;

		return $chart->toPrettyString();
	}
	
	function __createCachePath($id, $ext = '.txt')
	{
		return md5($id).$ext;
	}

}
