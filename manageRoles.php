<?php
    require_once ("_inc/header2.php");
    $allRoles  = roleClass::getAllRoles();
    $allPerms  = roleClass::getAllPermissions();
    $permTypes = roleClass::getAllPermissionTypes();
?>

<style type="text/css">
	.checkbox-grid li {
		display: block;
		float: left;
		width: 25%;
		text-align: left;
	}
	h2 {
	    clear: both;
	    margin: 5px;
	    padding: 5px 8px;
	    width: 97%;
	    text-align: left;
	}
	td {
		text-align: center;
		border-bottom: 2px solid #000;
		padding-bottom: 20px;
	}
</style>

<h1>Manage Roles Window</h1>

<div id="addNewWrapper">
	<br><br>
	<table id="tblAddNew">
	<tr>
   		<th>Role</th>
   		<th>Permissions</th>
   		<th></th>
	</tr>
	<?php foreach ($allRoles as $key1 => $value1): ?>
	<?php $chkRolePerms = roleClass::getRolePermIds($key1) ?>
	<tr>
		<td><input type="text" name="role" class="role" data-roleId="<?php echo $key1 ?>" value="<?php echo $value1 ?>"></td>
		<td>
			<?php foreach ($permTypes as $key2 => $value2): ?>
				<h2><?php echo $value2 ?>:</h2>
				<ul class="checkbox-grid">
				<?php $permByType = roleClass::getPermissionsByType($key2) ?>
				<?php foreach ($permByType as $key3 => $value3): ?>
					<?php
						if (in_array($key3, $chkRolePerms)) {
							$checked = "checked";
						} else {
							$checked = "";
						}
					?>
					<li><input type="checkbox" name="selPerm" class="selPerm" <?php echo $checked ?> value="<?php echo $key3 ?>"><?php echo $value3 ?></li>
				<?php endforeach; ?>
				</ul>
				<br>
			<?php endforeach; ?>
		</td>
		<td><input type="button" name="updateRole" class="updateRole" data-role="<?php echo $key1 ?>" value="Update Role" ></td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td><input type="text" name="role" class="role" value=""></td>
		<td>
			<?php foreach ($permTypes as $key2 => $value2): ?>
				<h2><?php echo $value2 ?>:</h2>
				<ul class="checkbox-grid">
				<?php $permByType = roleClass::getPermissionsByType($key2) ?>
				<?php foreach ($permByType as $key3 => $value3): ?>
					<li><input type="checkbox" name="selPerm" class="selPerm" value="<?php echo $key3 ?>"><?php echo $value3 ?></li>
				<?php endforeach; ?>
				</ul>
				<br>
			<?php endforeach; ?>
		</td>
		<td><input type="button" name="addRole" class="addRole" value="Add Role" ></td>
	</tr>
	</table>
</div>

<?php
    require_once ("_inc/footer.php");
?>