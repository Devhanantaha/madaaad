@extends('core::base.layouts.master')
@section('title')
    {{ translate('Banner List') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-30">
                <div class="card-body border-bottom2 mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Banners') }}</h4>
                        <a href="{{ route('plugin.ecommerce.app.configuration.banner.add') }}"
                            class="btn long">{{ translate('Add New Banner') }}</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="hoverable text-nowrap">
                        <thead>
                            <tr>
                                <th>{{ translate('#') }}</th>
                                <th>{{ translate('Image') }}</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Resource Type') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($banners->count() > 0)
                                @foreach ($banners as $key => $banner)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <img src="{{ asset(getFilePath($banner->image, true)) }}" class="img-100-auto"
                                                alt="{{ $banner->title }}">
                                        </td>
                                        <td>
                                            {{ $banner->translation('title') }}
                                        </td>
                                        <td>
                                            {{ ucwords(str_replace('_', ' ', $banner->type)) }}
                                        </td>
                                        <td>
                                            @if ($banner->status == config('settings.general_status.active'))
                                                <p class="badge badge-success">{{ translate('Active') }}</p>
                                            @else
                                                <p class="badge badge-danger">{{ translate('Inactive') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown-button">
                                                <a href="#" class="d-flex align-items-center justify-content-end"
                                                    data-toggle="dropdown">
                                                    <div class="menu-icon mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a
                                                        href="{{ route('plugin.ecommerce.app.configuration.banner.edit', ['id' => $banner->id]) }}">{{ translate('Edit') }}</a>
                                                    <a href="#" class="delete-item"
                                                        data-id="{{ $banner->id }}">{{ translate('Delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing Found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $banners->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Delete Modal-->
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('plugin.ecommerce.app.configuration.banner.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-item-id" name="id">
                        <button type="button" class="btn long mt-2 btn-danger"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
                        <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal-->
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            $('.delete-item').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('id');
                $("#delete-item-id").val(id);
                $('#delete-modal').modal('show');
            });
        })(jQuery);
    </script>
@endsection
