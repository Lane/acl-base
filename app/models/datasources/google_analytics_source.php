<?php

/**
 * Copyright 2005-2007, Felix Geisendörfer <felix@thinkingphp.org>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version    1.0 Beta
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * undocumented class
 *
 * @package default
 * @access public
 */
class GoogleAnalyticsSource extends DataSource{
/**
 * Description string for this Database Data Source.
 *
 * @var unknown_type
 */
    var $description = "Google Analytics API";
/**
 * undocumented variable
 *
 * @var unknown
 * @access public
 */
    var $Http = null;
	
	private $_authCode;
	private $_profileId;
	
	private $_endDate;
	private $_startDate;
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
    function __construct($config) {
        parent::__construct($config);
        		//default the start and end date
		$this->_endDate = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
		$this->_startDate = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 31, date("Y")));
    }
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
    function connected() {
        return $this->connected;
    }
/**
 * undocumented function
 *
 * @param unknown $user 
 * @param unknown $pass 
 * @return void
 * @access public
 */
    function login($user = null, $password = null) {
			
		if (empty($user)) {
            extract($this->config);
        }
        if (@empty($user) || @empty($password)) {
            return trigger_error('Please specify a user / password for using this service');
        }
		
		$postdata = array(
            'accountType' => 'GOOGLE',
            'Email' => $user,
            'Passwd' => $password,
            'service' => 'analytics',
            'source' => 'askaboutphp-v01'
        );
		
		$response = $this->_postTo("https://www.google.com/accounts/ClientLogin", $postdata);
		//process the response;
		if ($response) {
			preg_match('/Auth=(.*)/', $response, $matches);
			if(isset($matches[1])) {
				$this->_authCode = $matches[1];
				$this->config['database'] = $user;
				return $this->connected = true;
			}
		}
		return $this->connected = false;
    }
	

	/**
    * Performs the curl calls to the $url specified. 
	*
    * @param string $url
	* @param array $data - specify the data to be 'POST'ed to $url
	* @param array $header - specify any header information
	* @return $response from submission to $url
    */
	private function _postTo($url, $data=array(), $header=array()) {
		
		//check that the url is provided
		if (!isset($url)) {
			return FALSE;
		}
		
		//send the data by curl
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		if (count($data)>0) {
			//POST METHOD
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			$header = array("application/x-www-form-urlencoded");
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		
		$response = curl_exec($ch);
        $info = curl_getinfo($ch);
		
        curl_close($ch);
		
		//print_r($info);
		//print $response;
		if($info['http_code'] == 200) {
			return $response;
		} elseif ($info['http_code'] == 400) {
			throw new Exception('Bad request - '.$response);
		} elseif ($info['http_code'] == 401) {
			throw new Exception('Permission Denied - '.$response);
		} else {
			return FALSE;
		}
		
	}
	
	/**
    * Sets Profile ID
	*
    * @param string $id (format: 'ga:1234')
    */
	public function setProfile($id) {
		//look for a match for the pattern ga:XXXXXXXX, of up to 10 digits 
		if (!preg_match('/^ga:\d{1,10}/',$id)) {
			throw new Exception('Invalid GA Profile ID set. The format should ga:XXXXXX, where XXXXXX is your profile number');
		}
		$this->_profileId = $id; 
		return TRUE;
	}
	
	/**
    * Sets the date range
    * 
    * @param string $startDate (YYYY-MM-DD)
    * @param string $endDate   (YYYY-MM-DD)
    */
	public function setDateRange($startDate, $endDate) {
		//validate the dates
		if (!preg_match('/\d{4}-\d{2}-\d{2}/', $startDate)) {
			throw new Exception('Format for start date is wrong, expecting YYYY-MM-DD format');
		}
		if (!preg_match('/\d{4}-\d{2}-\d{2}/', $endDate)) {
			throw new Exception('Format for end date is wrong, expecting YYYY-MM-DD format');
		}
		if (strtotime($startDate)>strtotime($endDate)) {
			throw new Exception('Invalid Date Range. Start Date is greated than End Date');
		}
		$this->_startDate = $startDate;
		$this->_endDate = $endDate;
		return TRUE;
	}
	
	/**
    * Retrieve the report according to the properties set in $properties
	*
    * @param array $properties
	* @return array
    */
	public function getReport($properties = array()) {
		if (!count($properties)) {
			die ('getReport requires valid parameter to be passed');
			return FALSE;
		}
		
		//arrange the properties in key-value pairing
		foreach($properties as $key => $value){
            $params[] = $key.'='.$value;
        }
		//compose the apiURL string
        $apiUrl = 'https://www.google.com/analytics/feeds/data?ids='.$this->_profileId.'&start-date='.$this->_startDate.'&end-date='.$this->_endDate.'&'.implode('&', $params);
		
		//call the API
		$xml = $this->_callAPI($apiUrl);
		
		//get the results
		if ($xml) {
			$dom = new DOMDocument();
			$dom->loadXML($xml);
			$entries = $dom->getElementsByTagName('entry');
			foreach ($entries as $entry){
				$dimensions = $entry->getElementsByTagName('dimension');
				foreach ($dimensions as $dimension) {
					$dims .= $dimension->getAttribute('value').'~~';
				}

				$metrics = $entry->getElementsByTagName('metric');
				foreach ($metrics as $metric) {
					$name = $metric->getAttribute('name');
					$mets[$name] = $metric->getAttribute('value');
				}
				
				$dims = trim($dims,'~~');
				$results[$dims] = $mets;
				
				$dims='';
				$mets='';
			}
		} else {
			throw new Exception('getReport() failed to get a valid XML from Google Analytics API service');
		}
		return $results;
	}
	
	/**
    * Retrieve the list of Website Profiles according to your GA account
	*
    * @param none
	* @return array
    */
	public function getWebsiteProfiles() {
	
	    if (!$this->connected() && !$this->login()) {
            return false;
        }
		// make the call to the API
		$response = $this->_callAPI('https://www.google.com/analytics/feeds/accounts/default');
		
		//parse the response from the API using DOMDocument.
		if ($response) {
			$dom = new DOMDocument();
			$dom->loadXML($response);
			$entries = $dom->getElementsByTagName('entry');
			foreach($entries as $entry){
				$tmp['title'] = $entry->getElementsByTagName('title')->item(0)->nodeValue;
				$tmp['id'] = $entry->getElementsByTagName('id')->item(0)->nodeValue;
				foreach($entry->getElementsByTagName('property') as $property){
					if (strcmp($property->getAttribute('name'), 'ga:accountId') == 0){
						$tmp["accountId"] = $property->getAttribute('value');
					}    
					if (strcmp($property->getAttribute('name'), 'ga:accountName') == 0){
					   $tmp["accountName"] = $property->getAttribute('value');
					}
					if (strcmp($property->getAttribute('name'), 'ga:profileId') == 0){
						$tmp["profileId"] = $property->getAttribute('value');
					}
					if (strcmp($property->getAttribute('name'), 'ga:webPropertyId') == 0){
						$tmp["webProfileId"] = $property->getAttribute('value');
					}
				}
				$profiles[] = $tmp;
			}
		} else {
			throw new Exception('getWebsiteProfiles() failed to get a valid XML from Google Analytics API service');
		}
		return $profiles;
	}
	
	/**
    * Make the API call to the $url with the $_authCode specified
	*
    * @param url
	* @return result from _postTo
    */
	private function _callAPI($url) {
		return $this->_postTo($url,array(),array("Authorization: GoogleLogin auth=".$this->_authCode));
	}
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
    function listSources() {
        if (!$this->connected() && !$this->login()) {
            return false;
        }
        
        $cache = parent::listSources();
        if ($cache != null) {
            return $cache;
        }

        $sources = array();
        $response = $this->Http->get('https://www.google.com/analytics/home/?et=reset&hl=en-US&ns=100');

        $optionsRegex = '/<option.+?value="([0-9]+)".*?>([^<]+)<\/option>/si';
        preg_match('/<select.+?name="account_list".*?>(.+?)<\/select>/is', $response, $accounts);
        if (empty($accounts)) {
            return false;
        }
        preg_match_all($optionsRegex, $accounts[1], $accounts, PREG_SET_ORDER);
        if (empty($accounts)) {
            return false;
        }

        foreach ($accounts as $i => $account) {
            list(,$id, $name) = $account;
            if (empty($id) || !is_numeric($id)) {
                continue;
            }
            $account = array('Account' => compact('id', 'name'));
            if ($i != 0) {
                $response = $this->Http->get('https://www.google.com/analytics/home/admin?scid='.$id.'&ns=100');
            }
            preg_match('/<select.+?name="profile_list".*?>(.+?)<\/select>/is', $response, $profiles);
            if (empty($profiles)) {
                $account['Profile'] = array();
                continue;
            }
            preg_match_all($optionsRegex, $profiles[1], $profiles, PREG_SET_ORDER);
            foreach ($profiles as $profile) {
                list(,$id, $name) = $profile;
                if (empty($id) || !is_numeric($id)) {
                    continue;
                }
                $account['Profile'][] = compact('id', 'name');
            }
            $sources[] = $account;
        }
        parent::listSources($sources);
        return $sources;
    }
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
    function report($conditions = array(), $returnRaw = false) {
        if (!$this->connected() && !$this->login()) {
            return false;
        }
        
        if (is_int($conditions)) {
            $conditions = array('profile' => $conditions);
        } elseif (is_string($conditions)) {
            $conditions = array('report' => $conditions);
        }

        $defaults = array(
            'profile' => null,
            'report'  => 'Dashboard',
            'from'    => date('Y-m-d', time() - 1 * MONTH),
            'to'      => date('Y-m-d'),
            'query'   => array(),
            'tab'     => 0,
            'format'  => 'xml',
            'compute' => 'average',
            'view'    => 0,
        );
        $conditions = am($defaults, $conditions);
        $formats = array('pdf' => 0, 'xml' => 1, 'csv' => 2, 'tsv' => 3);
        
        foreach (array('from', 'to') as $condition) {
            if (is_string($conditions[$condition])) {
                $conditions[$condition] = strtotime($conditions[$condition]);
            }
        }

        if (!isset($conditions['profile'])) {
            $sources = $this->listSources();
            $conditions['profile'] = $sources[0]['Profile'][0]['id'];
        } elseif (is_string($conditions['profile'])) {
            $sources = $this->listSources();
            foreach ($sources as $source) {
                $profiles = Set::combine($source, 'Profile.{n}.name', 'Profile.{n}.id');
                if (isset($profiles[$conditions['profile']])) {
                    $conditions['profile'] = $profiles[$conditions['profile']];
                    break;
                }
            }
        }

        $query = array(
            'fmt' => isset($formats[$conditions['format']])
                ? $formats[$conditions['format']]
                : $conditions['format'],
            'id' => $conditions['profile'],
            'pdr' => date('Ymd', $conditions['from']).'-'.date('Ymd', $conditions['to']),
            'tab' => $conditions['tab'],
            'cmp' => $conditions['compute'],
            'view' => $conditions['view'],
            'rpt' => $conditions['report'].'Report',
        );
        $query = am($query, $conditions['query']);
        $report = $this->Http->get('https://www.google.com/analytics/reporting/export', $query);

        if ($returnRaw == true || $query['fmt'] != 1) {
            return $report;
        }

        uses('Xml');
        $ReportXml =& new XML($report);
        return $this->xmltoArray($ReportXml);
    }
/**
 * undocumented function
 *
 * @param unknown $node 
 * @return void
 * @access public
 */
    function xmltoArray($node) {
        $array = array();
        foreach ($node->children as $child) {
            if (empty($child->children)) {
                $value = $child->value;
            } else {
                $value = $this->xmltoArray($child);
            }

            $key = $child->name;
            if (!isset($array[$key])) {
                $array[$key] = $value;
            } else {
                if (!is_array($array[$key]) || !isset($array[$key][0])) {
                    $array[$key] = array($array[$key]);
                }
                $array[$key][] = $value;
            }
        }

        return $array;
    }
    
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
    function close() {
        return true;
    }
}

?> 