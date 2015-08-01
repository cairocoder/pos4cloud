<?php

require_once("dbClass.php");

class Role {

    protected $permissions;
 
    protected function __construct() {
        $this->permissions = array();
    }
 
    // return a role object with associated permissions
    public static function getRolePerms($role_id) {
        $role = new Role();
        $sql = "SELECT t2.perm_desc FROM role_perm as t1
                JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                WHERE t1.role_id = {$role_id}";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $role->permissions[$row["perm_desc"]] = true;
        }
        return $role;
    }
 
    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }

    // insert a new role
    public static function insertRole($role_name) {
        $sql = "INSERT INTO roles (role_name) VALUES (:role_name)";
        $sth = $db->query($sql);
        return $sth;
    }
     
    // insert array of roles for specified user id
    public static function insertUserRoles($user_id, $roles) {
        $sql = "INSERT INTO user_role (user_id, role_id) VALUES ('{$user_id}', '{$roles}')";
        $sth = $GLOBALS["DB"]->prepare($sql);
        $sth = $db->query($sql);
        return true;
    }
     
    // delete array of roles, and all associations
    public static function deleteRoles($role_id) {
        $sql = "DELETE t1, t2, t3 FROM roles as t1
                JOIN user_role as t2 on t1.role_id = t2.role_id
                JOIN role_perm as t3 on t1.role_id = t3.role_id
                WHERE t1.role_id = '{$role_id}'";
        $sth = $db->query($sql);
        return true;
    }
     
    // delete ALL roles for specified user id
    public static function deleteUserRoles($user_id) {
        $sql = "DELETE FROM user_role WHERE user_id = '{$user_id}'";
        $sth = $db->query($sql);
        return $sth;
    }
}