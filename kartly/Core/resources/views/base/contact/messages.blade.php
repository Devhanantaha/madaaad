@extends('core::base.layouts.master')
@section('title')
    {{ translate('Messages') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">
        <!-- User List-->
        <div class="col-md-12">
            <div class="card mb-30">
                <div class="bg-white card-header py-3">
                    <h4>{{ translate('Messages') }}</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="hoverable text-nowrap border-top2 " id="user_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('Date') }} </th>
                                    <th>{{ translate('Name') }} </th>
                                    <th>{{ translate('Subject') }} </th>
                                    <th>{{ translate('Email') }}</th>
                                    <th>{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $key => $message)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $message->created_at->format('d M Y') }}</td>

                                        <td>{{ $message->name }}</td>
                                        <td>{{ $message->subject }}</td>
                                        <td>{{ $message->email }}</td>
                                        <td>
                                            <div class="dropdown-button">
                                                <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                                    <div class="menu-icon style--two mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="view-message"
                                                        data-message="{{ $message->message }}">
                                                        {{ translate('Details') }}
                                                    </a>
                                                    <a href="#" class="delete-message" data-id="{{ $message->id }}">
                                                        {{ translate('Delete') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            <p class="alert alert-danger text-center">{{ translate('No Data found') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- List-->

        <div id="details-modal" class="details-modal modal fade show" aria-modal="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{ translate('Message') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <p class="mt-1"><span id="message"></span></p>
                    </div>
                </div>
            </div>
        </div>
        <!--Delete Modal-->
        <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
            <div class="modal-dialog modal-sm modal-dialog-top">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mt-1">{{ translate('Are you sure to delete') }}?</p>
                        <form method="POST" action="{{ route('core.contact.messages.delete') }}">
                            @csrf
                            <input type="hidden" id="message_id" name="id">
                            <button type="button" class="btn long mt-2 btn-danger"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Delete Modal-->
    </div>
@endsection
@section('custom_scripts')
    <script type="application/javascript">
        (function($) {
            "use strict";

            $(document).on('click', '.delete-message', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#message_id').val(id);
                $('#delete-modal').modal('show');
            });

            $(document).on('click', '.view-message', function(e) {
                e.preventDefault();
                var message = $(this).data('message');
                $('#message').html(message);
                $('#details-modal').modal('show');
            });
            
        })(jQuery);
</script>
@endsection
