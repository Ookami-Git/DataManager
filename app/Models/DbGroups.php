<?php 
namespace App\Models;
use CodeIgniter\Model;
class DbGroups extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'groupname';
    protected $allowedFields = ['groupname', 'description', 'protected'];
}