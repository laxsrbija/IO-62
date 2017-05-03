<?php
/**
 * Created by PhpStorm.
 * User: Lazar
 * Date: 3.5.2017.
 * Time: 17.49
 */

    class Role {
        protected $permissions;

        public static function getRolePerm($role_id) {
            $role = new Role();
            $sql = "SELECT t2.perm_desc FROM role_perm AS t1 
              LEFT JOIN permissions AS t2 ON t1.perm_id = t2.id WHERE t1.role_id = $role_id";
            $result = queryDBO($sql);
            while ($row = $result->fetch_assoc()) {
                $role->permissions[$row["perm_desc"]] = true;
            }
            return $role;
        }

        public function __construct() {
            $this->permissions = array();
        }
/*
        public function __construct($perm_desc) {
            if (!isset($this->permissions[$perm_desc])){
                $this->permissions[$perm_desc] = true;
            }
        }
*/
        public function __toString() {
            $res = "";
            foreach ($this->permissions as $perm)
                $res .= key($perm) . " ";
            return $res;
        }

        public function hasPerm($perm_desc) {
            return isset($this->permissions[$perm_desc]);
        }

    }