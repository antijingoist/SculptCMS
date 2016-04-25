$(document).ready(function() {
  $('#lightboxPhoto').click(function() {
      $('#lightbox').css("display", "none");
  });
});

function showLightbox(imgUrl) {
    var image;

    if (imgUrl) {
        image = '<img class="fullPhoto" src="' + imgUrl + '" alt="" />';
        $("#lightboxPhoto").html(image);
        $("#lightbox").css("display", "inline");
    }
}