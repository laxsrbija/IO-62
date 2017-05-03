<?php

/**
 * Created by PhpStorm.
 * User: Lazar
 * Date: 3.5.2017.
 * Time: 18.45
 */

    require_once  "Member.php";

    class PrivilegedMember extends Member {

        private $roles;

        public function __construct() {
            parent::__construct();
        }

        public static function getByUsername($username) {
            $result = queryDBO("SELECT * FROM members WHERE user = '$username'");
            $row = $result->fetch_assoc();
            if (!empty($row)) {
                $privMember = new PrivilegedMember();
                $privMember->id = $row["id"];
                $privMember->user = $username;
                $privMember->initRoles($row["id"]);
                return $privMember;
            }
            return false;
        }

        protected function initRoles($uid) {
            $this->roles = array();
            $sql = "SELECT t1.role_id, t2.role_name FROM member_role AS t1 
              LEFT JOIN roles AS t2 ON t1.role_id = t2.id WHERE t1.member_id = $uid";
            $result = queryDBO($sql);
            while ($row = $result->fetch_assoc()) {
                $this->roles[$row["role_name"]] = Role::getRolePerm($row["role_id"]);
            }
        }

        public function hasPrivilege($perm_desc) {
            foreach ($this->roles as $role) {
                if ($role->hasPerm($perm_desc))
                    return true;
            }
            return false;
        }

    }