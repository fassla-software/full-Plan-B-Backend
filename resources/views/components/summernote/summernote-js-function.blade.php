<script src="{{asset('assets/backend/js/summernote/summernote-lite.min.js')}}"></script>

<script>
    function initializeSummernote(element, options = {}) {
        let summernoteConfig = {
            disableDragAndDrop: true,
            codeviewFilter: true,
            codeviewIframeFilter: true,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['Insert', ['link', 'table', 'video', 'picture']],
            ],
            styleTags: [
                'p',
                {
                    title: 'Blockquote',
                    tag: 'blockquote',
                    className: 'blockquote',
                    value: 'blockquote'
                },
                'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
            codemirror: { // codemirror options
                theme: 'monokai'
            },
            callbacks: {
                onPaste: function(e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window
                        .clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        };

        summernoteConfig.callbacks = {...summernoteConfig.callbacks, ...options};

        let summerNote = element;

        if(summerNote.length > 1){
            summerNote.each(function (){
                const singleSummernote = $(this)
                // Get the HTML content from the textarea
                let rawData = singleSummernote.val();
                // Sanitize the HTML content
                let sanitizedData = sanitizeHTML(rawData);

                singleSummernote.html('').summernote(summernoteConfig);

                // Set the sanitized content as the initial value
                singleSummernote.summernote('code', sanitizedData);
            })
        }else{
            // Get the HTML content from the textarea
            let rawData = summerNote.val();
            // Sanitize the HTML content
            let sanitizedData = sanitizeHTML(rawData);

            summerNote.val(sanitizedData);
            summerNote.summernote(summernoteConfig);
        }
    }

    // Function to sanitize HTML content
    function sanitizeHTML(content) {
        // Use jQuery to create a temporary element and set its HTML
        let tempElement = $('<div>').html(content);

        // Remove any script tags
        tempElement.find('script').remove();

        // Return the sanitized HTML
        return tempElement.html();
    }
</script>