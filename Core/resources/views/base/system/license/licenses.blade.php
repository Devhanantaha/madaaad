@extends('core::base.layouts.master')
@section('title')
    {{ translate('Licenses') }}
@endsection
@section('custom_css')
    <style>
        .license-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header-custom {
            background: linear-gradient(135deg, #0a0992 0%, #0c55aa 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .card-header-custom h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .card-header-custom p {
            opacity: 0.9;
            font-size: 13px;
            margin-bottom: 0;
        }

        .card-body-custom {
            padding: 25px;
            flex-grow: 1;
        }

        .license-key {
            background: #f8f9fa;
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: bold;
            color: #333;
            letter-spacing: 1px;
            word-break: break-all;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #666;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .badge-custom {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-extended {
            background: #fff3cd;
            color: #856404;
        }

        .badge-regular {
            background: #d1ecf1;
            color: #0c5460;
        }

        .domains-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .domains-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            font-weight: 600;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .domain-item {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .domain-url {
            color: #667eea;
            font-weight: 500;
            font-size: 12px;
            display: block;
            margin-bottom: 4px;
        }

        .domain-status {
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .domain-status.active {
            color: #28a745;
        }

        .domain-status.inactive {
            color: #dc3545;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-dot.active {
            background: #28a745;
            animation: pulse 2s infinite;
        }

        .status-dot.inactive {
            background: #dc3545;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .btn-remove {
            width: 100%;
            margin-top: 20px;
        }

        .card-footer-custom {
            background: #f8f9fa;
            padding: 15px 25px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 32px;
            }

            .card-header-custom h2 {
                font-size: 20px;
            }
        }

        .backup-generate-btn {
            text-decoration: none;
            padding: 10px 20px;
            background: tomato;
            display: inline-flex !important;
            align-items: center;
            margin: 0;
            color: white;
        }

        .lds-ellipsis {
            display: inline-block;
            position: relative;
            width: 70px;
            height: 13px;
        }

        .lds-ellipsis div {
            position: absolute;
            top: 0px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #fff;
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }

        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }

        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(0);
            }
        }

        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(24px, 0);
            }
        }
    </style>
@endsection
@section('main_content')
    <div class="row mb-3">
        @forelse ($licenses as $key=>$license)
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="license-card">
                    <div class="card-header-custom">
                        <h2 class="text-capitalize text-white">
                            {{ $license->details['productTitle'] ?? $license->details['productTitle'] }}</h2>
                        <p>{{ translate('License Information') }}</p>
                    </div>

                    <div class="card-body-custom">
                        <div class="license-key mb-3">
                            {{ $license->license_key }}
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ translate('Client Name') }}</div>
                            <div class="info-value">
                                {{ $license->details['clientName'] ?? $license->details['clientName'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ translate('Client Email') }}</div>
                            <div class="info-value">
                                {{ $license->details['clientEmail'] ?? $license->details['clientEmail'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ translate('Product') }}</div>
                            <div class="info-value">
                                {{ $license->details['productTitle'] ?? $license->details['productTitle'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ translate('License Type') }}</div>
                            <div class="info-value">
                                <span
                                    class="badge-custom badge-extended">{{ $license->details['licenseType'] ?? $license->details['licenseType'] }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ translate('Status') }}</div>
                            <div class="info-value">
                                <span
                                    class="badge-custom badge-active">{{ $license->details['status'] ?? $license->details['status'] }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ translate('Activated on') }}</div>
                            <div class="info-value">
                                {{ $license->details['activatedAt'] ?? $license->details['activatedAt'] }}</div>
                        </div>

                        <div class="domains-section">
                            <div class="domains-title">{{ translate('Authorized Domains') }}</div>
                            @php
                                $domains = $license->details['domains'] ? $license->details['domains'] : [];
                            @endphp
                            @if (sizeof($domains) > 0)
                                @foreach ($domains as $domain)
                                    <div class="domain-item">
                                        <a href="{{ $domain['domain'] }}" class="domain-url">{{ $domain['domain'] }}</a>
                                        @if ($domain['isActive'])
                                            <div class="domain-status active">
                                                <span class="status-dot active"></span>
                                                Active
                                            </div>
                                        @else
                                            <div class="domain-status inactive">
                                                <span class="status-dot inactive"></span>
                                                Inactive
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="domain-item">
                                    <p>{{ translate('No domain Activated') }}</p>
                                </div>
                            @endif
                        </div>

                        <button class="btn btn-danger remove-license mt-2" data-license="{{ $license->license_key }}"
                            data-item="{{ $license->item }}">
                            {{ translate('Remove License') }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-3 mx-auto">
                <p>{{ translate('No license found') }}</p>
            </div>
        @endforelse
    </div>
    <!--Delete Modal-->
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Remove Confirmation') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to remove this license') }}?</p>
                    <form method="POST" action="{{ route('core.license.remove') }}">
                        @csrf
                        <input type="hidden" id="license" name="license">
                        <input type="hidden" id="item" name="item">
                        <button type="button" class="btn long mt-2 btn-danger"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
                        <button type="submit" class="btn long mt-2">{{ translate('Remove') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal-->
@endsection

@section('custom_scripts')
    <script type="application/javascript">
        (function($) {
            "use strict";
           /**
            * Open remove license modal
            **/
           $('.remove-license').on('click',function(e){
             e.preventDefault();
             let license=$(this).data('license');
             let item=$(this).data('item');
             $("#license").val(license);
             $("#item").val(item);
             $('#delete-modal').modal('show');
           });
        })(jQuery);
    </script>
@endsection
