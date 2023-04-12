<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'day_off' => 'array'
    ];

    public function fleetType(){
        return $this->belongsTo(FleetType::class);
    }

    public function ticketPrice(){
        return $this->belongsTo(TicketPrice::class, 'fleet_type_id', 'fleet_type_id')->where('vehicle_route_id', $this->vehicle_route_id);
    }

    public function agentCommission()
    {
        $agent = Agent::where('user_id', auth()->user()->id)->first();
        return $this->hasMany(AgentCommission::class)->where('agent_id', $agent->id);
    }

    public function route(){
        return $this->belongsTo(VehicleRoute::class ,'vehicle_route_id' );
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public function startFrom(){
        return $this->belongsTo(Counter::class, 'start_from', 'id');
    }

    public function endTo(){
        return $this->belongsTo(Counter::class, 'end_to', 'id');
    }

    public function assignedVehicle(){
        return $this->hasOne(AssignedVehicle::class);
    }

    public function bookedTickets(){
        return $this->hasMany(BookedTicket::class)->whereIn('status', [1,2]);
    }

    //scope

    public function scopeActive(){
        return $this->where('status', 1);
    }
}
