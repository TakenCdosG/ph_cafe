jQuery( function($) {

    $(document).ready(function() {

        FooterButtonsHover();

        SubSubMenuDropdownVersion();


    });

    function FooterButtonsHover(){

        if(jQuery("#site-navigation-wrap").length){


            var ClassToAdd = jQuery("#sidr-id-menu-main-menu  > li.sidr-class-current-menu-ancestor > a").text();

            var ClassToAdd = ClassToAdd.toLowerCase();

            $("#footer-buttons a."+ClassToAdd+" img").css({
                "-webkit-filter": "grayscale(0)",
                "filter": "grayscale(0)"
            });
        }


    }

    // Create default option "Go to..."

    function SubSubMenuDropdownVersion(){
        $("<option />", {
            "selected": "selected",
            "value"   : "",
            "text"    : "Go to..."
        }).appendTo(".sub-sub-main-menu select");

    // Populate dropdown with menu items
        $(".sub-sub-main-menu a").each(function() {
            var el = $(this);
            $("<option />", {
                "value"   : el.attr("href"),
                "text"    : el.text()
            }).appendTo(".sub-sub-main-menu select");
        });

        $(".sub-sub-main-menu select").change(function() {
            window.location = $(this).find("option:selected").val();
        });

    }



});
