
@extends('backend.layout.master')
@section('title', __('Edit All Words'))
@section('style')
    <x-media.css />
    <link rel="stylesheet" href="{{asset('assets/backend/css/custom-style.css')}}">
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Edit All Words') }}</h4>
                            <a href="{{route('admin.languages')}}" class="btn btn-primary">{{__('All Languages')}}</a>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error/>
                            <div class="card">
                                <div class="card-body">
                                    <div class="top-part d-flex justify-content-between margin-bottom-40">
                                        <div class="left-item">
                                            @can('language-word-add')
                                            <button
                                                class="btn btn-secondary btn-xs"
                                                data-bs-toggle="modal"
                                                data-bs-target="#view_quote_details_modal"
                                            >{{__('Add New Word')}}</button>
                                            @endcan
                                            <a href="#" id="regenerate_source_text_btn" class="btn btn-warning ">{{__('Regenerate Source Texts')}}</a>
                                        </div>
                                    </div>
                                    <p class="text-info margin-bottom-20">{{__('select any source text to translate it, then enter your translated text in textarea hit update')}}</p>
                                    <div class="language-word-translate-box">
                                        <div class="search-box-wrapper">
                                            <input type="text" name="word_search" id="word_search" placeholder="{{__('Search Source Text...')}}">
                                        </div>
                                        <div class="top-part">
                                            <div class="single-string-wrap">
                                                <div class="string-part">{{__('Source Text')}}</div>
                                                <div class="translated-part">{{__('Translation')}}</div>
                                            </div>
                                        </div>
                                        <div class="middle-part">
                                            @if(empty($all_word))
                                                <?php $all_word = []; ?>
                                            @endif
                                                @foreach($all_word as $key => $value)
                                                    <div class="single-string-wrap">
                                                        <div class="string-part" data-key="{{$key}}">{{$key}}</div>
                                                        <div class="translated-part" data-trans="{{$value}}">{{$key === $value ? '' : $value}}</div>
                                                    </div>
                                                @endforeach

                                        </div>
                                        <div class="footer-part">
                                            <h6 id="selected_source_text"><span>{{__('Source Text:')}}</span> <strong class="text"></strong></h6>
                                            <form action="{{route('admin.languages.words.update',$lang_slug)}}" method="POST" id="langauge_translate_form" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="type" value="{{$type}}">
                                                <input type="hidden" name="string_key">
                                                <div class="single-input">
                                                    <label for="" class="label-title mt-3">{{__('Translate To')}} <strong>{{$language->name}}</strong></label>
                                                    <textarea name="translate_word" cols="30" rows="5" class="form-control" placeholder="{{__('enter your translate words')}}"></textarea>
                                                </div>
                                                @can('language-word-edit')
                                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                                                @endcan
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view_quote_details_modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add New Translate able String')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.languages.add.new.word')}}" id="user_password_change_modal_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="lang_slug" id="lang_slug" value="{{$lang_slug}}">
                        <div class="single-input">
                            <label for="new_string" class="label-title">{{__('String')}}</label>
                            <input type="text" class="form-control" name="new_string" placeholder="{{__('New String')}}">
                        </div>
                        <div class="single-input">
                            <label for="translated_string" class="label-title mt-3">{{__('Translated String')}}</label>
                            <input type="text" class="form-control" id="translated_string" name="translate_string" placeholder="{{__('Translated String')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Add New String')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <script>
        (function($){
            "use strict";

            $(document).ready(function (){
                $(document).on('click','.language-word-translate-box .middle-part .single-string-wrap .string-part',function (e){
                    e.preventDefault();
                    let langKey = $(this).data('key');
                    let langValue = $(this).next().data('trans');
                    let formContainer = $('#langauge_translate_form');
                    $('#selected_source_text strong').text(langKey);
                    formContainer.find('input[name="string_key"]').val(langKey);
                    formContainer.find('textarea[name="translate_word"]').val(langValue);
                });
                //search source text
                $(document).on('keyup','#word_search',function (e){
                    e.preventDefault();
                    let searchText = $(this).val();
                    var allSourceText = $('.language-word-translate-box .middle-part .single-string-wrap .string-part');
                    $.each(allSourceText,function (index,value){
                        var text = $(this).text();
                        var found = text.toLowerCase().match(searchText.toLowerCase().trim());
                        if (!found){
                            $(this).parent().hide();
                        }else{
                            $(this).parent().show();
                        }
                    });
                });

                $(document).on('click','#regenerate_source_text_btn',function (e){
                    e.preventDefault();
                    //admin.languages.regenerate.source.texts
                    Swal.fire({
                        title: '{{__("Are you sure?")}}',
                        text: '{{__("It will delete current source texts, you will lose your current translated data!")}}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "{{__('Yes, Generate!')}}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: "{{route('admin.languages.regenerate.source.texts')}}",
                                data: {
                                    _token : "{{csrf_token()}}",
                                    slug : "{{$language->slug}}"
                                },
                                success : function (){
                                    toastr_success_js("{{ __('Regenerate source success') }}");
                                    location.reload();
                                }
                            });
                        }
                    });

                });

            });

        })(jQuery);
    </script>
@endsection
