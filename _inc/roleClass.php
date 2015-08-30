<?php

require_once("dbClass.php");

class roleClass {

    protected $permissions;
 
    protected function __construct() {
        $this->permissions = array();
    }
 
    // return a role object with associated permissions
    public static function getRolePerms($role_id) {
        global $db;
        $role = new roleClass();
        $sql = "SELECT t2.perm_desc, t2.perm_full_desc, t2.perm_type_id
                FROM role_perm as t1
                JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                WHERE t1.role_id = {$role_id}";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $role->permissions[] = $row;
        }
        return $role;
    }

    // return a role associated permission ids
    public static function getRolePermIds($role_id) {
        global $db;
        $sql = "SELECT perm_id
                FROM role_perm
                WHERE role_id = {$role_id}";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $permissions[] = $row["perm_id"];
        }
        return $permissions;
    }

    // return all roles
    public static function getAllRoles() {
        global $db;
        $sql = "SELECT * FROM roles";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $roles[$row['role_id']] = $row['role_name'];
        }
        return $roles;
    }

    // return permessions by role
    // public static function getPermissionsByRole() {
    //     global $db;
    //     $sql = "SELECT * FROM roles";
    //     $sth = $db->query($sql);
    //     while($row = mysql_fetch_assoc($sth)) {
    //         $roles[$row['role_id']] = $row['role_name'];
    //     }
    //     return $roles;
    // }

    // return all permessions for each role
    public static function getAllRolePermissions() {
        global $db;
        $sql = "SELECT * FROM role_perm";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $rolePerm[] = $row;
        }
        return $rolePerm;
    }

    // return all permession types
    public static function getAllPermissionTypes() {
        global $db;
        $sql = "SELECT * FROM perm_types";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $permTypes[$row['perm_type_id']] = $row['perm_type_desc'];
        }
        return $permTypes;
    }

    // return permissions by type
    public static function getPermissionsByType($perm_type_id) {
        global $db;
        $sql = "SELECT * FROM permissions
                WHERE perm_type_id = {$perm_type_id}";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $permissions[$row['perm_id']] = $row['perm_desc'];
        }
        return $permissions;
    }

    // return all permissions
    public static function getAllPermissions() {
        global $db;
        $sql = "SELECT * FROM permissions";
        $sth = $db->query($sql);
        while($row = mysql_fetch_assoc($sth)) {
            $permissions[$row['perm_id']] = $row['perm_desc'];
        }
        return $permissions;
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