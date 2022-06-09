<?php 
namespace App\Models;
use CodeIgniter\Model;
class DbAcl extends Model
{
    protected $table = 'acl';
    protected $primaryKey = 'page';
    protected $allowedFields = ['page', 'users', 'groups'];
}