<?  $album_cat = _INPUT("sac", ""); ?>
<div id="lightbox">
  <div id="lightboxPhoto">
  </div>
  <div id="lightboxCaption">
    (Click image to close)
  </div>
</div>
<div class="sc_album_cat">
<? $current_album = album_categories_list($album_cat); ?>
</div>
<div class="sc_album_content">
<? album_content($current_album); ?>
</div>