<?php 
namespace App\Models;
use CodeIgniter\Model;
class DbDataManager extends Model
{
    protected $table = 'datamanager';
    protected $primaryKey = 'name';
    protected $allowedFields = ['name', 'source', 'presentation', 'item'];
}