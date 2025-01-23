@extends('backend.layout.master')
@section('title', __('All Tickets'))
@section('style')
    <x-summernote.summernote-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Tickets') }}</h4>
                            @can('support-ticket-add')
                            <x-btn.add-modal :title="__('Add Ticket')" />
                            @endcan
                        </div>
                        @can('support-ticket-bulk-delete')
                        <div class="search_delete_wrapper">
                            <x-bulk-action.bulk-action />
                        </div>
                        @endcan

                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Tickets') }}</h4>
                            <x-search.search-in-table :id="'string_search'" :placeholder="__('Enter date to search')" />
                        </div>

                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                :description="__('Notice: The admin has the ability to create tickets for both the client and the freelancer if desired.')"
                                :description1="__('Notice: Admin can search by ticket id, ticket status, ticket priority.')"
                            />
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('supportticket::backend.ticket.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('supportticket::backend.ticket.add-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.ticket.delete.bulk.action')"/>
    @include('supportticket::backend.ticket.ticket-js')
    <x-summernote.summernote-js />
@endsection
