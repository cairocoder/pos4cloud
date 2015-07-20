<?php

require_once ("_inc/header2.php");

$result = $db->query("SELECT * FROM users
					  WHERE user_type IN ('stockuser', 'stockadmin', 'stocksadmin')");

if (mysql_num_rows($result) > 0)
{
	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<p><a href="#">+ Add New User</a></p>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>User Name</th><th>Email</th><th>Password</th><th>Location</th><th>Job</th><th>Type</th><th></th><th></th></tr>';
	while ($row = $db->fetch_array($result))
	{
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$row['username'].'</td>';
		$dataTable .= '<td>'.$row['email'].'</td>';
		$dataTable .= '<td>'.$row['password'].'</td>';
		$dataTable .= '<td>'.userClass::getBranchName($row['loc_id']).'</td>';
		$dataTable .= '<td>'.userClass::getJobName($row['user_id']).'</td>';
		$dataTable .= '<td>'.$row['user_type'].'</td>';
		$dataTable .= '<td><a class="editUser" rel="'.$row['user_id'].'" href="#">Edit</a></td>';
		$dataTable .= '<td><a class="deleteUser" rel="'.$row['user_id'].'" href="#">Delete</a></td>';
		$dataTable .= '</tr>';
	}
}

?>

<h1>User Manager Window</h1>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>