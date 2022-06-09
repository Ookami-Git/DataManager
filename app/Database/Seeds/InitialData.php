<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialData extends Seeder
{
    public function run()
    {
        //USERS
        #admin admin
        $data = [
            'username'      => 'admin',
            'connection'    => 'local',
            'password'      => '$2y$10$64km3TrS9M34RPg24iozReAzu0MLYwTtjSSoKhBrQ6Jb3Ukv4U9lq'
        ];
        $this->db->table('users')->insert($data);
        //GROUPS
        $data = [
            'groupname'      => 'admin',
            'description'   => 'Administrator'
        ];
        $this->db->table('groups')->insert($data);
        $data = [
            'groupname'      => 'users',
            'description'   => 'Default Group'
        ];
        $this->db->table('groups')->insert($data);
        //ROLES
        $data = [
            'username'      => 'admin',
            'groupname'   => 'admin'
        ];
        $this->db->table('roles')->insert($data);
        //PARAMETERS
        #General
        $data = [
            'name'          => 'theme',
            'value'         => 'light'
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'name',
            'value'         => 'Data Manager'
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'menu',
            'value'         => ''
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'defaultPage',
            'value'         => ''
        ];
        $this->db->table('parameters')->insert($data);
        #LDAP
        $data = [
            'name'          => 'ldapEnable',
            'value'         => False
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'ldapPort',
            'value'         => 389
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'ldapSsl',
            'value'         => False
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'ldapHost',
            'value'         => ''
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'ldapBaseDN',
            'value'         => ''
        ];
        $this->db->table('parameters')->insert($data);
        #Rundeck
        $data = [
            'name'          => 'rundeckEnable',
            'value'         => false
        ];
        $this->db->table('parameters')->insert($data);
        $data = [
            'name'          => 'rundeck',
            'value'         => ''
        ];
        $this->db->table('parameters')->insert($data);
    }
}