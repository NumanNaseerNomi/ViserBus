@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-12 col-lg-7 col-md-7 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Update Agent Commission')</h5>
                    <form action="{{route('admin.agents.commissions.update',[$agentCommission->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Password') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="password" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="" required>
                                </div>
                            </div> -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Agent') <span class="text-danger">*</span></label>
                                    <select class="form-control" name="agent_id" id="agent_id" auto-complete="off" required>
                                        <option value="">Select Agent</option>
                                        @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" @if($agentCommission->agent->id == $agent->id) selected @endif>{{ $agent->user->firstname }} ({{ $agent->user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('ID Number')<span class="text-danger">*</span></label>
                                    <input class="form-control onlyAlphNumeric" type="text" name="id_number" value="">
                                </div>
                            </div> -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Trip') <span class="text-danger">*</span></label>
                                    <select class="form-control" name="trip_id" id="trip_id" auto-complete="off" required>
                                        <option value="">Select Trip</option>
                                        @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}" @if($agentCommission->trip->id == $trip->id) selected @endif>{{ $trip->title }} ({{ __($general->cur_sym) }} {{ showAmount($trip->ticketPrice->price) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Commission') <span class="text-danger">*</span></label>
                                    <input class="form-control onlyNumericValue" type="text" name="commission_amount" value="{{ $agentCommission->commission_amount }}" auto-complete="off" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Seats Limit') <span class="text-danger">*</span></label>
                                    <select class="form-control" name="seats_limit" id="seats_limit" auto-complete="off" required>
                                        <option value="">Select Limit</option>
                                        @foreach(range(1, 15) as $value)
                                        <option value="{{ $value }}" @if($agentCommission->seats_limit == $value) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <textarea class="form-control" name="address"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') <span class="text-danger">*</span></label>
                                    <select name="country" class="form-control" required>
                                      <option value="">Select Country</option>
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"  data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%" name="status">
                            </div>
                        </div> -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





@endsection
