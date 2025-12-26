$(function() {
	var searchBox = $('#search input[name="search"]'), dropDown
	searchBox.autocomplete({
		'source': function(request, response) {
			var _self = this;
			if(!request) {
				_self.hide();
				return
			}
			$.ajax({
				url: 'module/search_suggestion/ajax?keyword=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(results) {
					var html = '';
					results.forEach(function(result) {
						if (result['type'] == 'remainder_cnt') {
							html += '<li class="search-item remainder-count"><a href="' + result['href'] + '">' + result['label'] + '</a></li>';
						} else {
							html += '<li class="search-item"><a href="' + result['href'] + '">' +
									'<div class="image"><img src="' + result['thumb'] + '"></div>' +
									'<div class="name">' + result['label'] + '</div>' +
									'<div class="price">' + result['price'] + '</div>' +
							'</a></li>'
						}
					});
					if (html) {
						_self.show();

					} else {
						_self.hide();
					}
					$(_self).siblings('ul.dropdown-menu').html(html);
					dropDown.scrollTop(0)
				}
			});
		},
		'select': function(item) {}
	});
	dropDown = searchBox.siblings('ul.dropdown-menu');
});