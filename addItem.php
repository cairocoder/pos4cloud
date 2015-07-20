<?php

require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

if (isset($_POST['start']))
{
  unset($errors);
  unset($success);
  define('CSV_PATH', "_uploads/");
  $mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/comma-separated-values');
  if (in_array($_FILES['file']['type'], $mimes)){
    if ($_FILES["file"]["error"] > 0)
    {
      $errors[] = $_FILES["file"]["error"];
    }
    else
    {
      $ext        = explode('.',$_FILES['file']['name']);
      $extension  = $ext[1];
      $newName    = $ext[0].'_'.time();
      $newPath    = CSV_PATH . $newName . '.' . $extension ;
      move_uploaded_file($_FILES['file']['tmp_name'], $newPath);
      $file       = fopen($newPath, "r");
      $numCol     = count(fgetcsv($file));
      if ($numCol == 19)
      {
         while ($curRow  = fgetcsv($file))
         {
            $itemId  = $curRow[0];
            $result  = $db->query("SELECT item_id FROM items WHERE item_id = '{$itemId}'");
            if (mysql_num_rows($result) > 0)
            {
               $duplicates[] = $itemId;
               continue;
            } else {
               $data  = implode("','", $curRow);
               $query = ("INSERT INTO items VALUES ('{$data}')");
               $db->query($query);
            }
         }
         fclose($file);
         $success  = "Done, File imported successfully";
      } else {
         $errors[] = "Error, Incorrect column number";
      }
    }
  } else {
    $errors[] = "Sorry, File type not allowed";
  }
}

?>

<h1>Add Item Window</h1>

<div id="reportWrapper2">
  <form method="post" enctype="multipart/form-data">
      <?php
        if (isset($errors))
        {
          foreach ($errors as $key => $value) {
            echo '<span class="error">' . $value . '</span><br>';
          }
          echo '<br>';
        }
        if (isset($duplicates))
        {
         echo '<span class="error">Duplicate values:</span><br>';
          foreach ($duplicates as $key => $value) {
            echo $value . '<br>';
          }
          echo '<br>';
        }
        if (isset($success)) echo '<span class="success">' . $success . '</span><br><br>';
      ?>
      <table border="0" align="center" style="margin: 0 auto">
        <tr>
        	<td>Source CSV file to import:</td>
        	<td><input type="file" name="file" id="file"></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><br><input type="Submit" name="start" value="Import it"><br><br></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><br><a href="_uploads/template.csv">Download the template<br><br></td>
        </tr>
      </table>
  </form>
</div>