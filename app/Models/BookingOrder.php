<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingOrder extends Model
{
    protected $fillable = [
        'project_id', 'project_name', 'contact_person', 'project_manager',
        'project_captain', 'project_assistant_captain', 'phone_number',
        'set_down_date', 'set_down_time', 'event_venue', 'set_up_time',
        'estimated_set_up_period', 'set_down_team', 'pasting_team',
        'technical_team', 'logistics_designated_truck', 'driver',
        'loading_team_confirmed', 'printed_collateral_shared',
        'approved_mock_up_shared', 'fabrication_preparation',
        'time_of_loading_departure', 'safety_gear_checker',
    ];


    public function teams()
{
    return $this->hasMany(BookingOrderTeam::class);
}

public function setDownTeam()
{
    return $this->teams()->where('team_type', 'set_down');
}

public function pastingTeam()
{
    return $this->teams()->where('team_type', 'pasting');
}

public function technicalTeam()
{
    return $this->teams()->where('team_type', 'technical');
}

}
