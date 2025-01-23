
@extends('backend.layout.master')
@section('title', __('All Languages'))
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Languages') }}</h4>
                            @can('language-add')
                            <x-btn.add-modal :title="__('Add Languages')" />
                            @endcan
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04">
                                <x-validation.error/>
                                <table class="DataTable_activation">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Direction')}}</th>
                                        <th>{{__('Slug')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Default')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($all_lang as $lang)
                                        <tr>
                                            <td>{{ $lang->name }}</td>
                                            <td>{{ ucfirst($lang->direction) }}</td>
                                            <td>{{ $lang->slug }}</td>
                                            <td>{{ ucfirst($lang->status) }}</td>
                                            <td>
                                                @if($lang->default == 1)
                                                    <span>{{ __('Yes') }}</span>
                                                @else
                                                    <span>{{ __('No') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <x-status.table.select-action :title="__('Select Action')"/>
                                                <ul class="dropdown-menu status_dropdown__list">
                                                    @can('language-edit')
                                                    <li class="status_dropdown__item">
                                                        <a
                                                            class="btn dropdown-item status_dropdown__list__link edit_language_modal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editLanguageModal"
                                                            data-id="{{$lang->id}}"
                                                            data-name="{{$lang->name}}"
                                                            data-slug="{{$lang->slug}}"
                                                            data-status="{{$lang->status}}"
                                                            data-direction="{{$lang->direction}}"
                                                        >
                                                            {{ __('Edit Language') }}
                                                        </a>
                                                    </li>
                                                    @endcan
                                                    @can('language-word-edit')
                                                    <li class="status_dropdown__item">
                                                        <a href="{{ route('admin.languages.words.all',$lang->slug) }}" class="btn dropdown-item status_dropdown__list__link">
                                                            {{ __('Edit All Words') }}
                                                        </a>
                                                    </li>
                                                   @endcan
                                                    @can('language-edit')
                                                        @if($lang->default != 1)
                                                        <li class="status_dropdown__item">
                                                            <a href="{{ route('admin.languages.make.default',$lang->id) }}" class="btn dropdown-item status_dropdown__list__link">
                                                                {{ __('Make Default') }}
                                                            </a>
                                                        </li>
                                                        @endif
                                                    @endcan
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.languages.add-modal')
    @include('backend.pages.languages.edit-modal')
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    @include('backend.pages.languages.language-js')
@endsection
