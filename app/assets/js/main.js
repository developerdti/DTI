document.addEventListener("DOMContentLoaded", () => {
  showImage();
});

function showImage(){
  $(document).on(
    "click",
    'button[class~="Button__imageModal"]',
    function (e) {
      // console.log(this.innerHTML)
      const imageModal = document.getElementById('imageContainerModal');
      imageModal.innerHTML = this.innerHTML;
    }
  );
}
