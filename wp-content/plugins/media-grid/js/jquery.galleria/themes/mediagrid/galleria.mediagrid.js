/**
 * Galleria LCweb Theme 2013-04-17 - for mediagrid
 * (c) LCweb - Montanari Luca
 */

(function($) {
	
/*global jQuery, Galleria */

Galleria.addTheme({
    name: 'mediagrid',
    author: 'Montanari Luca',
    defaults: { 
		initialTransition: 'flash',
        thumbCrop:  true,
		queue:		false,
		showCounter:false,
		pauseOnInteraction: true,
		
        // set this to false if you want to show the caption all the time:
        _toggleInfo: false
    },
    init: function(options) {

        Galleria.requires(1.28, 'LCweb theme requires Galleria 1.2.8 or later');

        // add some elements
        this.addElement('mg-play','mg-toggle-thumb');
        this.append({
            'info' : ['mg-play','mg-toggle-thumb','info-text']
        });

        // cache some stuff
        var slider_obj = this,
			info = this.$('info-text'),
			play_btn = this.$('mg-play'),
            touch = Galleria.TOUCH,
            click = touch ? 'touchstart' : 'click';

        // some stuff for non-touch browsers
        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
        }

        // bind some stuff
        this.bind('thumbnail', function(e) {

            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
                    $(this).not('.active').children().stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children().stop().fadeTo(400, 0.6);
                });

                if ( e.index === this.getIndex() ) {
                    $(e.thumbTarget).css('opacity',1);
                }
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6);
            }
        });

        this.bind('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 1);
            }

			// remove the past tweaked description
			this.$('info').parent().find('.galleria-stage .galleria-info-text').remove();
			
			if(this.hasInfo()) {
				this.$('info').removeClass('has_no_data');
			} else {
				this.$('info').addClass('has_no_data');
			}	

            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
        });

        this.bind('loadfinish', function(e) {
			this.$('loader').fadeOut(200);
			
			// security check for the play-pause button
			if(!this._playing && play_btn.hasClass('galleria-mg-pause')) {
				play_btn.removeClass('galleria-mg-pause');
			}
			
			info.hide(); // hide standard infobox

			// description bottom position trick
			if(this.hasInfo()) {
				var clone = this.$('info').find('.galleria-info-text').clone();
				this.$('info').parents('.galleria-container').find('.galleria-stage').append(clone);
				this.$('info').parents('.galleria-container').find('.galleria-stage .galleria-info-text').fadeTo(1, mg_galleria_fx_time);
			}	
        });
		
		
    }
});

}(jQuery));