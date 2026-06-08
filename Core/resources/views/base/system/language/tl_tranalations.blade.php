@extends('core::base.layouts.master')
@section('title')
    {{ translate('Theme Translations') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">
        <div class="col-8">
            <div class="card mb-30">
                <div class="card-body border-bottom2">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Theme Translations') }}</h4>

                    </div>
                </div>
                <div class="table-responsive">
                    <table id="ThemeTranslationTable" class="hoverable text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Value') }}</th>
                                <th>{{ translate('Key') }}</th>
                                <th>{{ translate('Language') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lang_keys as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $value->lang_value }}</td>
                                    <td>{{ $value->lang_key }}</td>
                                    <td>English</td>
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

                                                <a href="#" class="delete-value"
                                                    data-id="{{ $value->id }}">{{ translate('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="mt-3">
                        <div class="col-12">
                            {{ $lang_keys->onEachSide(1)->appends(request()->input())->links('pagination::bootstrap-5-custom') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-4">
            <div class="form-element py-30 mb-30">
                <h4 class="font-20 mb-30">New Language Key</h4>
                <form action="{{ route('core.language.theme.translations.store') }}" method="POST">
                    @csrf
                    <div class="form-row mb-20">
                        <div class="col-sm-12">
                            <label class="font-14 bold black">Key Value in English</label>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" name="value" class="theme-input-style" value="{{ old('value') }}"
                                placeholder="Enter Value">
                            @if ($errors->has('value'))
                                <div class="invalid-input">{{ $errors->first('value') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn long">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('core.language.theme.translations.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-key-id" name="id">
                        <button type="button" class="btn long mt-2"
                            data-dismiss="modal">{{ translate('Cencel') }}</button>
                        <button type="submit" class="btn btn-danger long mt-2">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            $('.delete-value').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('id');
                $("#delete-key-id").val(id);
                $('#delete-modal').modal('show');
            });

        })(jQuery);
    </script>
@endsection
