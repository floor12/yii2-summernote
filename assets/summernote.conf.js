/**
 * Created by floor12 on 12.12.2016.
 */



var summernoteParams = {
    placeholder: 'Введите текст здесь...',
    lang: 'ru-RU',
    codemirror: {
        theme: 'monokai',
    },
    height: 200,
    toolbar: [
        ['style', ['style']],
        ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['link', ['link', 'picture']],
        ['insert', ['table']],
        ['misc', ['codeview', 'fullscreen']]
    ],
    onCreateLink: function (originalLink) {
        return originalLink; // return original link
    },

    callbacks: {
        onImageUpload: function (files) {
            sendFile(files[0]);
        },

        onCreateLink: function (originalLink) {
            return originalLink; // return original link
        },
    }

}

function sendFile(file, editor, welEditable) {
    data = new FormData();
    data.append("file", file);
    $.ajax({
        data: data,
        type: "POST",
        url: "/pages/page/imageupload",
        cache: false,
        contentType: false,
        processData: false,
        success: function (url) {
            document.execCommand('insertImage', false, url);

        }
    });
}
