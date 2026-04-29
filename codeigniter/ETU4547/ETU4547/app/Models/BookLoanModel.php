<?php

namespace App\Models;

use CodeIgniter\Model;

class BookLoanModel extends Model
{
    protected $table = 'book_loans';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'book_id',
        'borrower_name',
        'borrowed_at',
        'returned_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
