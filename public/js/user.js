const image = document.getElementById("image");
const imageInput = document.getElementById("profile_picture")
imageInput.addEventListener("change", function () {
    getImgData();
});
image.addEventListener("click", toggleProfilePictureInput);

function toggleProfilePictureInput() {
    imageInput.click();

}

function getImgData() {
    const files = imageInput.files[0];
    if (files) {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(files);
        fileReader.addEventListener("load", function () {
            image.src = this.result;
        });
    }
}

