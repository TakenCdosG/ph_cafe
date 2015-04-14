<?php

/* Menu Walkers */


/* Walker Menu Footer */
/*class WPEX_Walker_Nav_Menu_footer extends Walker_Nav_Menu {

    function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
    if($element->menu_order !=1 && $element->menu_item_parent == 0){
    //$children_elements = NULL;
    Walker_Nav_Menu::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

    function walk( $elements, $max_depth){

        foreach($elements as $key => $element){
            if($element->menu_order == 1 || $element->menu_item_parent != 0){
                unset($elements[$key]);
            }
        }

        return Walker_Nav_Menu::walk( $elements, $max_depth);
    }

}
*/

Class Custom_Walker_Sub_Main_Menu extends Walker_Nav_Menu{


    function walk( $elements, $max_depth){

        //d($elements);


        foreach($elements as $key => $element){

            if($element->current_item_ancestor == TRUE || ($element->current == TRUE && $element->menu_item_parent == 0) ){
                $menu_item_parent = $element->ID;
            }
        }
        //d($menu_item_parent);

        foreach($elements as $key => $element){


            if($element->menu_item_parent != $menu_item_parent){
                unset($elements[$key]);
            }
            $element->classes[] = strtolower($element->title);
        }
       // d($elements);
        return Walker_Nav_Menu::walk( $elements, $max_depth);
    }

}


?>