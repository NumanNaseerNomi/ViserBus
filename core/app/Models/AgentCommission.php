<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentCommission extends Model
{
    use HasFactory;
    protected $table = 'agents_commissions';
    protected $guarded = ['id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
