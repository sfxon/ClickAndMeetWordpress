jQuery(document).ready(function( $ ) {
		jQuery.fn.modal = function(param) {				
				if(typeof param != 'undefined') {
						if(param == 'hide') {
								$(this).hide();
								return;
						}
				}
				
				var self = this;
				$(this).show();
				
				//init close button
				$(this).find('.modal-header button.close').off('click');
				$(this).find('.modal-header button.close').on('click', function() {
						$(self).hide();
				});
		};
});