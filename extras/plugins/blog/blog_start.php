<?
   $blog_page = _INPUT("bp", "front");
   $blog_list_offset = _INPUT("o", 0);

/*
   if($blog_page === "front") {
      blog_front_page();
   } else {
      blog_post($blog_page);
   }
*/

   switch($blog_page) {
     case "front":
       blog_front_page();
       break;
     case "archive":
       $archive_year = _INPUT("ayr", "");
       $archive_month = _INPUT("amo", "");

       if ($archive_month === "" || $archive_year === "") {
         blog_archives_list(); //switch to archive front
       } else {
         blog_archive($archive_year, $archive_month);
       }
       break;
     default:
       blog_post($blog_page);
       break;
   }
?>