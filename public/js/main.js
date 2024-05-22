// Product Slider
$('.sv-slider .owl-carousel').owlCarousel({
    autoplay: false,
    autoplayHoverPause: true,
    dots: false,
    nav: true,
    thumbs: true,
    thumbImage: true,
    thumbsPrerendered: true,
    thumbContainerClass: 'owl-thumbs',
    thumbItemClass: 'owl-thumb-item',
    loop: true,
    navText: [
      "<i class='fa fa-chevron-left' aria-hidden='true'></i>",
      "<i class='fa fa-chevron-right' aria-hidden='true'></i>"
    ],
    items: 1,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 1,
      },
      992: {
        items: 1,
      }
    }
  });


$('.increment').click(function () {
	if ($(this).prev().val() < 99) {
	$(this).prev().val(+$(this).prev().val() + 1);
	}
});
$('.decrement').click(function () {
	if ($(this).next().val() > 1) {
	if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
	}
});

// User Page
$(function() {
	// Reference the tab links.
	const tabLinks = $('#tab-links li a');
	
	// Handle link clicks.
	tabLinks.click(function(event) {
		var $this = $(this);
		
		// Prevent default click behaviour.
		event.preventDefault();
		
		// Remove the active class from the active link and section.
		$('#tab-links a.active, section.active').removeClass('active');
		
		// Add the active class to the current link and corresponding section.
		$this.addClass('active');
		$($this.attr('href')).addClass('active');
	});
});

$(".toggle-password").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    input = $(this).parent().find("input");
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});



// Pagination
// (function($) {
// 	var pagify = {
// 		items: {},
// 		container: null,
// 		totalPages: 1,
// 		perPage: 4,
// 		currentPage: 0,
// 		createNavigation: function() {
// 			this.totalPages = Math.ceil(this.items.length / this.perPage);

// 			$('.pagination', this.container.parent()).remove();
// 			var pagination = $('<div class="pagination"></div>').append('<a class="nav prev disabled" data-next="false"><i class="fa fa-chevron-left"></i></a>');

// 			for (var i = 0; i < this.totalPages; i++) {
// 				var pageElClass = "page";
// 				if (!i)
// 					pageElClass = "page current";
// 				var pageEl = '<a class="' + pageElClass + '" data-page="' + (
// 				i + 1) + '">' + (
// 				i + 1) + "</a>";
// 				pagination.append(pageEl);
// 			}
// 			pagination.append('<a class="nav next" data-next="true"><i class="fa fa-chevron-right"></i></a>');

// 			this.container.after(pagination);

// 			var that = this;
// 			$("body").off("click", ".nav");
// 			this.navigator = $("body").on("click", ".nav", function() {
// 				var el = $(this);
// 				that.navigate(el.data("next"));
// 			});

// 			$("body").off("click", ".page");
// 			this.pageNavigator = $("body").on("click", ".page", function() {
// 				var el = $(this);
// 				that.goToPage(el.data("page"));
// 			});
// 		},
// 		navigate: function(next) {
// 			// default perPage to 5
// 			if (isNaN(next) || next === undefined) {
// 				next = true;
// 			}
// 			$(".pagination .nav").removeClass("disabled");
// 			if (next) {
// 				this.currentPage++;
// 				if (this.currentPage > (this.totalPages - 1))
// 					this.currentPage = (this.totalPages - 1);
// 				if (this.currentPage == (this.totalPages - 1))
// 					$(".pagination .nav.next").addClass("disabled");
// 				}
// 			else {
// 				this.currentPage--;
// 				if (this.currentPage < 0)
// 					this.currentPage = 0;
// 				if (this.currentPage == 0)
// 					$(".pagination .nav.prev").addClass("disabled");
// 				}

// 			this.showItems();
// 		},
// 		updateNavigation: function() {

// 			var pages = $(".pagination .page");
// 			pages.removeClass("current");
// 			$('.pagination .page[data-page="' + (
// 			this.currentPage + 1) + '"]').addClass("current");
// 		},
// 		goToPage: function(page) {

// 			this.currentPage = page - 1;

// 			$(".pagination .nav").removeClass("disabled");
// 			if (this.currentPage == (this.totalPages - 1))
// 				$(".pagination .nav.next").addClass("disabled");

// 			if (this.currentPage == 0)
// 				$(".pagination .nav.prev").addClass("disabled");
// 			this.showItems();
// 		},
// 		showItems: function() {
// 			this.items.hide();
// 			var base = this.perPage * this.currentPage;
// 			this.items.slice(base, base + this.perPage).show();

// 			this.updateNavigation();
// 		},
// 		init: function(container, items, perPage) {
// 			this.container = container;
// 			this.currentPage = 0;
// 			this.totalPages = 1;
// 			this.perPage = perPage;
// 			this.items = items;
// 			this.createNavigation();
// 			this.showItems();
// 		}
// 	};

// 	// stuff it all into a jQuery method!
// 	$.fn.pagify = function(perPage, itemSelector) {
// 		var el = $(this);
// 		var items = $(itemSelector, el);

// 		// default perPage to 5
// 		if (isNaN(perPage) || perPage === undefined) {
// 			perPage = 3;
// 		}

// 		// don't fire if fewer items than perPage
// 		if (items.length <= perPage) {
// 			return true;
// 		}

// 		pagify.init(el, items, perPage);
// 	};
// })(jQuery);

// $(".jm_product_outer_pg").pagify(4, ".jm_product_single");







// Cart Page Setting
/* Set rates + misc */
var taxRate = 0.10;
var shippingRate = 0; 
var fadeTime = 100;


/* Assign actions */
$('.product-quantity input').change( function() {
  updateQuantity(this);
});

$('.product-removal button').click( function() {
  removeItem(this);
});


/* Recalculate cart */
function recalculateCart()
{
  var subtotal = 0;
  
  /* Sum up row totals */
  $('.product').each(function () {
    subtotal += parseFloat($(this).children('.product-line-price').text());
  });
  
  /* Calculate totals */
  var tax = subtotal * taxRate;
  var shipping = (subtotal > 0 ? shippingRate : 0);
  var total = subtotal + tax + shipping;
  
  /* Update totals display */
  $('.totals-value').fadeOut(fadeTime, function() {
    $('#cart-subtotal').html(subtotal.toFixed(2));
    $('#cart-tax').html(tax.toFixed(2));
    $('#cart-total').html(total.toFixed(2));
	$('#total-price-tax').html(total.toFixed(2));
	
    if(total == 0){
      $('.checkout').fadeOut(fadeTime);
    }else{
      $('.checkout').fadeIn(fadeTime);
    }
    $('.totals-value').fadeIn(fadeTime);
  });
}


/* Update quantity */
function updateQuantity(quantityInput)
{
  /* Calculate line price */
  var productRow = $(quantityInput).parent().parent();
  var price = parseFloat(productRow.children('.product-price').text());
  var quantity = $(quantityInput).val();
  var linePrice = price * quantity ;
  
  /* Update line price display and recalc cart totals */
  productRow.children('.product-line-price').each(function () {
    $(this).fadeOut(fadeTime, function() {
      $(this).text(linePrice.toFixed(2));
      recalculateCart();
      $(this).fadeIn(fadeTime);
    });
  });  

}


/* Remove item from cart */
function removeItem(removeButton)
{
  /* Remove row from DOM and recalc cart total */
  var productRow = $(removeButton).parent().parent();
  productRow.slideUp(fadeTime, function() {
    productRow.remove();
    recalculateCart();
  });
}

document.querySelector('.open_menu').onclick = function (e) {
  var nav = document.querySelector('#navbar-default');
  nav.classList.toggle('show_mobile_menu');
  e.preventDefault();
}