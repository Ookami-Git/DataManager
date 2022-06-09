<?php 
namespace App\Models;
use CodeIgniter\Model;
class DbUsers extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'username';
    protected $allowedFields = ['username', 'theme', 'connection', 'password'];
}