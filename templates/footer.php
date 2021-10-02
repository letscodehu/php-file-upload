<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        const $form = $('#uploadform');
        const $box = $('.box');

        const $input = $form.find('input[type="file"]');
        const $label = $('.box__filelabel');

        const showFiles = function(files) {
            $label.text(files[0].name);
        }

        let droppedFiles = false;

        $('#uploadable').on('change', function(e) {
            droppedFiles = e.currentTarget.files;
            showFiles(droppedFiles);
        })

        $('.box__filelabel').on('click', function() {
            $('#uploadable').click();
        })

        $box.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
        })
        .on('dragover dragenter', function() {
            $box.addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            $box.removeClass('is-dragover');
        })
        .on('drop', function(e) {
            droppedFiles = e.originalEvent.dataTransfer.files;
            showFiles(droppedFiles);
        });

        $form.on('submit', function(e) {
            if ($form.hasClass('is-uploading')) return false;

            $form.addClass('is-uploading').removeClass('is-error');

            e.preventDefault();

            var ajaxData = new FormData($form.get(0));

            if (droppedFiles) {
                $.each(droppedFiles, function(i, file) {
                    ajaxData.append($input.attr('name'), file);
                })
            }

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: ajaxData,
                processData : false,
                contentType : false,
                dataType: 'json',
                complete: function() {
                    $form.removeClass('is-uploading')
                },
                success: function(data) {
                    $box.addClass('is-success');
                    window.location.href = data.message
                },
                xhr : function() {
                    let xhr = new XMLHttpRequest;
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            $label.text(Math.ceil(e.loaded / e.total * 100) + " %");
                        }
                    });
                    return xhr;
                },
                error: function(data) {
                    droppedFiles = false;
                    $box.addClass('is-error');
                    if (data.responseJSON) {
                        $label.text(data.responseJSON.error);
                    } else {
                        $label.text("Upload error");
                    }
                }
            })

        })


    });
</script>