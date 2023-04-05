function updateProgressBar(category_name, product_name, percent_category, percent_product) {
	var c_name = document.getElementsByClassName('category_inner_name')[0];
	var p_name = document.getElementsByClassName('product_inner_name')[0];
	var percent_c = document.getElementsByClassName('category_inner')[0];
	var percent_p = document.getElementsByClassName('product_inner')[0];

	c_name.innerHTML = category_name;
	p_name.innerHTML = product_name;
	percent_c.innerHTML = percent_category + '%';
	percent_p.innerHTML = percent_product + '%';

	percent_c.style.width = percent_category + '%';
	percent_p.style.width = percent_product + '%';
}

function start_scroll_down() {
	window.scrollBy(0, 1000);
	// scroll = setInterval(function(){ window.scrollBy(0, 1000);}, 1500);
}
