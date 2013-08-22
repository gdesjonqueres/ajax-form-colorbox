if (typeof Dtno === 'undefined') {
	window.Dtno = {};
}

(function($) {
	var config = {
		urlAjax: '',
	};
	Dtno.config = config;
	
	var utils = {
		isset: function(v) {
			return typeof v !== 'undefined' && v !== null;
		},
			
		handleError: function(xhr) {
			var text = 'statut : ' + xhr.status;
			if (xhr.status == 500) {
				text = xhr.responseText;
			}
			else {
				text += '\nréponse : ' + xhr.responseText;
			}
			
			if (window.console && window.console.log && window.console.error && window.console.info) {
				console.error(text);
			}
		},

		doPost: function(action, data, success, complete, error) {
			var jqXhr,
				url;
			
			if (typeof data === 'undefined' || data == null) {
				data = {};
			}
			$.extend(data, {action: action});

			jqXhr = $.ajax({
				type: "POST",
				url: Dtno.config.urlAjax,
				data: data,
				dataType: "json",
				success: function(data) {
					if (typeof success !== 'undefined' && success != null) {
						success.call(this, data.r);
					}
				},
				error: function(xhr) {
					if (typeof error !== 'undefined' && error != null) {
						if (!error.call(this)) {
							utils.handleError(xhr);
						}
					}
					else {
						utils.handleError(xhr);
					}
				},
				complete: function() {
					if (typeof complete !== 'undefined' && complete != null) {
						complete.call(this);
					}
				}
			});
			
			return jqXhr;
		}
	};

	Dtno.utils = utils;
})(jQuery);