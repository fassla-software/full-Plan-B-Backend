function send_ajax_request(request_type,request_data,url,before_send,success_response,errors){
    $.ajax({
        url: url,
        type: request_type,
        headers: {
            'X-CSRF-TOKEN': "4Gq0plxXAnBxCa2N0SZCEux0cREU7h4NHObiPH10",
        },
        beforeSend: (typeof before_send !== "undefined" && typeof before_send === "function") ? before_send : () => { return ""; } ,
        processData: false,
        contentType: false,
        data: request_data,
        success:  (typeof success_response !== "undefined" && typeof success_response === "function") ? success_response : () => { return ""; },
        error:  (typeof errors !== "undefined" && typeof errors === "function") ? errors : () => { return ""; }
    });
}

function prepare_errors(data,form,msgContainer,btn){
    let errors = data.responseJSON;

    if(errors.success != undefined){
        toastr.error(errors.msg.errorInfo[2]);
        toastr.error(errors.custom_msg);
    }

    $.each(errors.errors,function (index,value){
        console.log(value)
        toastr.error(value[0]);
    })
}
function ajax_toastr_error_message(xhr){
    $.each(xhr.responseJSON.errors, function (key, value) {
        toastr.error((key.capitalize()).replace("-", " ").replace("_", " "), value);
    });
}

function ajax_toastr_success_message(data){
    if(data.success){
        toastr.success(data.msg)
    }else{
        toastr.warning(data.msg);
    }
}

function convertToSlug(text) {
    return text
        .toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
}

function calculatePercentage(price, percentage){
    return (price * percentage) / 100;
}

//: Change the URL without reloading the page
function changeUrlWithoutReload(newUrl, title, data) {
    if (typeof history.pushState === 'function') {
        if(!newUrl.includes("?"))
            newUrl = "?" + newUrl;

        history.pushState(data, title, window.location.pathname + newUrl);
    }
}

function currentPageUrl(){
    return window.location.host + window.location.pathname;
}

function parseFormDataAsString(element){
    const formData = new FormData(element);
    let formDataString = "";

    for (const [key, value] of formData.entries()) {
        formDataString += `${key}=${value}&`;
    }

    return formDataString.slice(0, -1); // remove the trailing "&"
}

//: Example usage
//changeUrlWithoutReload('/new-page', 'New Page', { someData: 'example' });

// Listen for the popstate event (triggered when the user navigates using browser back/forward buttons)
window.addEventListener('popstate', function(event) {
    // You can access the event state data using event.state
    var state = event.state;

    // Perform actions based on the state data (e.g., update content)
    if (state && state.someData === 'example') {
        // Update content based on the state data
        // For example, load new content using AJAX
    }
});