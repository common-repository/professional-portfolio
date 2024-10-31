var filter;
var list=jQuery('.ittanta-portfolio .ittantalist');
(function($){
    'use strict';
    jQuery(document).ready(function($){
		$.scrollUp();
		setPolio();
        var filter = '*';
        isotope_run(filter);
        $(document).on("click",".filtertags",function(){
			list.trigger('polio','collapse');
			$(".filtertags").removeClass('filter-active');
			var target = $(this).data('target');
			$(this).addClass('filter-active');
			if(target!='all'){
				$(".targetDiv").hide();
				$(target).show();
			}else{
				$(".targetDiv").hide();
				$(".subfilter").removeClass("filter-active");
				updateFilter();
			}
		});
		$(document).on("click",".subfilter",function(){
			list.trigger('polio','collapse');
			var parent = $(this).data('parent');
			if($(this).hasClass('filter-active')){
				$(this).removeClass("filter-active");
				$(parent+" .subfilter").removeClass("filter-active");
			}else{
				$(parent+" .subfilter").removeClass("filter-active");
				$(this).addClass("filter-active");	
			}
			updateFilter();
		}); 
    });

    jQuery(window).on("load",function(){
		//updateFilter();
    });
    if(portfolio_options.portfolio_pagination=='infinitescroll'){
    	list.infiniteScroll({
		  path: '.pagination .next',
		  append: '.ittantalist .ittantalist-item',
		  status: '.scroller-status',
		  hideNav: '.ittanta-portfolio-pagination .pagination',
		  history: false 
		}).on( 'append.infiniteScroll', function( event, response ) {
			list.trigger('polio','collapse');
			var oldlist=$("#professional-portfolio-list .polio-container").html();
			jQuery("#professional-portfolio-list").remove();
			list.isotope("destroy");
			isotope_run();
			setTimeout(function(){
				setPolio();
				updateFilter()
				$("#professional-portfolio-list .polio-container").prepend(oldlist);
			},100);
			
		});	
    }
    
})(jQuery);
setPolio=function(){
	
	list.polio({
        id: 'professional-portfolio-list',
        theme: 'black',
        placement: 'inside',
		hiddenItems: '.isotope-hidden',
		closeText: '<svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-close closeimg" viewBox="0 0 40 40"><path d="M23.868 20.015L39.117 4.78c1.11-1.108 1.11-2.77 0-3.877-1.109-1.108-2.773-1.108-3.882 0L19.986 16.137 4.737.904C3.628-.204 1.965-.204.856.904c-1.11 1.108-1.11 2.77 0 3.877l15.249 15.234L.855 35.248c-1.108 1.108-1.108 2.77 0 3.877.555.554 1.248.831 1.942.831s1.386-.277 1.94-.83l15.25-15.234 15.248 15.233c.555.554 1.248.831 1.941.831s1.387-.277 1.941-.83c1.11-1.109 1.11-2.77 0-3.878L23.868 20.015z" class="layer"/></svg>',
        onContent: function(content){
            // init "fancybox" plugin
            jQuery('.flexslider', content).flexslider({slideshow: false, animationSpeed: 300});
        }
    });
}
isotope_run = function() {
	list.isotope({layoutMode : 'fitRows'});
    list.children().css({top: 0, left: 0});
    window.setTimeout(function(){
        list.trigger('polio','excludeHidden');
    }, 25);
};
updateFilter = function(){
	var classes=[];
	if(jQuery(".subfilter.filter-active").length>0){
		jQuery(".subfilter.filter-active").each(function(){
			classes.push(jQuery(this).data('filter'));
		});

		filter=classes.join('');
	
	}else{
		filter="*";	
	}
	
	list.isotope({filter:filter});
};



if(portfolio_options.portfolio_pagination=='loadmore'){
	(function($){

		$(document).ready(function() {
			$('.elm-button').on('click', function (e) {
				e.preventDefault();

				var $that = $(this),
					url = $that.attr('data-href'),
					nextPage = parseInt($that.attr('data-page'), 10) + 1,
					maxPages = parseInt($that.attr('data-max-pages'), 10);

				$that.addClass('is-loading');

				if (url.indexOf('?') > 0) {
					url += '&';
				} else {
					url += '?';
				}

				url += 'paged=' + nextPage;

				$.ajax({
					type : 'POST',
					url : url,
					dataType: 'text'
				}).done(function (data) {
					$that.removeClass('is-loading');
					list.trigger('polio','collapse');
					$('.ittanta-portfolio .ittantalist').append($($.parseHTML(data)).find('.ittanta-portfolio .ittantalist').addBack('.ittanta-portfolio .ittantalist').html());
					$('.ittanta-portfolio .ittanta-portfolio-main-container').append($($.parseHTML(data)).find('.ittanta-portfolio .polio-content').addBack('.ittanta-portfolio .polio-content').html());
					var oldlist=$("#professional-portfolio-list .polio-container").html();
					jQuery("#professional-portfolio-list").remove();
					list.isotope("destroy");
					isotope_run();
					setTimeout(function(){
						setPolio();
						updateFilter()
						$("#professional-portfolio-list .polio-container").prepend(oldlist);
					},100);

					if ( nextPage == maxPages ) {
						$that.remove();
					} else {
						$that.attr('data-page', nextPage);
					}

				}).fail(function () {
					console.log('Ajax failed. Navigating to ' + url + '.');
					document.location.href = url;
				});

				return false;
			});
		});

	})(jQuery);
}