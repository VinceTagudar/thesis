@extends('layouts.app')

@section('content')
    @foreach ($guest as $guestDetails)
    <div class="container pb-5">
        <div class="py-3 text-center">
            <a href="{{ URL::previous() }}">
                <span style="float:left;">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <strong>Back</strong>
                </span>
            </a>
            <h3>Check-out Form</h3>
        </div>
        
        <form method="POST" action="/checkoutGlamping">
        <div class="row" role="tablist" aria-multiselectable="true">
            <div class="col-md-4 order-md-2 mb-4 mx-0 px-0">
                <!-- Payment Transactions Accordion -->
                <div id="accordion">
                    <!-- All Paid Transations -->
                    <div id="selectedAdditionalPayments" style="display:none;">
                    </div>
                    <div class="card my-0">
                        <p class="card-header" role="tab" id="headingOne">
                            <a class="collapsed d-block" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size:1.1em;">
                                Paid Charges
                            </a>
                        </p>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body p-0">
                                <table class="table table-striped m-0 display nowrap transactionTable" style="font-size:.88em;">
                                    <thead>
                                        <tr>
                                        @if(count($payments) > 0)
                                            <th scope="col" style="width:55%">Description</th>
                                            <th scope="col">Qty.</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Amount</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                            $totalPayment = 0;
                                            $balance = 0;
                                            $amountPaid = 0;
                                        @endphp
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td>{{$payment->serviceName}}</td>
                                            <td style="text-align:right;">{{$payment->quantity}}</td>
                                            {{--<td style="text-align:right;">{{number_format($payment->price, 2)}}</td>--}}
                                        @php
                                            $amountPaid = ($payment->totalPrice) - ($payment->balance);
                                        @endphp
                                            <td style="text-align:right;">{{number_format($payment->totalPrice, 2)}}</td>
                                            <td style="text-align:right;">{{number_format($amountPaid, 2)}}</td>
                                        @php
                                            $total += $payment->totalPrice;
                                            $totalPayment += $amountPaid;
                                            //$totalPayment += $payment->amount;
                                            //$balance = $total - $totalPayment;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" scope="row">TOTAL:</th>
                                            <th style="text-align:right;">₱&nbsp;{{number_format($total, 2)}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" scope="row">AMOUNT:</th>
                                            <th style="text-align:right;">₱&nbsp;{{number_format($totalPayment, 2)}}</th>
                                        </tr>
                                    </tfoot>
                                    @else
                                        <td class="text-center">
                                            No payments to show
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Unpaid Charges -->
                    <div class="card my-0">
                        <p class="card-header" role="tab" id="headingTwo">
                            <a class="collapsed d-block" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-size:1.1em;">
                                Pending Charges
                            </a>
                        </p>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body p-0">
                                <table class="table table-striped m-0 display nowrap transactionTable" style="font-size:.88em;">
                                    <thead>
                                        @if(count($pendingPayments) > 0)
                                        <tr>
                                            <th scope="col" style="width:55%">Description</th>
                                            <th scope="col">Qty.</th>
                                            <th scope="col">Total</th> 
                                            <th scope="col">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceRows">
                                        @php
                                            $total = 0;
                                            $totalPayment = 0;
                                            $balance = 0;
                                            $totalBalance = 0;
                                        @endphp
                                        @foreach($pendingPayments as $pending)
                                        @php
                                            $total += $pending->totalPrice;
                                            $totalBalance += $pending->balance;

                                            $identifier = 'Pending'.$loop->iteration;
                                        @endphp
                                        <tr id="invoiceRow{{$identifier}}">
                                            <td id="invoiceDescription{{$identifier}}" class="invoiceDescriptions">{{$pending->serviceName}}</td>
                                            <td style="display:none;"><input type="text" name="charge{{$loop->index}}" value="{{$pending->chargeID}}"></td>
                                            <td style="display:none;"><input id="invoiceCheckBox{{$identifier}}" class="form-check-input invoiceCheckboxes" type="checkbox" checked></td>
                                            <td id="invoiceQuantity{{$identifier}}"style="text-align:right;" class="invoiceQuantities">{{$pending->quantity}}</td>
                                            <td id="invoicePrice{{$identifier}}"style="text-align:right;" class="invoicePrices">{{(number_format($pending->totalPrice, 2))}}</td>
                                            @if($pending->remarks == 'unpaid')
                                            <td id="invoiceBalance{{$identifier}}" style="text-align:right;" class="invoiceBalances">{{(number_format($pending->balance, 2))}}</td>
                                            @else
                                            <td id="invoiceBalance{{$identifier}}" style="text-align:right;" class="invoiceBalances">{{number_format($pending->balance, 2)}}</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" scope="row">TOTAL:</th>
                                            <th id="invoiceGrandTotal" style="text-align:right;">₱&nbsp;{{number_format($total, 2)}}</th>
                                            <th></th>
                                        </tr>                                        
                                        <tr id="rowAmountPaid" style="display:none">
                                            <th colspan="3" scope="row">AMOUNT PAID:</th>
                                            <th id="invoiceAmountPaid" style="text-align:right;"></th>
                                        </tr>    
                                        <tr>
                                            <th colspan="3" scope="row">BALANCE:</th>
                                            <th id="invoiceTotalBalance" style="text-align:right;">₱&nbsp;{{number_format($totalBalance, 2)}}</th>
                                        </tr>
                                                                               
                                        <tr style="display:none;">
                                            <input type="number" name="chargesCount" style="display:none;" value="{{count($pendingPayments)}}">
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                            @if(count($pendingPayments) > 0)
                                            <button type="button" class="btn btn-primary btn-block" id="showChargesModal" data-toggle="modal" data-target="#chargesModal">
                                                Get payment
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-primary btn-block" id="showChargesModal" data-toggle="modal" data-target="#chargesModal" disabled>
                                                Get payment
                                            </button> 
                                            @endif
                                            <a href="/view-guests-payments/{{$guestDetails->accommodationID}}" style="text-decoration: none">
                                            <button type="button" class="btn btn-info btn-block mt-1" id="viewGuestPaymentsButton">
                                                View full payment details
                                            </button></a> 
                                            </td>
                                            </tr>
                                    </tfoot>
                                    @else
                                        <tr style="">
                                            <th scope="col" style="width:55%">Description</th>
                                            <th scope="col">Qty.</th>
                                            <th scope="col">Total</th> 
                                            <th scope="col">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceRows" style="">
                                        <!--tr>
                                            <td ></td>
                                            <td style="text-align:right;"></td>
                                            <td style="text-align:right;"></td>
                                            <td style="text-align:right;" class="invoicePrices"></td>
                                            <td style="text-align:right;"></td>
                                        </tr-->
                                        <tr>
                                            <td colspan="5" id="noPendingPayments" class="text-center">
                                                No pending payments to show
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot style="">
                                        <tr>
                                            <th colspan="2" scope="row">TOTAL:</th>
                                            <th id="invoiceGrandTotal" style="text-align:right;"></th>
                                            <th></th>
                                        </tr>
                                        <tr id="rowAmountPaid" style="display:none">
                                            <th colspan="3" scope="row">AMOUNT PAID:</th>
                                            <th id="invoiceAmountPaid" style="text-align:right;"></th>
                                        </tr>    
                                        <tr>
                                            <th colspan="3" scope="row">BALANCE:</th>
                                            <th id="invoiceTotalBalance" class="invoiceTotalBalance" style="text-align:right;"></th>
                                        </tr> 
                                        <tr>
                                            <td colspan="4">
                                            @if(count($pendingPayments) > 0)
                                            <button type="button" class="btn btn-primary btn-block" id="showChargesModal" data-toggle="modal" data-target="#chargesModal">
                                                Get payment
                                            </button>
                                             @else
                                            <button type="button" class="btn btn-primary btn-block" id="showChargesModal" data-toggle="modal" data-target="#chargesModal" disabled>
                                                Get payment
                                            </button> 
                                            @endif
                                            <a href="/view-guests-payments/{{$guestDetails->accommodationID}}" style="text-decoration: none">
                                            <button type="button" class="btn btn-info btn-block mt-1" id="viewGuestPaymentsButton">
                                                View full payment details
                                            </button></a> 
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Payment Transactions Accordion -->
            </div>
            <div class="col-md-8 order-md-1 check-out-form">
        @if(count($dueToday) > 0)
            @foreach($dueToday as $due)
                
            @endforeach
            @if($due && $remaining > 1)
                <div class="container glamping-accomodation-tabs pb-4 px-0">
                    <ul class="nav nav-tabs pt-0" style="width:100%">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color:#505050;" href="/checkout-glamping-due-today/{{$guestDetails->unitID}}">Due today</a>
                        </li>
                    </ul>
                </div>
            @else
            @endif
        @endif
                <div>
                    @csrf
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">                    
                    <input type="hidden" name="accommodationID" value="{{$guestDetails->accommodationID}}">

                    <div class="form-group col-md-6" style="position: absolute;">
                        <input type="text" name="guestID" required="required" class="form-control" style="display:none" value="{{$guestDetails->guestID}}">
                        <input style="display:none" class="form-control" type="text" name="unitID" value="{{$guestDetails->unitID}}">
                    </div>
                    <h5 style="margin-bottom:.80em;">Guest Details</h5>
                    <div id="guestDetails">
                        <div class="form-group row">
                            <div class="col-md-4 mb-1">
                                <label for="firstName">First name</label>
                                <input class="form-control" type="text" name="firstName" maxlength="15" placeholder="" value="{{$guestDetails->firstName}}" disabled>
                            </div>
                            <div class="col-md-5 mb-1">
                                <label for="lastName">Last name</label>
                                <input class="form-control" type="text" name="lastName"  maxlength="20" placeholder="" value="{{$guestDetails->lastName}}" disabled>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label for="unitNumberOfPax">No. of pax</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input class="form-control numberOfPaxGlamping" name="numberOfPaxGlamping" type="number" placeholder="" value="{{$guestDetails->numberOfPax}}" disabled>
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
                                    <input class="form-control" type="text" name="contactNumber" maxlength="11" placeholder="" value="{{$guestDetails->contactNumber}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="glamping">Accommodation</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="glamping" maxlength="11" placeholder="" value="Glamping" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4">

                    <h5 style="margin-bottom:.80em;">Unit Details</h5>
                    <div class="form-group row">
                        @if($guestDetails->numberOfUnits > 1)
                            <input type="number" style="display:none;float:left;" id="unitCheckoutCount" name="unitCheckoutCount" value="{{$guestDetails->numberOfUnits}}">
                                {{--<div class="col-md-1 mb-1" id="divUnitNumberCheckbox">
                                    <input type='checkbox' class='custom-control-input unitNumberCheckboxes'>
                                    @foreach($otherUnits as $units)
                                    <input type='checkbox' class='custom-control-input unitNumberCheckboxes'>
                                    @endforeach
                                </div>--}}
                                <div class="col-md-1 mb-1" id="divUnitCheckoutCheckboxes">    
                                    <div class="custom-control custom-checkbox">
                                        <input type='checkbox' class='custom-control-input' id="selectAllUnitCheckoutCheckboxes" checked>
                                        <label class="custom-control-label" for="selectAllUnitCheckoutCheckboxes" style="text-align:center; font-weight:bold;"></label>
                                    </div>
                                    
                                    @php
                                        $unitNumbers = array();
                                    @endphp

                                    @foreach($otherUnits as $units)
                                    @php
                                        array_push($unitNumbers, $units->unitID);
                                    @endphp
                                    <div class="custom-control custom-checkbox my-3">
                                        <input type='checkbox' class='custom-control-input unitCheckoutCheckboxes' name="unitCheckoutCheckbox{{$units->unitID}}" value="{{$units->unitID}}" id="unitCheckoutCheckbox{{$units->unitID}}" checked>
                                        <label class="custom-control-label" for="unitCheckoutCheckbox{{$units->unitID}}" style="text-align:center; font-weight:bold;">{{$loop->iteration}}</label>
                                    </div>
                                    @endforeach

                                    @php
                                        $unitNumbers = implode(",", $unitNumbers);
                                    @endphp
                                    
                                    <input type="hidden" name="unitCheckout" id="unitCheckout" value="{{$unitNumbers}}">
                                </div>
                                {{--<div class="col-md-1 mb-1" id="divUnitNumber">
                                    <input type="text" readonly class="form-control-plaintext" style="text-align:center;" value="" disabled>
                                    @foreach($otherUnits as $units)
                                    <input type="text" readonly class="form-control-plaintext mb-1" style="text-align:center; font-weight:bold;" value="{{$loop->iteration}}">
                                    @endforeach
                                </div>--}}
                                <div class="col-md-2 mb-1" style="margin-left=0; padding-left:0;" id="divUnitNumber">
                                    <label for="unitNumber">Unit no.</label>
                                    @foreach($otherUnits as $units)
                                    <input type="text" class="form-control mb-1" value="{{$units->unitNumber}}" disabled>
                                    @endforeach
                                </div>
                                <div class="col-md-3 mb-1" id="divAccommodationPackage">
                                    <label for="additionalServiceUnitPrice">Package</label>
                                    @foreach($otherUnits as $units)
                                    
                                    {{--@if($guestDetails->serviceName = 'Glamping Solo')
                                        <input class="form-control mb-1" value="Solo" name="accommodationType" id="accommodationType" readonly>
                                    @elseif($guestDetails->serviceName = 'Glamping 2 pax')
                                        <input class="form-control mb-1" value="2 pax" name="accommodationType" id="accommodationType" readonly>
                                    @elseif($guestDetails->serviceName = 'Glamping 3 pax')
                                        <input class="form-control mb-1" value="3 pax" name="accommodationType" id="accommodationType" readonly>
                                    @elseif($guestDetails->serviceName = 'Glamping 4 pax')
                                        <input class="form-control mb-1" value="4 pax" name="accommodationType" id="accommodationType" readonly>
                                    @endif--}}
                                    <input class="form-control mb-1" value="{{$units->serviceName}}" name="accommodationType" id="accommodationType" readonly>
                                    @endforeach
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="checkInDatetime">Check-in date</label>
                                     @foreach($otherUnits as $units)
                                    <div class="input-group mb-1">
                                        @php
                                            $checkedIn = new DateTime($units->checkinDatetime);
                                            $checkedInAt = $checkedIn->format("F j, o");
                                        @endphp
                                    <input class="form-control" type="text" name="checkedInAt" placeholder="" value="{{$checkedInAt}}" disabled>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="checkoutDatetime">Check-out date</label>
                                     @foreach($otherUnits as $units)
                                    <div class="input-group mb-1">
                                        @php
                                            $checkOut = new DateTime($units->checkoutDatetime);
                                            $checkOutAt = $checkOut->format("F j, o");
                                        @endphp
                                    <input class="form-control" type="text" name="checkOutAt" placeholder="" value="{{$checkOutAt}}" disabled>
                                    </div>
                                    @endforeach
                                </div>
                        @else   
                                <input type="number" style="display:none;float:left;" id="checkOutOneUnit" name="checkOutOneUnit" value="{{$guestDetails->numberOfUnits}}">
                                <div class="col-md-3 mb-1" id="divUnitNumber">
                                    <label for="unitNumber">Unit no.</label>   
                                    <input type="text" class="form-control mb-1" value="{{$guestDetails->unitNumber}}" disabled>
                                </div>
                                <div class="col-md-3 mb-1" id="divAccommodationPackage">
                                    <label for="additionalServiceUnitPrice">Package</label>
                                    {{--<select class="form-control mb-1" name="accommodationType" id="accommodationType" readonly>
                                        <option>{{$guestDetails->serviceName}}</option>
                                    </select>--}}
                                    @if($guestDetails->serviceName = 'Glamping Solo')
                                        <input class="form-control mb-1" value="Solo" name="accommodationType" id="accommodationType" readonly>
                                    @elseif($guestDetails->serviceName = 'Glamping 2 pax')
                                        <input class="form-control mb-1" value="2 pax" name="accommodationType" id="accommodationType" readonly>
                                    @elseif($guestDetails->serviceName = 'Glamping 3 pax')
                                        <input class="form-control mb-1" value="3 pax" name="accommodationType" id="accommodationType" readonly>
                                    @elseif($guestDetails->serviceName = 'Glamping 4 pax')
                                        <input class="form-control mb-1" value="4 pax" name="accommodationType" id="accommodationType" readonly>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="checkInDatetime">Check-in date</label>
                                    <div class="input-group mb-1">
                                        @php
                                            $checkedIn = new DateTime($guestDetails->checkinDatetime);
                                            $checkedInAt = $checkedIn->format("F j, o");
                                        @endphp
                                    <input class="form-control" type="text" name="checkedInAt" placeholder="" value="{{$checkedInAt}}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="checkoutDatetime">Check-out date</label>
                                    <div class="input-group mb-1">
                                        @php
                                            $checkOut = new DateTime($guestDetails->checkoutDatetime);
                                            $checkOutAt = $checkOut->format("F j, o");
                                        @endphp
                                    <input class="form-control" type="text" name="checkOutAt" placeholder="" value="{{$checkOutAt}}" disabled>
                                    </div>
                                </div>
                        @endif
                    </div>
                    <hr class="mb-4 mt-4">
                    <div class="form-group row pb-3" id="divAdditionalServices">
                        <div class="col-md-12 mb-1">
                            <h5 style="margin-bottom:.80em;">Damage Charges</h5>
                        </div>
                        <input type="hidden">
                        <div class="col-md-3 mb-1" id="divServiceName">
                            <label for="additionalServiceName">Charge name</label>
                            <select name="additionalServiceName" id="serviceSelect" class="form-control serviceSelect">
                                <option value="choose" selected disabled >Choose...</option>
                                <option value="8">Shoe box</option>
                                <option value="9">Pillow case</option>                                
                                <option value="10">Pillow</option>
                                <option value="11">Blanket</option>
                                <option value="12">Bedsheet</option>
                                <option value="13">Foam</option>
                                <option value="14">Tent</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1" id="divQuantity">
                            <label for="additionalServiceNumberOfPax">Quantity</label>
                            <input class="form-control paxSelect" type="number" id="additionalServiceNumberOfPax" placeholder="" value="" min="1" max="10">
                        </div>
                        <div class="col-md-3 mb-1" id="divUnitPrice">
                            <label for="additionalServiceUnitPrice">Unit price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input class="form-control additionalServiceUnitPrice" type="text" id="additionalServiceUnitPrice" name="additionalServiceUnitPrice" placeholder="" value="" disabled>
                                <input class="form-control additionalServiceUnitPrice" type="text" style="display:none;float:left;" id="additionalServiceUnitPrice" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-3 mb-1" id="divTotalPrice">
                            <label for="additionalServiceTotalPrice">Total price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input class="form-control additionalServiceTotalPrice" type="text" id="additionalServiceTotalPrice" name="additionalServiceTotalPrice" placeholder="" value="" disabled>
                                <input class="form-control additionalServiceTotalPrice" type="text" style="display:none;float:left;" id="additionalServiceTotalPrice" placeholder="" value="">
                            </div>
                        </div>
                        <div style="margin-top:2em;" id="divButton">
                            <div class="input-group">
                                <button type="button" id="additionalServiceFormAddExtra" class="btn btn-primary additionalServiceFormAddExtra">
                                    <span class="fa fa-plus" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>

                        
                        <input type="number" style="display:none;float:left;" id="additionalServicesCount" name="additionalServicesCount" value="0">
                    </div>
                
                    {{--<input class="form-control" type="number" name="numberOfAdditionalCharges" value="1" style="display:none; position:absolute;">
                    <input class="form-control" type="text" name="serviceID1" value="6" style="display:none; position:absolute;">
                    <input class="form-control" type="number" name="numberOfPaxAdditional1" value="5" style="display:none; position:absolute;">
                    <input class="form-control" type="text" name="paymentStatus1" value="paid" style="display:none; position:absolute;">--}}

                    <div class="mt-3" style="float:right;">
                    {{--<button type="button" class="btn btn-success" id="checkoutWarning" style="width:10em" data-toggle="modal" data-target="#unpaidChargesModal">Check-out</button>--}}
                    @if(count($pendingPayments) > 0)
                        <button id="checkoutButton" class="btn btn-success" style="width:10em;display:none">Check-out</button>
                        <button type="button" class="btn btn-success" id="checkoutWarning" style="width:10em" data-toggle="modal" data-target="#unpaidChargesModal">Check-out</button>
                    @else
                        <button id="checkoutButton" class="btn btn-success" style="width:10em;">Check-out</button>
                        <button type="button" class="btn btn-success" id="checkoutWarning" style="width:10em;display:none" data-toggle="modal" data-target="#unpaidChargesModal">Check-out</button>
                    @endif
                        <a style="text-decoration:none;">
                            <button class="btn btn-secondary" style="width:11em;" type="button" id="cancelChanges">Cancel</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- charges modal -->
    <div class="modal fade" id="chargesModal" tabindex="-1" role="dialog" aria-labelledby="chargesModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Charges</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card my-0">
                        <table class="table table-striped m-0 display nowrap transactionTable" style="font-size:1em;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th scope="col" style="width:50%">
                                        <input class="form-check-input" type="checkbox" id="selectAllBalances" checked>
                                        Description
                                    </th>
                                    <th scope="col">Qty.</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Balance</th>
                                </tr>
                            </thead>
                            <tbody id="chargesRows">
                                <tr>
                                    <td></td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="charge1" checked>
                                        Glamping 4 pax
                                    </td>
                                    <td style="text-align:right;">4</td>
                                    <td style="text-align:right;" class="chargesPrices">3400</td>
                                    <td>
                                        <button type="button" id="deleteCharge1" class="btn btn-sm btn-danger deleteCharge">
                                            <span class="fa fa-minus" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="charge2" checked>
                                        Airsoft
                                    </td>
                                    <td style="text-align:right;">2</td>
                                    <td style="text-align:right;" class="chargesPrices">1500</td>
                                    <td>
                                        <button type="button" id="deleteCharge1" class="btn btn-sm btn-danger deleteCharge">
                                            <span class="fa fa-minus" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th colspan="3" scope="row">Amount due:</th>
                                    <th id="invoiceTotalBalanceModal" class="invoiceTotalBalance" style="text-align:right;"></th>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th colspan="2" scope="row">Amount paid:</th>
                                    <th style="text-align:right;"  colspan="2">
                                        <input type="number" name="amountPaid" placeholder="0" min="0" style="text-align:right;" class="form-control" id="amount">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>                        
                     </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveAllPayments" type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end of charges modal -->
    <!-- unsaved changes modal -->
    <div class="modal fade" id="unsavedChangesModal" tabindex="-1" role="dialog" aria-labelledby="unsavedChagesModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unsaved changes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> You have unsaved changes. Are you sure you want to leave this page? </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" style="width:5em;">Yes</button>
                    <button type="button" class="btn btn-primary" style="width:5em;" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- unsaved changes modal -->
    <!-- unpaid charges modal -->
    <div class="modal fade" id="unpaidChargesModal" tabindex="-1" role="dialog" aria-labelledby="unpaidChargesModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Unpaid Charges</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>There are charges left unpaid in this accommodation. Either go back and get the full payment or modify the transaction. What would you like to do?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger">Modify transaction</button>
                        <button type="button" class="btn btn-primary" style="width:8em;" data-dismiss="modal">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- unnpaid charges modal -->
    @endforeach
@endsection
 