
<script>
    @if(request()->has('freelancer_id'))
        $(document).ready(function (){
            $('.chat_item[data-freelancer-id={{ request()->freelancer_id }}]').trigger('click').addClass("active")
        })
    @endif

    /*
    ========================================
        Chat Click and Active Class
    ========================================
    */
    let oldChannelName = "";
    let liveChat, channelName;
    liveChat = new LiveChat();

    $(document).on('click', '.chat_item', function() {

        //: first need to remove all active class and after that add active class to clicked item
        $(this).siblings().removeClass('active');
        $('#client-message-footer').removeClass('d-none');
        $(this).addClass('active');
        $('.chat_wrapper__contact__close, .body-overlay').removeClass('active');
        //: now fetch all old conversation from request with header and body
        fetch_chat_data($(this).attr("data-freelancer-id"));

        $("#chat_body").attr("data-current-freelancer", $(this).attr("data-freelancer-id"))

        channelName = {
            freelancer_id: $(this).attr("data-freelancer-id"),
            client_id: "{{ auth('web')->id() }}",
            type: "client"
        };

        if(freelancer_list["freelancer_id_" + channelName.freelancer_id] != true){

            //: initialize livechat js
            liveChat.createChannel(channelName.client_id, channelName.freelancer_id, channelName.type);


            liveChat.bindEvent('livechat-freelancer-' + channelName.freelancer_id, function (data){
                if($("#chat_body").attr("data-current-user") == data.livechat?.user?.id) {
                    $("#chat_body").append(data.messageBlade);

                    scrollToBottom();
                }
                if (document.getElementById("chat-alert-sound") != undefined){
                    var alert_sound = document.getElementById("chat-alert-sound");
                    alert_sound.play();
                }
            });

            freelancer_list["freelancer_id_" + channelName.freelancer_id] = true;
            oldChannelName = channelName;
        }

        $(this).find(".chat_wrapper__contact__list__time .badge").fadeOut();
    });

    $(document).on("click","#client-send-message-to-freelancer", function (){
        //: prepare chat post data
        let file = $('#client-message-footer #message-file')[0].files[0];
        let form = new FormData();
        form.append('message', $('#client-message-footer #message').val());
        form.append('file', file !== undefined ? file : '');
        form.append('from_user', '1');
        form.append('freelancer_id', $("#livechat-message-header").attr('data-freelancer-id'));
        form.append('from', "chatbox");
        form.append('_token', "{{ csrf_token() }}");

        let messages_ = $('#client-message-footer #message').val();

        @if(moduleExists('SecurityManage'))
            //get security manage module name
            let module_exits = "<?php echo moduleExists('SecurityManage'); ?>"
            if(module_exits){
                let words = JSON.parse('<?php echo json_encode(\Modules\SecurityManage\Entities\Word::select('word')->where("status", "active")->pluck("word")->toArray()); ?>');
                // Function to check if any word exists in the string
                function checkAnyWordExists(words, messages_) {
                    return words.some(word => messages_.includes(word));
                }
                // Check if any of the words exist in the string
                let anyWordExists = checkAnyWordExists(words, messages_);

                // Function to get all matching words in the string
                function getAllMatchedWords(words, message) {
                    return words.filter(word => message.includes(word));
                }
                // Get all matching words
                let matchedWords = getAllMatchedWords(words, messages_);

                if(anyWordExists){
                    toastr_warning_js('You can not send restricted words:' + matchedWords);
                    return false;
                }
            }
        @endif

        if(messages_ != '' || file !== undefined){
            $('#client-message-footer #message').val('');
            $('#client-message-footer #message-file').val('');
            $('#client-message-footer .show_uploaded_file').text('');

            // var regex = /(bank|email|phone)/i; // List words separated by |
            // if (regex.test(messages_)) {
            //     console.log('not allow');
            //     return false;
            //     // At least one of the words exists in the string
            // }

            send_ajax_request("post", form, "{{ route("client.message.send") }}", function (){}, function (response){
                $("#chat_body").append(response);
                if(response.status == 'image_not_allow_in_demo'){
                    toastr_warning_js("{{ __('This is demonstration purpose only, you may not able to send files in demo purpose, once your purchase this script you will get access to all settings.') }}");
                }
                scrollToBottom();
            }, function (){})
        }else{
            return false;
        }
    });

    $(document).on("click",".load-more-pagination", function (){
        let el = $(this);
        let page = parseInt(el.attr('data-page'));
        let nextPage = page + 1;

        fetch_chat_data($('#livechat-message-header').attr('data-freelancer-id'), nextPage, function (){
            el.attr("data-page",nextPage);
        });
    });

    function fetch_chat_data(freelancer_id, page = 1, callback){
        //: hare call a api for fetching data from database if no data available then new item will be inserted
        let formData;

        formData = new FormData();
        formData.append("freelancer_id", freelancer_id);
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("from_user", 1)

        send_ajax_request("post", formData,`{{ route("client.fetch.chat.freelancer.record") }}?page=${page}`,function (){

        }, function (response){

            if(page > 1) {
                $("#chat_body").children().not(":first").prepend(response.body);
            }else{
                let loadmore = `
                            <div class="pagination d-flex justify-content-center mb-3">
                                <button data-page="1" class="btn btn-info load-more-pagination">{{ __("Load More") }}</button>
                            </div>`;

                $("#chat_body").html((response.allow_load_more ? loadmore : '') + response.body);
                $("#chat_header").html(response.header);

                scrollToBottom();
            }

            $("#vendor-message-footer").removeClass("d-none");
            $("#chat_header").removeClass("d-none");

            if (typeof callback === "function") {
                callback();
            }

            $('.unseen_message_count_'+freelancer_id).addClass("d-none")
            $('.reload_unseen_message_count').load(location.href + ' .reload_unseen_message_count')
        }, function (){

        })
    }

    function scrollToBottom(){
        const scrollingElement = (document.querySelector("#chat_body") || document.body);
        let scrollSmoothlyToBottom = document.querySelector("#chat_body");

        $(scrollingElement).animate({
            scrollTop: scrollSmoothlyToBottom.scrollHeight,
        }, 500);
    }

    (function (){
        /*
        ========================================
            Attach File js
        ========================================
        */

        let uploadImage = document.querySelector(".show_uploaded_file");
        let inputTag = document.querySelector(".inputTag");

        if(inputTag != null) {
            inputTag.addEventListener('change', ()=> {
                let inputTagFile = document.querySelector(".inputTag").files[0];
                uploadImage.innerText = inputTagFile.name;
            });
        };
    })();
</script>
