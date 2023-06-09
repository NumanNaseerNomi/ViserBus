@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Agent')</th>
                                <th>@lang('Trip')</th>
                                <th>@lang('Fare')</th>
                                <th>@lang('Commission')</th>
                                <th>@lang('Seats Limit')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($agents as $agent)
                            <tr>
                                <td data-label="@lang('Agent')">
                                    <span class="font-weight-bold">{{$agent->agent->user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.agents.detail', $agent->id) }}"><span>@</span>{{ $agent->agent->user->username }}</a>
                                    </span>
                                </td>
                                <td data-label="@lang('Trip')">
                                    {{ $agent->trip->title }}
                                </td>
                                <td data-label="@lang('Fare')">
                                    <span class="font-weight-bold" data-toggle="tooltip" data-original-title="{{ @$agent->address->country }}">{{ $agent->trip->ticketPrice->price }}</span>
                                </td>
                                <td data-label="@lang('Commission')">
                                    {{ $agent->commission_amount ? $agent->commission_amount : '-' }}
                                </td>

                                <td data-label="@lang('Joined At')">
                                    {{ $agent->seats_limit ? $agent->seats_limit : '-' }}
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.agents.commissions.detail', $agent->id) }}" class="icon-btn m-1" data-toggle="tooltip" title="" data-original-title="@lang('Edit')">
                                        <i class="las la-pen text--shadow"></i>
                                    </a>
                                    <!-- <a href="{{ route('admin.agents.detail', $agent->id) }}" class="icon-btn m-1 btn--danger" data-toggle="tooltip" title="" data-original-title="@lang('Edit')">
                                        <i class="las la-trash text--shadow"></i>
                                    </a> -->
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($agents) }}
                </div>
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
<a href="{{ route('admin.agents.commissions.create')}}" class="btn btn--primary box--shadow1 addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
    <!-- <form action="{{ route('admin.agents.search', $scope ?? str_replace('admin.agents.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or email')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form> -->
@endpush
