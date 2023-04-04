@extends($activeTemplate.$layout)

@section('content')

@php

$counters = App\Models\Counter::get();

@endphp

<div class="ticket-search-bar bg_img padding-top" style="background: url({{ getImage('assets/templates/basic/images/bg/inner.jpg') }}) left center;">

    <div class="container">

        <div class="bus-search-header">

            <form action="{{ route('search') }}" class="ticket-form ticket-form-two row g-3 justify-content-center">

                <div class="col-md-4 col-lg-3">

                    <div class="form--group">

                        <i class="las la-location-arrow"></i>

                        <select name="pickup" class="form--control select2" required>

                            <option value="">@lang('Pickup Point')</option>

                            @foreach ($counters as $counter)

                            <option value="{{ $counter->id }}" @if(request()->pickup == $counter->id) selected @endif>{{ __($counter->name) }}</option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="col-md-4 col-lg-3">

                    <div class="form--group">

                        <i class="las la-map-marker"></i>

                        <select name="destination" class="form--control select2" required>

                            <option value="">@lang('Dropping Point')</option>

                            @foreach ($counters as $counter)

                            <option value="{{ $counter->id }}" @if(request()->destination == $counter->id) selected @endif>{{ __($counter->name) }}</option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="col-md-4 col-lg-3">

                    <div class="form--group">

                        <i class="las la-calendar-check"></i>

                        <input type="text" name="date_of_journey" class="form--control datepicker" placeholder="@lang('Date of Journey')" autocomplete="off" value="{{ request()->date_of_journey }}" required>

                    </div>

                </div>

                <div class="col-md-6 col-lg-3">

                    <div class="form--group">

                        <button>@lang('Find Tickets')</button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Ticket Search Starts -->





<!-- Ticket Section Starts Here -->

<section class="ticket-section padding-bottom section-bg">

    <div class="container">

        <div class="row gy-5">

            <div class="col-lg-3">

                <form action="{{ route('search') }}" id="filterForm">

                    <div class="ticket-filter">

                        <div class="filter-header filter-item">

                            <h4 class="title mb-0">@lang('Filter')</h4>

                            <button type="reset" class="reset-button h-auto">@lang('Reset All')</button>

                        </div>

                        @if($fleetType)

                        <div class="filter-item">

                            <h5 class="title">@lang('Vehicle Type')</h5>

                            <ul class="bus-type">

                                @foreach ($fleetType as $fleet)

                                <li class="custom--checkbox">

                                    <input name="fleetType[]" class="search" value="{{ $fleet->id }}" id="{{ $fleet->name }}" type="checkbox" @if (request()->fleetType)

                                    @foreach (request()->fleetType as $item)

                                    @if ($item == $fleet->id)

                                    checked

                                    @endif

                                    @endforeach

                                    @endif >

                                    <label for="{{ $fleet->name }}"><span><i class="las la-bus"></i>{{ __($fleet->name) }}</span></label>

                                </li>

                                @endforeach

                            </ul>

                        </div>

                        @endif



                        @if ($routes)

                        <div class="filter-item">

                            <h5 class="title">@lang('Routes')</h5>

                            <ul class="bus-type">

                                @foreach ($routes as $route)

                                <li class="custom--checkbox">

                                    <input name="routes[]" class="search" value="{{ $route->id }}" id="route.{{ $route->id }}" type="checkbox" @if (request()->routes)

                                    @foreach (request()->routes as $item)

                                    @if ($item == $route->id)

                                    checked

                                    @endif

                                    @endforeach

                                    @endif >

                                    <label for="route.{{ $route->id }}"><span><span><i class="las la-road"></i> {{ __($route->name) }} </span></label>

                                </li>

                                @endforeach

                            </ul>

                        </div>

                        @endif



                        @if ($schedules)

                        <div class="filter-item">

                            <h5 class="title">@lang('Schedules')</h5>

                            <ul class="bus-type">

                                @foreach ($schedules as $schedule)

                                <li class="custom--checkbox">

                                    <input name="schedules[]" class="search" value="{{ $schedule->id }}" id="schedule.{{ $schedule->id }}" type="checkbox" @if (request()->schedules)

                                    @foreach (request()->schedules as $item)

                                    @if ($item == $schedule->id)

                                    checked

                                    @endif

                                    @endforeach

                                    @endif>

                                    <label for="schedule.{{ $schedule->id }}"><span><span><i class="las la-clock"></i> {{ showDateTime($schedule->start_from, 'h:i a').' - '. showDateTime($schedule->end_at, 'h:i a') }} </span></label>

                                </li>

                                @endforeach

                            </ul>

                        </div>

                        @endif

                    </div>

                </form>

            </div>

            <div class="col-lg-9">

                <div class="ticket-wrapper">
                    @if($trips->total())
                        <table class="booking-table">
                            <thead>
                                <tr>
                                    <th>@lang('Vehicle Type')</th>
                                    <th>@lang('Starting Point')</th>
                                    <th>@lang('Dropping Point')</th>
                                    <th>@lang('Pickup Time')</th>
                                    <th>@lang('Fare')</th>
                                    <th>@lang('Off Days')</th>
                                    <th>@lang('Seats')</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif

                    @forelse ($trips as $trip)

                    @php

                    $start = Carbon\Carbon::parse($trip->schedule->start_from);

                    $end = Carbon\Carbon::parse($trip->schedule->end_at);

                    $diff = $start->diff($end);

                    $ticket = App\Models\TicketPrice::where('fleet_type_id', $trip->fleetType->id)->where('vehicle_route_id', $trip->route->id)->first();

                    $fleet_type_id_data = App\Models\Trip::where('id', $trip->id)->get(['fleet_type_id']);
                    $fleet_type_id = $fleet_type_id_data[0]->fleet_type_id;
                    $totalseats_data = App\Models\FleetType::where('id',$fleet_type_id)->get(['deck_seats']);
                    $totalseats = $totalseats_data[0]->deck_seats ;
                    $left_seats =  $totalseats[0];

                    $date_of_journey = request()->date_of_journey; 
                    $pickup_point = request()->pickup;
                    $destination_point = request()->destination;
                    
                    if(!empty($date_of_journey) && !empty($pickup_point) && !empty($destination_point)){
                        
                        $bookedTicket  = App\Models\BookedTicket::where('trip_id', $trip->id)->where('date_of_journey', Carbon\Carbon::parse(urldecode($date_of_journey))->format('Y-m-d'))->whereIn('status', [1,2])->get()->toArray();
                        if(sizeof($bookedTicket) == 0){
                            $left_seats = $totalseats[0] - sizeof($bookedTicket);
                        }else{
                            $booked = 0;
                            foreach($bookedTicket as $bt){
                                $booked = $booked + $bt['ticket_count'];
                            }
                            $left_seats = $totalseats[0] - $booked;
                        }
                    }
                    //echo $left_seats;    
                    @endphp





                                <tr>
                                    <td class="text-success">{{ __($trip->fleetType->name) }}</td>
                                    <td class="text-success">{{ __($trip->startFrom->name) }}</td>
                                    <td class="text-success">{{ __($trip->endTo->name) }}</td>
                                    <td class="text-success">{{ showDateTime($trip->schedule->start_from, 'h:i A') }}</td>
                                    <td class="text-success">{{ __($general->cur_sym) }}{{ showAmount($ticket->price) }}</td>
                                    <td class="text-success">
                                        @if($trip->day_off)
                                            <div class="seats-left mt-2 mb-3 fs--14px">
                                                <div class="d-inline-flex flex-wrap" style="gap:5px">
                                                    @foreach ($trip->day_off as $item)
                                                        <span class="badge badge--primary">{{ __(showDayOff($item)) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            @lang('Every day available')
                                        @endif
                                    </td>
                                    <td class="text-success">2 available out of 5<br/>
                                        @php
                                            $urlstring = "/?";
                                            if(!empty(request()->pickup)){ $urlstring .= 'pickup='. request()->pickup;}
                                            if(!empty(request()->destination)){ $urlstring .= '&destination='. request()->destination;}
                                            if(!empty(request()->date_of_journey)){ $urlstring .= '&date_of_journey='. request()->date_of_journey;}
                                        @endphp
                                        <a class="btn btn--base" href="{{ route('ticket.seats', [$trip->id, slug($trip->title)]) }} {{$urlstring}}">@lang('Select Seat')</a>
                                    </td>
                                </tr>
                                @if ($trip->fleetType->facilities)
                                    <tr>
                                        <td colspan="7">
                                            <div class="d-flex content-justify-center">
                                            <strong>@lang('Facilities: ')</strong>
                                                @foreach ($trip->fleetType->facilities as $item)
                                                    <span class="facilities">{{ __($item) }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                    <!-- <div class="ticket-item"> -->

                        <!-- <div class="ticket-item-inner">

                            <h5 class="bus-name">{{ __($trip->title) }}</h5>

                            <span class="bus-info">@lang('Seat Layout - ') {{ __($trip->fleetType->seat_layout) }}</span>

                            <span class="ratting"><i class="las la-bus"></i>{{ __($trip->fleetType->name) }}</span>
                            @php if(!empty($date_of_journey) && !empty($pickup_point) && !empty($destination_point)){ @endphp
                            <span class="ratting">Avilable Seats: {{$left_seats}}</span>

                            @php } @endphp
                        </div> -->

                        <!-- <div class="ticket-item-inner travel-time">

                            <div class="bus-time">

                                <p class="time">{{ showDateTime($trip->schedule->start_from, 'h:i A') }}</p>

                                <p class="place">{{ __($trip->startFrom->name) }}</p>

                            </div>

                            <div class=" bus-time">

                                <i class="las la-arrow-right"></i>

                                <p>{{ $diff->format('%H:%I min') }}</p>

                            </div>

                            <div class=" bus-time">

                                <p class="time">{{ showDateTime($trip->schedule->end_at, 'h:i A') }}</p>

                                <p class="place">{{ __($trip->endTo->name) }}</p>

                            </div>

                        </div> -->

                        <!-- <div class="ticket-item-inner book-ticket">

                            <p class="rent mb-0">{{ __($general->cur_sym) }}{{ showAmount($ticket->price) }}</p>

                            @if($trip->day_off)

                            <div class="seats-left mt-2 mb-3 fs--14px">

                                @lang('Off Days'): <div class="d-inline-flex flex-wrap" style="gap:5px">

                                    @foreach ($trip->day_off as $item)

                                    <span class="badge badge--primary">{{ __(showDayOff($item)) }}</span>

                                    @endforeach

                                </div>

                                @else

                                @lang('Every day available')

                                @endif</div>
                                @php
                                $urlstring = "/?";

                                if(!empty(request()->pickup)){ $urlstring .= 'pickup='. request()->pickup;}
                                if(!empty(request()->destination)){ $urlstring .= '&destination='. request()->destination;}
                                if(!empty(request()->date_of_journey)){ $urlstring .= '&date_of_journey='. request()->date_of_journey;}
                                
                                @endphp
                            <a class="btn btn--base" href="{{ route('ticket.seats', [$trip->id, slug($trip->title)]) }} {{$urlstring}}">@lang('Select Seat')</a>

                        </div> -->

                        <!-- @if ($trip->fleetType->facilities)

                        <div class="ticket-item-footer">

                            <div class="d-flex content-justify-center">

                                <span>

                                    <strong>@lang('Facilities - ')</strong>

                                    @foreach ($trip->fleetType->facilities as $item)

                                    <span class="facilities">{{ __($item) }}</span>

                                    @endforeach

                                </span>

                            </div>

                        </div>

                        @endif -->

                    <!-- </div> -->

                    @empty

                    <div class="ticket-item">

                        <h5>{{ __($emptyMessage) }}</h5>

                    </div>

                    @endforelse
                            </tbody>
                        </table>

                    @if ($trips->hasPages())

                    {{ paginateLinks($trips) }}

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection

@push('script')

<script>

    (function($) {

        "use strict";

        $('.search').on('change', function() {

            $('#filterForm').submit();

        });



        $('.reset-button').on('click', function() {

            $('.search').attr('checked', false);

            $('#filterForm').submit();

        })

    })(jQuery)

    

</script>

@endpush