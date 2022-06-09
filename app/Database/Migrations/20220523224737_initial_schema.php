<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitSchema extends Migration
{
    public function up()
    {
        if ($this->forge->createDatabase('datamanager',true)) {
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
            $this->forge->addField($fields);
            $this->forge->addKey('name', TRUE);
            $this->forge->createTable('datamanager', TRUE);
            #DataManager Preview
            $this->forge->addField($fields);
            $this->forge->addKey('name', TRUE);
            $this->forge->createTable('preview', TRUE);
            #Parameters
            $fields = [
                'name'               => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
                'value'              => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
            ];
            $this->forge->addField($fields);
            $this->forge->addKey('name', TRUE);
            $this->forge->createTable('parameters', TRUE);
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
            $this->forge->addField($fields);
            $this->forge->addKey('groupname', TRUE);
            $this->forge->createTable('groups', TRUE);
            #Users
            $fields = [
                'username'           => [
                    'type'           => 'TEXT',
                    'unique'         => true,
                ],
                'password'           => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
                'connection'           => [
                    'type'           => 'TEXT',
                    'null'           => false
                ],
                'theme'              => [
                    'type'           => 'TEXT',
                    'default'        => 'light'
                ],
            ];
            $this->forge->addField($fields);
            $this->forge->addKey('username', TRUE);
            $this->forge->createTable('users', TRUE);
            #ACL
            $fields = [
                'page'           => [
                    'type'           => 'TEXT',
                    'unique'         => true,
                ],
                'users'           => [
                    'type'           => 'TEXT',
                    'null'         => true,
                ],
                'groups'              => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
            ];
            $this->forge->addField($fields);
            $this->forge->addKey('page', TRUE);
            $this->forge->createTable('acl', TRUE);
            #Roles
            $fields = [
                'username'           => [
                    'type'           => 'TEXT',
                ],
                'groupname'           => [
                    'type'           => 'TEXT',
                ],
            ];
            $this->forge->addField($fields);
            $this->forge->addKey('username', TRUE);
            $this->forge->addKey('groupname', TRUE);
            $this->forge->addForeignKey('username', 'users', 'username');
            $this->forge->addForeignKey('groupname', 'groups', 'groupname');
            $this->forge->createTable('roles', TRUE);

            //SEEDS
            $seeder = \Config\Database::seeder();
            $seeder->call('InitialData');
        }
    }

    public function down()
    {
        $this->forge->dropTable('datamanager');
        $this->forge->dropTable('preview');
        $this->forge->dropTable('parameters');
        $this->forge->dropTable('acl');
        $this->forge->dropTable('roles');
        $this->forge->dropTable('groups');
        $this->forge->dropTable('users');
    }
}