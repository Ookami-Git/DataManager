<?php

namespace App\Controllers;
use App\Models;

class Initialisation extends BaseController
{
    function initDatabase() {
        $forge = \Config\Database::forge();
        if ($forge->createDatabase('datamanager',true)) {
            #DataManager
            $fields = [
                'name'               => [
                    'type'           => 'TEXT',
                    'unique'         => true,
                ],
                'source'             => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
                'presentation'        => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
                'item'               => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
            ];
            $forge->add_key('name', TRUE);
            $forge->create_table('datamanager', TRUE);
            #Parameters
            $fields = [
                'id'                 => [
                    'type'           => 'INT',
                    'unique'         => true,
                    'auto_increment' => true,
                ],
                'name'               => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
                'value'              => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
            ];
            $forge->add_key('id', TRUE);
            $forge->create_table('parameters', TRUE);
            #Groups
            $fields = [
                'groupname'          => [
                    'type'           => 'TEXT',
                    'unique'         => true,
                ],
                'description'        => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
            ];
            $forge->add_key('groupname', TRUE);
            $forge->create_table('groups', TRUE);
            #Users
            $fields = [
                'username'           => [
                    'type'           => 'TEXT',
                    'unique'         => true,
                ],
                'theme'              => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
            ];
            $forge->add_key('username', TRUE);
            $forge->create_table('users', TRUE);
            
            echo 'Database created!';
        }
    }
}