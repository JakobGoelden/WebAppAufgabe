<?php

?>

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
  
  <script>
   function initDialog(target, trigger) {
    $(target).dialog({
      autoOpen: false,
      show: {
        effect: "fold",
        duration: 200
      }
    });

    $(trigger).on("click", function() {
      $(target).dialog("open");
    });
  }
  
  function initInactive(target, trigger) {
    $(target).dialog({
      autoOpen: false,
      show: {
        effect: "fold",
        duration: 200
      },
      modal: true,
      buttons: {
        "Logout": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });

    $(trigger).on("click", function() {
      $(target).dialog("open");
    });
  }
  </script>