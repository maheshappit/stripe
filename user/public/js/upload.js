document.addEventListener("DOMContentLoaded", function () {
    const uploadForm = document.getElementById("upload-form");
    const progressContainer = document.getElementById("progress-container");
    const progressBar = document.getElementById("progress-bar");
    const progressText = document.getElementById("progress-text");
    const uploadResult = document.getElementById("upload-result");

    uploadForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(uploadForm);

        axios.post('/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: function (progressEvent) {
                const percentCompleted = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                progressBar.style.width = percentCompleted + "%";
                progressText.textContent = percentCompleted + "%";
            },
        }).then(function (response) {
            uploadResult.textContent = response.data.message;
        }).catch(function (error) {
            uploadResult.textContent = "Upload failed.";
        });

        progressContainer.style.display = "block";
    });
});
