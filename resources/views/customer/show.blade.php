@extends('layouts.app')
@section('styles')
    <style>
        .address-line {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <div
            class="d-flex flex-column flex-sm-row align-items-center justify-content-sm-between mb-4 text-center text-sm-start gap-2">
            <div class="mb-2 mb-sm-0">
                <h4 class="mb-1 text-black">
                    Customer ID #{{ $customer->id }}
                </h4>
                <p class="mb-0">
                    {{ \Carbon\Carbon::now()->format('M d, Y, g:i A') }} (ET)
                </p>
            </div>

        </div>


        <div class="row">

            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">

                <div class="card mb-6 h-100">
                    <div class="card-body pt-12 d-flex flex-column h-100">
                        <div class="customer-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded-3 mb-4"
                                    src="https://demos.pixinvent.com/materialize-html-laravel-admin-template/demo/assets/img/avatars/1.png"
                                    height="120" width="120" alt="User avatar">
                                <div class="customer-info text-center mb-6">
                                    <h5 class="mb-0 text-black">{{ $customer->firstName }} {{ $customer->lastName }}</h5>
                                    <span>Customer ID #{{ $customer->id }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-container mt-0">
                            <h5 class="border-bottom text-capitalize pb-4 mt-6 mb-4 text-black">Details</h5>
                            <ul class="list-unstyled mb-6">
                                <li class="mb-2">
                                    <span class="h6 me-1 text-black">Email:</span>
                                    <span>{{ $customer->email }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="h6 me-1 text-black">Contact:</span>
                                    <span>{{ $customer->mobile_no }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!--/ Customer Sidebar -->


            <!-- Customer Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">

                <!-- Customer cards -->
                <div class="row text-nowrap">
                    <div class="card card-action mb-6">

                        <div class="  px-0 py-3 text-black w-100 m-0">
                            <div class="d-flex align-items-center justify-content-between px-4">
                                <h4 class="card-title text-black mb-0">Address Book</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="accordion accordion-arrow-left" id="ecommerceBillingAccordionAddress">
                                @foreach ($customer->addresses as $index => $address)
                                    @php
                                        $headingId = 'heading' . $index;
                                        $collapseId = 'collapse' . $index;
                                    @endphp

                                    <div class="accordion-item">
                                        <div class="accordion-header d-flex justify-content-between align-items-center flex-wrap flex-sm-nowrap row-gap-4"
                                            id="{{ $headingId }}">
                                            <a class="accordion-button px-2 collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}" aria-expanded="false"
                                                aria-controls="{{ $collapseId }}" role="button">
                                                <span>
                                                    <span class="d-flex gap-2 mb-1 align-items-baseline">
                                                        <span
                                                            class="h6 mb-0 text-capitalize text-black fw-bold">{{ ucfirst($address->address_type) }}</span>
                                                    </span>
                                                    <span
                                                        class="mb-0 text-body fw-normal">{{ $address->address_line }}</span>
                                                </span>
                                            </a>

                                            <div class="d-flex gap-4 p-4 p-sm-2 py-sm-0 pt-0 ms-4 ms-sm-0">
                                                <a href="javascript:void(0);"><i
                                                        class="ri-edit-box-line ri-22px text-body"></i></a>
                                                <a href="javascript:void(0);"><i
                                                        class="ri-delete-bin-7-line ri-22px text-body"></i></a>
                                                <button class="btn p-0" data-bs-toggle="dropdown" aria-expanded="false"
                                                    role="button">
                                                    <i class="ri-more-2-line ri-22px text-body"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item waves-effect" href="javascript:void(0);">Set
                                                            as default address</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                            aria-labelledby="{{ $headingId }}"
                                            data-bs-parent="#ecommerceBillingAccordionAddress">
                                            <div class="accordion-body ps-6 ms-6 ml-4" style="margin-left:25px;">
                                                <h6 class="mb-1 text-black">{{ $customer->firstName }}
                                                    {{ $customer->lastName }}</h6>
                                                @if ($address->line1)
                                                    <p class="mb-1">{{ $address->line1 }}</p>
                                                @endif
                                                @if ($address->line2)
                                                    <p class="mb-1">{{ $address->line2 }}</p>
                                                @endif
                                                <p class="mb-1">
                                                    {{ $address->city ?? '' }},
                                                    {{ $address->state ?? '' }}
                                                </p>                                     
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card card-action mb-6">
                        <div class="  px-0 py-3 text-black w-100 m-0">
                            <div class="d-flex align-items-center justify-content-between px-4">
                                <h4 class="card-title text-black mb-0">Orders placed</h4>
                            </div>
                        </div>
                        <div class="table-responsive mb-4">

                            <div class="card-body">
                                <table class="table datatables-customer-order table-bordered table-hover">
                                    <thead class="text-black fw-bold" >
                                        <tr style="font-size: 1.1rem !important; color: black !important; font-weight: bold !important;">
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Spent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customer->orders as $order)
                                            <tr>
                                                <td>
                                                    <span>#{{ $order->order_id }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-nowrap">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        switch ($order->status) {
                                                            case 0:
                                                                $badgeClass = 'warning';
                                                                $statusText = 'Pending';
                                                                break;
                                                            case 1:
                                                                $badgeClass = 'success';
                                                                $statusText = 'Completed';
                                                                break;
                                                            case 2:
                                                                $badgeClass = 'danger';
                                                                $statusText = 'Cancelled';
                                                                break;
                                                            default:
                                                                $badgeClass = 'secondary';
                                                                $statusText = 'Unknown';
                                                        }
                                                    @endphp

                                                    <span class="badge rounded-pill bg-label-{{ $badgeClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span>${{ number_format($order->total_price, 2) }}</span>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datatables-customer-order').DataTable({
                responsive: true,
                paging: true,
                ordering: true,
                info: true,
                searching: true,
                lengthChange: false
            });
        });
    </script>
@endsection
