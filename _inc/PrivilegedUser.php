<?php

require_once("dbClass.php");

class PrivilegedUser {

    private $roles;
 
    public function __construct() {
        parent::__construct();
    }
 
    // override User method
    public static function getByUsername($username) {
        $sql    = "SELECT * FROM users WHERE username = '{$username}'";
        $sth    = $db->query($sql);
        $result = $db->fetch_array($sth);
 
        if (!empty($result)) {
            $privUser = new PrivilegedUser();
            $privUser->user_id    = $result[0]["user_id"];
            $privUser->username   = $username;
            $privUser->password   = $result[0]["password"];
            $privUser->email_addr = $result[0]["email"];
            $privUser->initRoles();
            return $privUser;
        } else {
            return false;
        }
    }
 
    // populate roles with their associated permissions
    protected function initRoles() {
        $this->roles = array();
        $sql = "SELECT t1.role_id, t2.role_name FROM user_role as t1
                JOIN roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.user_id = {$this->user_id}";
        $sth = $db->query($sql);
 
        while($row = mysql_fetch_assoc($sth)) {
            $this->roles[$row["role_name"]] = Role::getRolePerms($row["role_id"]);
        }
    }
 
    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }

    // check if a user has a specific role
    public function hasRole($role_name) {
        return isset($this->roles[$role_name]);
    }
     
    // insert a new role permission association
    public static function insertPerm($role_id, $perm_id) {
        $sql = "INSERT INTO role_perm (role_id, perm_id) VALUES ('{$role_id}', '{$perm_id}')";
        $sth = $db->query($sql);
        return $sth;
    }
     
    // delete ALL role permissions
    public static function deletePerms() {
        $sql = "TRUNCATE role_perm";
        $sth = $db->query($sql);
        return $sth;
}
}