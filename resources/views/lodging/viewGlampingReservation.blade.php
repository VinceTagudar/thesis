@extends('layouts.app')

@section('content')
@if(count($unit) > 0)
    @if(count($reservation) > 0)
    @foreach($unit as $unit)
    @foreach($reservation as $reservation)
    <div class="container pb-5">
        <div class="pt-3 pb-3 text-center">
            <a href="{{ URL::previous() }}">
                <span style="float:left;">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <strong>Back</strong>
                </span>
            </a>
            <h3>Reservation Details</h3>
        </div>   
        <form method="POST" action="/save-glamping-reservation-details">
        @csrf
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input type="hidden" name="selectedUnit" id="selectedUnit" value="{{$unit->unitNumber}}">
        <input type="hidden" name="reservationID" id="reservationID" value="{{$reservation->id}}">
        <div class="row">
            <div class="col-md-4 order-md-2 mb-4 mx-0">
                <div class="card p-0 mx-0">
                    <h4 class="text-muted" style="text-align:center; padding:0.5em;">Charges</h4>
                    <table class="table table-striped" style="font-size:.88em;">
                        <thead>
                            <tr>
                                <th scope="col" style="width:40%">Description</th>
                                <th scope="col">Qty.</th>
                                <th scope="col">Price</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceRows">
                            @php
                                $totalPrice = 0;    
                            @endphp
                            @if(count($charges) > 0)
                            @foreach($charges as $charge)
                            @php
                                $checkin = new DateTime($charge->checkinDatetime);
                                $checkout = new DateTime($charge->checkoutDatetime);
                                $stayDuration = date_diff($checkin, $checkout)->days+1;
                            @endphp
                            <tr id="invoiceUnit{{$charge->unitNumber}}">
                                <td style="display:none;"><input id="invoiceCheckBox{{$charge->unitNumber}}" class="form-check-input invoiceCheckboxes" type="checkbox" checked>
                                <input type="hidden" name="charge{{$charge->unitNumber}}" class="chargeIDs" value="{{$charge->chargeID}}"></td>
                                <td id="invoiceDescription{{$charge->unitNumber}}" class="invoiceDescriptions">{{$charge->serviceName}}</td>
                                <td id="invoiceQuantity{{$charge->unitNumber}}" style="text-align:right;" class="invoiceQuantities">{{$charge->quantity}}x{{$stayDuration}}</td>
                                <td id="invoiceUnitPrice{{$charge->unitNumber}}" style="text-align:right;" class="invoiceUnitPrices">{{number_format($charge->price, 2)}}</td>
                                <td id="invoiceTotalPrice{{$charge->unitNumber}}" style="text-align:right;" class="invoicePrices">{{number_format($charge->totalPrice, 2)}}</td>
                            </tr>
                            @php
                                $totalPrice += $charge->totalPrice;    
                            @endphp
                            @endforeach
                            @endif

                            @if(count($additionalServices) > 0)
                            @foreach($additionalServices as $additionalService)
                            <tr id="invoiceRow{{$loop->iteration}}">
                            <td style="display:none;"><input id="invoiceCheckBox{{$loop->iteration}}" class="form-check-input invoiceCheckboxes" type="checkbox" checked>
                            <input type="hidden" name="charge{{$loop->iteration}}" class="chargeIDs" value="{{$additionalService->chargeID}}"></td>
                            <td id="invoiceDescription{{$loop->iteration}}" class="invoiceDescriptions">{{$additionalService->serviceName}}</td>
                            <td id="invoiceQuantity{{$loop->iteration}}" style="text-align:right;" class="invoiceQuantities">{{$additionalService->quantity}}</td>
                            <td id="invoiceUnitPrice{{$loop->iteration}}" style="text-align:right;" class="invoiceUnitPrices">{{number_format($additionalService->price, 2)}}</td>
                            <td id="invoiceTotalPrice{{$loop->iteration}}" style="text-align:right;" class="invoicePrices">{{number_format($additionalService->totalPrice, 2)}}</td>
                            </tr>
                            @php
                                $totalPrice += $additionalService->totalPrice;    
                            @endphp
                            @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3" scope="row">TOTAL:</th>
                                <th id="invoiceGrandTotal" style="text-align:right;">₱&nbsp;{{number_format($totalPrice, 2)}}</th>
                            </tr>
                            {{--<tr>
                                <td colspan="4"><button type="button" class="btn btn-primary btn-block w-100" style="text-align:center;width:8em" id="proceedToPayment" data-toggle="modal" data-target="#chargesModal">
                                    Get payment
                                </button></td>
                            </tr>--}}
                            {{--<tr>
                                <th colspan="1">Amount Paid:</th>
                                <th style="text-align:right;"  colspan="3">
                                <input type="number" name="amountPaid" placeholder="0" min="0" style="text-align:right;" class="form-control" id="amount" required>
                                </th>
                            </tr>--}}
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-8 order-md-1 check-in-form">
                <h5 style="margin-bottom:.80em;">Guest Details</h5>
                    <div class="form-group row">
                        <div class="col-md-4 mb-1">
                            <label for="firstName">First name</label>
                            <input class="form-control" type="text" name="firstName" required="required" maxlength="15" placeholder="" value="{{$reservation->firstName}}">
                        </div>
                        <div class="col-md-5 mb-1">
                            <label for="lastName">Last name</label>
                            <input class="form-control" type="text" name="lastName" required="required" maxlength="20" placeholder="" value="{{$reservation->lastName}}">
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="unitNumberOfPax">No. of pax</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <input class="form-control numberOfPaxGlamping"  required="required" min="1" max="100" name="numberOfPaxGlamping" type="number" placeholder="" value="{{$reservation->numberOfPax}}">
                            </div>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-md-6 mb-1">
                            <label for="contactNumber">Contact number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <input class="form-control" type="text" name="contactNumber"  required="required" maxlength="11" placeholder="" value="{{$reservation->contactNumber}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="glamping">Accommodation</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="glamping" maxlength="11" placeholder="" value="Glamping" disabled>
                            </div>
                        </div>
                    </div>  
                    <hr class="mb-4">
                    <h5 style="margin-bottom:.80em;">Unit Details</h5>
                    <div class="form-group row">
                        <div class="col-md-2 mb-1">
                            <label for="unitID">No. of tents</label>
                            {{--<input class="form-control" style="display:none;float:left;" type="number" name="numberOfUnits" placeholder="" value="1" min="1" max="10" disabled>--}}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-campground" aria-hidden="true"></i>
                                    </span>
                                </div>
                            <input class="form-control" type="number" id="numberOfUnits" name="numberOfUnits" required placeholder="" value="{{$reservation->numberOfUnits}}" min="1" max="80" readonly>
                            </div>
                        </div>
                        
                        @if(count($allReservedUnits) > 0)
                        @php
                            $units = "";
                        @endphp
                        @foreach($allReservedUnits as $singleReservedUnit)
                        @php
                            if($loop->iteration == count($allReservedUnits)){
                                $units .= $singleReservedUnit->unitNumber." ";
                            } else {
                                $units .= $singleReservedUnit->unitNumber.", ";
                            }
                        @endphp
                        @endforeach
                        @endif
                        <div class="col-md-10 mb-1">
                            <label for="unitNumber">Unit/s</label>
                            <input type="text" name="unitID" required="required" class="form-control" style="display:none;position:absolute;" value="{{$unit->id}}">
                            <input class="form-control" type="text" name="unitNumber" required id="tokenfield" value="{{$units}}" required>
                            
                            <input class="form-control" style="display:none;float:left;" type="text" name="unitID" value="{{$unit->id}}">
                        </div>
                    </div>
                    @if(count($reservedUnit) > 0)
                    @foreach($reservedUnit as $reservedUnit)
                    @php
                        $unitTotalPrice = $reservedUnit->price * $reservedUnit->numberOfPax;
                    @endphp
                    <div class="form-group row" id="divUnits">
                        <div class="col-md-2 mb-1" id="divUnitNumber{{$reservedUnit->unitNumber}}">
                            <label for="unitNumber">Unit number</label>
                            <input type="text" class="form-control" value="{{$reservedUnit->unitNumber}}" disabled>
                            <input class="" name="totalPrice{{$reservedUnit->unitNumber}}" id="totalPrice{{$reservedUnit->unitNumber}}" type="number" style="display:none;position:absolute" value="{{$unitTotalPrice}}">
                        </div>
                        <div class="col-md-2 mb-1" id="divAccommodationPackage{{$reservedUnit->unitNumber}}">
                            <label for="additionalServiceUnitPrice">Package</label>
                            <select class="form-control accommodationPackages" name="accommodationPackage{{$reservedUnit->unitNumber}}" value="{{$reservedUnit->serviceID}}" id="accommodationPackage{{$reservedUnit->unitNumber}}">
                                @if($reservedUnit->serviceID == 1)                                
                                <option value="1" selected>Solo</option>
                                <option value="2">2 Pax</option>
                                <option value="3">3 pax</option>
                                <option value="4">4 pax</option>

                                @elseif($reservedUnit->serviceID == 2)                                
                                <option value="1">Solo</option>
                                <option value="2" selected>2 Pax</option>
                                <option value="3">3 pax</option>
                                <option value="4">4 pax</option>
                                
                                @elseif($reservedUnit->serviceID == 3)
                                <option value="1">Solo</option>
                                <option value="2">2 Pax</option>
                                <option value="3" selected>3 pax</option>
                                <option value="4">4 pax</option>
                                
                                @elseif($reservedUnit->serviceID == 4)
                                <option value="1">Solo</option>
                                <option value="2">2 Pax</option>
                                <option value="3">3 pax</option>
                                <option value="4" selected>4 pax</option>

                                @endif
                            </select>
                        </div>

                        <div class="col-md-4 mb-1" id="divCheckinDate{{$reservedUnit->unitNumber}}">
                            <label for="checkinDate">Check-in date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    </span>
                                </div>
                            <input type="date" name="checkinDate{{$reservedUnit->unitNumber}}" required="required" class="form-control checkinDates" id="checkinDate{{$reservedUnit->unitNumber}}" value="{{\Carbon\Carbon::parse($reservedUnit->checkinDatetime)->format('Y-m-d')}}">
                            </div>
                        </div>

                        <div class="col-md-4 mb-1" id="divCheckoutDate{{$reservedUnit->unitNumber}}">
                            <label for="checkoutDate">Check-out date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <input type="date" name="checkoutDate{{$reservedUnit->unitNumber}}" required="required" class="form-control checkoutDates" id="checkoutDate{{$reservedUnit->unitNumber}}" value="{{\Carbon\Carbon::parse($reservedUnit->checkoutDatetime)->format('Y-m-d')}}">
                                {{--<input type="text" name="stayDuration" id="stayDuration" required="required" style="display:none;position:absolute;" value="">--}}
                            </div>
                        </div>
                    
                    @endforeach
                    @endif

                    
                    @if(count($otherReservedUnits) > 0)
                    @foreach($otherReservedUnits as $otherReservedUnits)
                    @php
                        $unitTotalPrice = $otherReservedUnits->price * $otherReservedUnits->numberOfPax;
                    @endphp
                        <div class="col-md-2 mb-1" id="divUnitNumber{{$otherReservedUnits->unitNumber}}">
                            {{--<label for="unitNumber">Unit number</label>--}}
                            <input type="text" class="form-control" value="{{$otherReservedUnits->unitNumber}}" disabled>
                        <input class="" name="totalPrice{{$otherReservedUnits->unitNumber}}" id="totalPrice{{$otherReservedUnits->unitNumber}}" type="number" style="display:none;position:absolute" value="{{$unitTotalPrice}}">
                        </div>
                        <div class="col-md-2 mb-1" id="divAccommodationPackage{{$otherReservedUnits->unitNumber}}">
                            {{--<label for="additionalServiceUnitPrice">Package</label>--}}
                            <select class="form-control accommodationPackages" name="accommodationPackage{{$otherReservedUnits->unitNumber}}" value="{{$otherReservedUnits->serviceID}}" id="accommodationPackage{{$otherReservedUnits->unitNumber}}">
                                @if($otherReservedUnits->serviceID == 1)                                
                                <option value="1" selected>Solo</option>
                                <option value="2">2 Pax</option>
                                <option value="3">3 pax</option>
                                <option value="4">4 pax</option>

                                @elseif($otherReservedUnits->serviceID == 2)                                
                                <option value="1">Solo</option>
                                <option value="2" selected>2 Pax</option>
                                <option value="3">3 pax</option>
                                <option value="4">4 pax</option>
                                
                                @elseif($otherReservedUnits->serviceID == 3)
                                <option value="1">Solo</option>
                                <option value="2">2 Pax</option>
                                <option value="3" selected>3 pax</option>   
                                <option value="4">4 pax</option>
                                
                                @elseif($otherReservedUnits->serviceID == 4)
                                <option value="1">Solo</option>
                                <option value="2">2 Pax</option>
                                <option value="3">3 pax</option>
                                <option value="4" selected>4 pax</option>

                                @endif
                            </select>
                        </div>

                        <div class="col-md-4 mb-1" id="divCheckinDate{{$otherReservedUnits->unitNumber}}">
                            {{--<label for="checkinDate">Check-in date</label>--}}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    </span>
                                </div>
                            <input type="date" name="checkinDate{{$otherReservedUnits->unitNumber}}" required="required" class="form-control checkinDates" id="checkinDate{{$otherReservedUnits->unitNumber}}" value="{{\Carbon\Carbon::parse($otherReservedUnits->checkinDatetime)->format('Y-m-d')}}">
                            </div>
                        </div>

                        <div class="col-md-4 mb-1" id="divCheckoutDate{{$otherReservedUnits->unitNumber}}">
                            {{--<label for="checkoutDate">Check-out date</label>--}}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <input type="date" name="checkoutDate{{$otherReservedUnits->unitNumber}}" required="required" class="form-control checkoutDates" id="checkoutDate{{$otherReservedUnits->unitNumber}}" value="{{\Carbon\Carbon::parse($otherReservedUnits->checkoutDatetime)->format('Y-m-d')}}">
                                {{--<input type="text" name="stayDuration" id="stayDuration" required="required" style="display:none;position:absolute;" value="">--}}
                            </div>
                        </div>
                    
                    @endforeach
                    @endif

                    
                    </div>

                    <div id="dateAlertContainer" class="alert alert-warning mt-2" style="display:none;">
                        <a href="#" class="close">&times;</a>
                        <span id="dateAlertMessage"><strong>Invalid Date!</strong> Please select a check-out date after the check-in date.</span>
                    </div>

                    <div id="alertContainer" class="alert alert-danger mt-2" style="display:none;">
                        <a href="#" class="close">&times;</a>
                        <span id="alertMessage"><strong>Occupied!</strong> Tent 3 is occupied from March 25 to March 27.</span>
                    </div>
                    
                    <hr class="mb-4">
                    <div class="form-group row pb-3" id="divAdditionalServices">
                        <div class="col-md-12 mb-1">
                            <h5 style="margin-bottom:.80em;">Additional Services</h5>
                        </div>
                        <input type="hidden" {{--name="additionalServiceAccommodationID" value="" {{--form="serviceForm"--}}>
                        <div class="col-md-3 mb-1" id="divServiceName">
                            <label for="additionalServiceName">Service name</label>
                            <select name="additionalServiceName" id="serviceSelect" class="form-control serviceSelect" {{--form="serviceForm"--}}>
                                <option value="choose" selected disabled >Choose...</option>
                                <option value="6">Airsoft</option>
                                <option value="7">Archery</option>                                
                                <option value="15">Pillow</option>
                                <option value="16">Bedsheet</option>
                                <option value="17">Blanket</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1" id="divQuantity">
                            <label for="additionalServiceNumberOfPax">Quantity</label>
                            <input class="form-control paxSelect" type="number" id="additionalServiceNumberOfPax" {{--name="additionalServiceNumberOfPax"--}} placeholder="" value="" min="1" max="10" {{--form="serviceForm"--}}>
                        </div>
                        <div class="col-md-3 mb-1" id="divUnitPrice">
                            <label for="additionalServiceUnitPrice">Unit price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input class="form-control additionalServiceUnitPrice" type="text" id="additionalServiceUnitPrice" name="additionalServiceUnitPrice" placeholder="" value="" disabled>
                                <input class="form-control additionalServiceUnitPrice" type="text" style="display:none;float:left;" id="additionalServiceUnitPrice" {{--name="additionalServiceUnitPrice"--}} placeholder="" value="" {{--form="serviceForm"--}}>
                            </div>
                        </div>
                        <div class="col-md-3 mb-1" id="divTotalPrice">
                            <label for="additionalServiceTotalPrice">Total price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input class="form-control additionalServiceTotalPrice" type="text" id="additionalServiceTotalPrice" name="additionalServiceTotalPrice" placeholder="" value="" disabled>
                                <input class="form-control additionalServiceTotalPrice" type="text" style="display:none;float:left;" id="additionalServiceTotalPrice" {{--name="additionalServiceTotalPrice"--}} placeholder="" value="" {{--form="serviceForm"--}}>
                            </div>
                        </div>

                        <div style="margin-top:2em;" id="divButton">
                            <div class="input-group">
                                <button type="button" id="additionalServiceFormAdd" class="btn btn-primary additionalServiceFormAdd" {{--form="serviceForm"--}}disabled>
                                    <span class="fa fa-plus" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    <input type="number" style="display:none;float:left;" id="additionalServicesCount" name="additionalServicesCount" value="{{count($additionalCharges)}}">

                        @if(count($additionalCharges) > 0)
                        @foreach($additionalCharges as $additionalCharge)
                        <input type='text' style='display:none;float:left;' id='additionalServiceID{{$loop->iteration}}' name='additionalServiceID{{$loop->iteration}}' value='{{$additionalCharge->id}}'>
                        <div class='col-md-3 mb-1' id='divServiceName{{$loop->iteration}}'>
                            <input class='form-control paxSelect' type='text' name='additionalServiceName{{$loop->iteration}}' value='{{$additionalCharge->serviceName}}' readonly>
                        </div>
                        <div class='col-md-2 mb-1' id='divQuantity{{$loop->iteration}}'>
                            <input class='form-control paxSelect' type='number' id='additionalServiceNumberOfPax' name='additionalServiceNumberOfPax{{$loop->iteration}}' value='{{$additionalCharge->quantity}}' min='1' max='10' {{--form='serviceForm'--}} readonly>
                        </div>
                        <div class='col-md-3 mb-1' id='divUnitPrice{{$loop->iteration}}'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <span class='input-group-text'>₱</span>
                                </div>
                            <input class='form-control additionalServiceUnitPrice' type='text' id='additionalServiceUnitPrice' name='additionalServiceUnitPrice{{$loop->iteration}}' placeholder='' value='{{$additionalCharge->price}}' readonly>
                            </div>
                        </div>
                        <div class='col-md-3 mb-1' id='divTotalPrice{{$loop->iteration}}'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <span class='input-group-text'>₱</span>
                                </div>
                            <input class='form-control additionalServiceTotalPrice' type='text' id='additionalServiceTotalPrice' name='additionalServiceTotalPrice{{$loop->iteration}}' placeholder='' value='{{$additionalCharge->totalPrice}}' readonly>
                            </div>
                        </div>
                        <div id='divButton{{$loop->iteration}}'>
                            <div class='input-group'>
                                <button type='button' id='additionalServiceFormRemove{{$loop->iteration}}' value='{{$loop->iteration}}' class='btn btn-danger additionalServiceFormRemove'>
                                    <span class='fa fa-minus' aria-hidden='true'></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    
                    <div class="pt-4" style="float:right;">   
                        {{--<a href="/getDates" style="text-decoration:none;"> --}}                 
                        {{--<button class="btn btn-info" id="checkAvailability" style="width:10em;" type="button">Check Availability</button>--}}
                        {{--</a>--}}
                        <button class="btn btn-success" id="saveReservationButton" style="width:10em;" type="submit">Save</button>
                        <a id="{{$reservation->id}}" class="cancel-all-reservations-modal" data-toggle="modal" data-target="#removeReservationModal">
                            <button class="btn btn-danger" style="width:10em;"> Release </button>
                        </a>
                        <a href="/glamping" style="text-decoration:none;">
                            <button class="btn btn-secondary" style="width:10em;" type="button">Cancel</button>
                        </a>
                    </div>
            </div>
        </div>
    <!-- charges modal -->
    <div class="modal fade" id="chargesModal" tabindex="-1" role="dialog" aria-labelledby="chargesModal" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:70%">
            <div class="modal-content">
                <div id="selectedPayments" style="display:none;">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Charges</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body my-0">
                    <!--form class="card my-0"-->
                        <table class="table table-striped m-0 display nowrap transactionTable" style="font-size:1em;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th scope="col" style="width:40%">
                                        <input class="form-check-input" type="checkbox" id="selectAll" checked>
                                        Description
                                    </th>
                                    <th scope="col">Qty.</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Total</th> 
                                </tr>
                            </thead>
                            <tbody id="chargesRows">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th colspan="3" scope="row">Amount due:</th>
                                    <th id="chargesGrandTotal" style="text-align:right;">1500</th>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th scope="row">Amount paid:</th>
                                    <th style="text-align:right;"  colspan="3">
                                        <input type="number" name="amountPaid" placeholder="0" min="0" style="text-align:right;" class="form-control" id="amount">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="savePayments" class="btn btn-success" data-dismiss="modal">Save Changes</button>
                    <!--button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button-->
                </div>
            </div>
        </div>
    </div>
    <!-- end of charges modal -->      
    <!-- cancel reservation modal -->
    <div class="modal fade" id="removeReservationModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cancelAllReservationsModalBody">
                </div>
                <div class="modal-footer">
                    <a href="" id="confirmCancel"><button type="button" class="btn btn-danger" style="width:5em;">Yes</button></a>
                    <button type="button" class="btn btn-primary" style="width:5em;" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>     
    <!-- end of cancel reservation modal -->          
        @endforeach  
        @endforeach  
        @endif
    @endif
@endsection