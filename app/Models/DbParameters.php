<?php 
namespace App\Models;
use CodeIgniter\Model;
class DbParameters extends Model
{
    protected $table = 'parameters';
    protected $primaryKey = 'name';
    protected $allowedFields = ['name', 'value'];
}