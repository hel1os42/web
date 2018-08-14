<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    /**
     * Activation constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');
        $this->table      = 'users_confirmations';
        $this->primaryKey = 'id';
        $this->timestamps = false;

        $this->fillable = [
            'user_id',
            'token',
            'created_at',
        ];

        parent::__construct($attributes);
    }
}
