jQuery( function($) {

    $(document).ready(function() {


        var ClassToAdd = jQuery("#sidr-id-menu-main-menu  > li.sidr-class-current-menu-ancestor > a").text();

        var ClassToAdd = ClassToAdd.toLowerCase();

        jQuery("#footer-buttons a."+ClassToAdd+" img").css({
            "-webkit-filter": "grayscale(0)",
            "filter": "grayscale(0)"
        });


       // alert(ClassToAdd);

    });

    

});
