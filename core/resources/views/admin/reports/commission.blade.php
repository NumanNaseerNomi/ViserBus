@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <form action="{{route('admin.report.commission')}}" class="form-inline pb-2">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="px-2" for="report_date">User </label>
                                <select class="form-select" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach($users as $item)
                                        <option value="{{ $item[0]->user->id }}" @if($item[0]->user->id == request()->user_id) selected @endif>{{ $item[0]->user->fullname }} ({{ $item[0]->user->getCategory->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="px-2" for="report_date">Start Date </label>
                                <input type="text" name="start_date" id="start_date" class="form-control" placeholder="@lang('Start Date')" autocomplete="off" value="{{ request()->start_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="px-2" for="report_date">End Date </label>
                                <input type="text" name="end_date" id="end_date" class="form-control" placeholder="@lang('End Date')" autocomplete="off" value="{{ request()->end_date }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="px-2" for="report_date">Status </label>
                                <select class="form-select" name="status">
                                    <option value="">Select Status</option>
                                    <option value="1" @if(request()->status == 1) selected @endif>@lang('Booked')</option>
                                    <option value="2" @if(request()->status == 2) selected @endif>@lang('Pending')</option>
                                    <option value="0" @if(request()->status == '0') selected @endif>@lang('Rejected')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button class="btn btn-primary">@lang('Search')</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('PNR Number')</th>
                                    <th>@lang('Journey Date')</th>
                                    <th>@lang('Trip')</th>
                                    <!-- <th>@lang('Pickup Point')</th>
                                    <th>@lang('Dropping Point')</th> -->
                                    <th>@lang('Status')</th>
                                    <th>@lang('Ticket Count')</th>
                                    <th>@lang('Fare')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $item)
                                <tr>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ __(@$item->user->fullname) }}</span>
                                    <br>
                                    <span class="small"> <a href="{{ route('admin.users.detail', $item->user_id) }}"><span>@</span>{{ __(@$item->user->username) }}</a> </span>

                                    </td>
                                    <td data-label="@lang('PNR Number')">
                                        <span class="text-muted">{{ __($item->pnr_number) }}</span>
                                    </td>
                                    <td data-label="@lang('Journey Date')">
                                        {{ __(showDateTime($item->date_of_journey, 'd M, Y')) }}
                                    </td>
                                    <td data-label="@lang('Trip')">
                                        <span class="font-weight-bold">{{ __($item->trip->fleetType->name) }}</span>
                                        <br>
                                        <span class="font-weight-bold"> {{ __($item->trip->startFrom->name ) }} - {{ __($item->trip->endTo->name ) }}</span>
                                    </td>
                                    <!-- <td data-label="@lang('Pickup Point')">
                                        {{ __($item->pickup->name) }}
                                    </td>
                                    <td data-label="@lang('Dropping Point')">
                                        {{ __($item->drop->name) }}
                                    </td> -->
                                    <td data-label="@lang('Status')">
                                        @if ($item->status == 1)
                                            <span class="badge badge--success font-weight-normal text--samll">@lang('Booked')</span>
                                        @elseif($item->status == 2)
                                            <span class="badge badge--warning font-weight-normal text--samll">@lang('Pending')</span>
                                        @else
                                            <span class="badge badge--danger font-weight-normal text--samll">@lang('Rejected')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Ticket Count')">
                                        {{ __(sizeof($item->seats)) }}
                                    </td>
                                    <td data-label="@lang('Fare')">
                                        {{ __(showAmount($item->sub_total)) }} {{ __($general->cur_text) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($tickets) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
<form action="{{route('admin.vehicle.ticket.search', $scope ?? str_replace('admin.vehicle.ticket.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('Search PNR Number')" value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>
@endpush
