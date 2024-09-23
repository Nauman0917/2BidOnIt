<style>
    .editor-modal {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow: hidden;
    }

    .editor-modal-content {
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        margin: auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        background: #fff;
        overflow-y: auto;
        overflow-x: hidden;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: max-height 0.3s ease;
    }

    .editor-modal-content {
        position: relative;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        margin: auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        background: #fff;
        overflow-y: auto;
        overflow-x: hidden;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: max-height 0.3s ease;
    }

    .image_container {
        width: 100%;
        max-width: 100%;
        max-height: 60vh;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .cropped-preview img {
        max-width: 100%;
        height: auto;
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 40px;
        height: 40px;
        animation: spin 2s linear infinite;
        margin: auto;
        display: block;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .editor-modal-content button {
        margin-bottom: 20px;
        padding: 10px 15px;
    }

    .editor-modal-content h2 {
        margin: 0;
        margin-bottom: 20px;
        text-align: center;
        width: 100%;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 24px;
        cursor: pointer;
    }
</style>

<div id="imageEditorModal" class="modal editor-modal" style="display:none;">
    <div class="modal-content editor-modal-content">
        <span class="close" id="closeEditorModel">&times;</span>
        <h2>Edit Image</h2>

        <div style="width: 100%; display: flex; justify-content: space-between;">
            <button id="crop_button" class="btn btn-success">Crop & Preview</button>
            <button id="save_cropped_image" type="button" class="btn btn-primary">Save Image</button>
        </div>

        <div class="image_container">
            <img id="blah" src="#" alt="main image" />
        </div>

        <div id="cropped_result" class="image_container" style="width: 60% !important; height: auto;"></div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
    let cropper;
    let imageModel;
    let parent_id;
    let imageId;

    $('img').on('dblclick', function() {
        let imagePath = $(this).data('image-path');
        imageModel = $(this).data('image-model');
        parent_id = $(this).data('parent-id');
        imageId = $(this).data('image-id');
        let imageUrl = $(this).attr('src');

        if (!imagePath) {
            return;
        }
        initCropper(imageUrl);
        $('#imageEditorModal').show();
    });

    function initCropper(base64Image) {
        const imageElement = document.getElementById('blah');

        imageElement.src = base64Image;

        imageElement.onload = function() {
            cropper = new Cropper(imageElement, {
                aspectRatio: 16 / 9,
                crop(event) {
                    console.log(event.detail.x);
                    console.log(event.detail.y);
                }
            });

            document.getElementById('crop_button').addEventListener('click', function() {
                const imgUrl = cropper.getCroppedCanvas().toDataURL();
                const img = document.createElement('img');
                img.src = imgUrl;
                document.getElementById('cropped_result').innerHTML = '';
                document.getElementById('cropped_result').appendChild(img);
                adjustModalHeight();
            });

            document.getElementById('save_cropped_image').addEventListener('click', function(event) {
                event.preventDefault();
                const croppedImageUrl = cropper.getCroppedCanvas().toDataURL();
                sendCroppedImageToServer(croppedImageUrl);
            });
        };
    }

    function sendCroppedImageToServer(croppedDataUrl) {
        $.ajax({
            url: '{{ route('save_edited_image') }}',
            type: 'POST',
            data: {
                image: croppedDataUrl,
                imageModel: imageModel,
                parent_id: parent_id,
                imageId: imageId,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                toastr.success(success, toastr_options);
            },
            error: function(error) {
                toastr.error(error, toastr_options);
            }
        });

        $('#imageEditorModal').show();
        location.reload();
    }

    function adjustModalHeight() {
        const modalContent = document.querySelector('.editor-modal-content');
        modalContent.style.maxHeight = '90vh';
        modalContent.style.overflowY = 'auto';
    }

    $('#closeEditorModel').on('click', function() {
        $('#imageEditorModal').hide();
        cropper.destroy();
    });
</script>
