<?php 
namespace App\Models;
use CodeIgniter\Model;
class dbRoles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = ['username','groupname'];
    protected $allowedFields = ['username','groupname','protected'];
}