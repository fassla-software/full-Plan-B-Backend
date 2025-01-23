<script>
    (function($){
        "use strict";
        $(document).ready(function(){


            $(document).on('click','.accept_and_change_order_status',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Accept Order ?")}}',
                    text: '{{__("You would not change it again.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Accept it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            //time tracker
            let elTime = document.querySelector("#time"),
                elStart = document.querySelector("#start"),
                elPause = document.querySelector("#pause"),
                elWorkHour = document.querySelector("#work_hour");

            // VALUES
            let timeArr = [0, 0, 0],
                timeStr = '00:00:00',
                timer;

            // STATE
            let STATE = 'new'; // 'new', 'play', 'pause', 'stop';
            let start_count = 0;
            if (elStart != null) {
                elStart.addEventListener('click', function () {
                    if(start_count == 0){
                        const startTime = new Date().toISOString();
                        $('#start_time').val(startTime);
                        setCookie('start_time', startTime, 7);
                        start_count++;
                    }

                    if (STATE === 'play') {
                        return false;
                    }
                    STATE = 'play';
                    timer = setInterval(function () {
                        timeArr = getTime();
                        incrSeconds();
                        setTime();
                    }, 1000);
                    glowButton();
                });
            }
            if (elPause != null) {
                elPause.addEventListener('click', function () {
                    STATE = 'pause';
                    clearInterval(timer);
                    glowButton();
                });
            }

            function getTime() {
                let str = elTime.textContent,
                    frmtRgx = /(?:\d{2}:)?\d{2}:\d{2}:\d{2}/;

                str = frmtRgx.test(str) ? str : timeStr;

                let arr = str.split(':'),
                    len = arr.length;
                for (let i = 0; i < len; i++) {
                    arr[i] = parseInt(arr[i]);
                }
                return arr;
            }

            function setTime() {
                let tmpArr = pad(timeArr.slice(0));
                elTime.textContent = tmpArr.join(':');
                elWorkHour.value  = tmpArr.join(':');
                setCookie('work_hour', tmpArr.join(':'), 7);

                function pad(tmpArr) {
                    for (let i = 0; i < tmpArr.length; i++) {
                        tmpArr[i] = String(tmpArr[i]);
                        if (tmpArr[i].length < 2) {
                            tmpArr[i] = '0' + tmpArr[i];
                        }
                    }
                    return tmpArr;
                }
            }

            function incrSeconds() {
                let secIndex = timeArr.length - 1;
                timeArr[secIndex]++;
                if (timeArr[secIndex] >= 60) {
                    timeArr[secIndex] = 0;
                    incrMinutes();
                }
            }

            function incrMinutes() {
                let minIndex = timeArr.length - 2;
                timeArr[minIndex]++;
                if (timeArr[minIndex] >= 60) {
                    timeArr[minIndex] = 0;
                    incrHours();
                }
            }

            function incrHours() {
                let hrIndex = timeArr.length - 3;
                timeArr[hrIndex]++;
                if (timeArr[hrIndex] >= 24) {
                    timeArr[hrIndex] = 0;
                    incrDay();
                }
            }

            function incrDay() {
                if (timeArr.length < 4) {
                    timeArr.unshift(0);
                }
                timeArr[0]++;
            }

            function glowButton() {
                elStart.classList.remove('active');
                elPause.classList.remove('active');
                if (STATE === 'play') {
                    elStart.classList.add('active');
                } else {
                    if (STATE === 'pause') {
                        elPause.classList.add('active');
                    }
                }
            }

            //set cookie
            function setCookie(name, value, days) {
                let expires = "";
                if (days) {
                    let date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; " +"expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
            }

            //get cookie
            function getCookie(name) {
                let nameEQ = name + "=";
                let ca = document.cookie.split(';');
                for(let i=0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            //note modal
            $(document).on('click','.add_notes',function(){
                let notes = $('#work_notes').val();
                $('#notes').val(notes);
            });
            //manual time modal
            $(document).on('click','.add_manual_time',function(){
                $('#manual_time').val(getCookie('work_hour'));
                $('#work_hour').val(getCookie('work_hour'));
                $('#start_time').val(getCookie('start_time'));
            });

            //display restore time
            $(document).on('click','.display_restore_time',function (){
                $('#time').text($('#manual_time').val());
            })

        });
    }(jQuery));

    // todo toastr warning
    function toastr_warning_js(msg){
        Command: toastr["warning"](msg, "Warning !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
    //toastr success
    function toastr_success_js(msg){
        Command: toastr["success"](msg, "Success !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

</script>
