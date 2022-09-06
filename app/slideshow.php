<?php
  require("functions.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>WCSS Graduation Slideshow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reset.min.css" integrity="sha512-Mjxkx+r7O/OLQeKeIBCQ2yspG1P5muhAtv/J+p2/aPnSenciZWm5Wlnt+NOUNA4SHbnBIE/R2ic0ZBiCXdQNUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.min.css" integrity="sha512-ITH3NSfntO7uI5n+BnxGNXpzDUoOsmAXuG37UDONLxNYIdc0EBBOOQ1xyc+t9ag9ETSuBXFApx+Rod0uURfDYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/theme/sky.min.css" integrity="sha512-Y5vz+5wI9ALJMccqguHMlI25sV/YjGIBUYr7yOkYm9/TNQoOlWXWtmzfmQoNVVqYLNkt9NHKl+0ODDQ5+VxVIA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/plugin/highlight/monokai.min.css" integrity="sha512-z8wQkuDRFwCBfoj7KOiu1MECaRVoXx6rZQWL21x0BsVVH7JkqCp1Otf39qve6CrCycOOL5o9vgfII5Smds23rg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
  </head>
  <body>
    <div class="reveal">
      <div class="slides">
        <?php
          $students = getStudentsbyAsc(getCurrentYear());
          $preSlideOrder = getSlideOrder(0);
          $postSlideOrder = getSlideOrder(1);

          foreach ($preSlideOrder as $preSlide) {
            echo "<section data-background-iframe='slide.php?id=".$preSlide."' data-background-interactive></section>\n";
          }
          foreach ($students as $student){
            if ($student["stud_enabled"] == 1) {
              echo "<section data-background-iframe='studentSlide.php?stud_id=".$student["stud_id"]."' data-background-interactive></section>\n";
            }
          }
          foreach ($postSlideOrder as $postSlide) {
            echo "<section data-background-iframe='slide.php?id=".$postSlide."' data-background-interactive></section>\n";
          }
         ?>
         <section>
           <p>Options:</p>
           <a href="slideshow.php" class="btn btn-info" role="button">Start of Slideshow</a>
           <a href="startSlideshow.php" class="btn btn-info" role="button">Admin Controls</a>
         </section>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/reveal.js" integrity="sha512-fEgazVdZLNymIm9n+b2jtCrM4DQiqNTfLNUxbsGSFUJpYemf9A8GxgJ3VAfU/Lc6yZkDdEOdekBDZtG/mf73fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/plugin/notes/notes.min.js" integrity="sha512-nLsCkPS/rdxRVOkxiKnld9KhQ24V/5kRChzSZ3MoFODykbWyBlQRsHLrbpN3J4LabYBnXIp1EnRIo3ifvyiQIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/plugin/markdown/markdown.min.js" integrity="sha512-QAU7Ux8iEB53koLKFCtddfFp0D1+uQWoX3ROnRDLeiE98XHOkMciaup0Spc014jQAuJaqz8VjUZdBqriheI4Lg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.3.1/plugin/highlight/highlight.min.js" integrity="sha512-U3fPDUX5bMrn1wnYqjaK44MFA9E6MKS+zPAg9WPAGF5XhReBeDj3FGaA831CjueG+YJxYA3WaO/m33kMIoOs/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
      //Only make the controls for the slideshow visible if the slideshow is being embedded as an iframe
      var controlVal = window.self !== window.top;
      Reveal.initialize({
        controls: controlVal,
        plugins: [RevealMarkdown, RevealHighlight, RevealNotes]
      });
    </script>
  </body>
</html>
