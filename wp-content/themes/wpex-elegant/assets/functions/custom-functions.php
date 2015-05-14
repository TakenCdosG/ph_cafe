<?php
add_action( 'tribe_events_single_event_after_the_content_custom','single_event_links_custom');

function single_event_links_custom(){


    // don't show on password protected posts
    if ( is_single() && post_password_required() ) {
        return;
    }

    echo '<div class="tribe-events-cal-links">';
    echo '<a class="tribe-events-gcal tribe-events-button" target="_blank" href="' . esc_url( tribe_get_gcal_link() ) . '" title="' . __( 'Add to Google Calendar', 'tribe-events-calendar' ) . '">+ ' . __( 'Google Calendar', 'tribe-events-calendar' ) . '</a>';
    echo '<a class="tribe-events-ical tribe-events-button" target="_blank" href="' . esc_url( tribe_get_single_ical_link() ) . '" title="' . __( 'Download .ics file', 'tribe-events-calendar' ) . '" >+ ' . __( 'iCal Export', 'tribe-events-calendar' ) . '</a>';
    echo '</div><!-- .tribe-events-cal-links -->';
}