@extends($activeTemplate.$layout)

@section('content')

@php

$counters = App\Models\Counter::get();

@endphp



<!-- Ticket Section Starts Here -->

<section class="ticket-section padding-bottom section-bg">

    <div class="container">

        <div class="row gy-5">

            <div class="col-lg-3">

                

            </div>

            <div class="col-lg-9">

                <div class="ticket-wrapper">
                        <h5 style="margin-top:15px;margin-bottom:15px;">For Best Travel Experience</h5>
                        <h1>Book a Ticket Now</h1>
                        <div class="row">
                        <form action="{{ route('agentticketsearch') }}" class="ticket-form ticket-form-two row g-3 justify-content-center">

                            <div class="col-md-12 col-lg-12">

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

                            <div class="col-md-12 col-lg-12">     

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

                            <div class="col-md-12 col-lg-12">

                                <div class="form--group">

                                    <i class="las la-calendar-check"></i>

                                    <input type="text" name="date_of_journey" class="form--control datepicker" placeholder="@lang('Date of Journey')" autocomplete="off" value="{{ request()->date_of_journey }}" required >

                                </div>

                            </div>

                            <div class="col-md-12 col-lg-12">

                                <div class="form--group">

                                    <button>@lang('SEARCH AVAILABLE BUSES')</button>

                                </div>

                            </div>

                        </form>
                    </div>
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