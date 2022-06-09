<?php 
namespace App\Models;
use CodeIgniter\Model;
class DbDataManagerPreview extends Model
{
    protected $table = 'preview';
    protected $primaryKey = 'name';
    protected $allowedFields = ['name', 'source', 'presentation', 'item'];
}