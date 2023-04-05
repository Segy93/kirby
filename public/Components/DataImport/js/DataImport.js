var evtSource;

var config = {
	import_in_progress: false,
	percent_category: 0,
	percent_product:0,
	write_buffer: [],
	imported_pictures: [],
	import_errors: [],
	finished: '',
}

function updateProgressBar(
	category_name,
	product_name,
	percent_category,
	percent_product,
	times
) {
	var c_name 				= 	document.getElementsByClassName('category_inner_name')[0];
	var p_name 				= 	document.getElementsByClassName('product_inner_name')[0];
	var percent_c 			= 	document.getElementsByClassName('category_inner')[0];
	var percent_p 			= 	document.getElementsByClassName('product_inner')[0];

	c_name.innerHTML 		= 	category_name;
	p_name.innerHTML 		= 	product_name;
	percent_c.innerHTML 	= 	percent_category + '%';
	percent_p.innerHTML 	= 	percent_product + '%';

	percent_c.style.width 	= 	percent_category + '%';
	percent_p.style.width 	= 	percent_product + '%';

	console.log(times);
}

function start_scroll_down() {
	window.scrollBy(0, 1000);
}

function importProducts() {
	var category = document.getElementById("category_type").value;
	var url = "/db_import";
	var url = (category != "") ? "/db_import/" + category : "/db_import";
	evtSource = new EventSource(url);

	config.import_in_progress = true;
	window.requestAnimationFrame(writeToHtml);
	evtSource.onmessage = function(e) {
		var server_info = JSON.parse(e.data);
		if(server_info.hasOwnProperty("finish")){
			config.finished =  "Gotovo!" + "<br>";
			config.import_in_progress = false;
			evtSource.close();
			start_scroll_down();
		} else {	
			saveData(server_info);
		}
	}

	evtSource.onerror = function(e) {
		console.log(e);
		config.import_in_progress = false;
		evtSource.close();
		
	}
}

function saveData (server_info) {
	if (server_info.type === 'product') {
		var percent_category 	= 	parseInt((server_info.current_category_num / server_info.num_all_categories) * 100);
		var percent_product		=	parseInt((server_info.current_product_num / server_info.num_all_products_category) * 100);

		config.percent_category 		= percent_category;
		config.percent_product  		= percent_product;
		config.current_category_name 	= server_info.current_category_name;
		config.current_product_name  	= server_info.current_product_name;
		config.times  					= server_info.times;
		
		config.write_buffer.push("Trenutni proizvod: " + server_info.current_product_name + "<br/>" + server_info.success);
	} else if (server_info.type === 'pictures') {
		config.imported_pictures.push(server_info.message);
	} else if (server_info.type === 'error') {
		config.import_errors.push(server_info.message);
	}
}

function writeToHtml() {
	updateProgressBar(
		config.current_category_name,
		config.current_product_name,
		config.percent_category,
		config.percent_product,
		config.times
	);
	var element = document.getElementById("product-info");
	var html    = '';
	while(config.write_buffer.length) {
		var info = config.write_buffer.shift();
		html += info + "<br>";
	}

	while(config.imported_pictures.length) {
		var picture = config.imported_pictures.shift();
		html +=  picture + "<br>";
	}

	while(config.import_errors.length) {
		var error = config.import_errors.shift();
		html += "<p class = 'import_error'>"+ error + "</p><br>";
	}

	if (config.finished !== '') {
		html += config.finished;
	}
	element.innerHTML += html;

	start_scroll_down();

	if (config.import_in_progress) {
		window.requestAnimationFrame(writeToHtml);
	}
}

function updateProducts() {

}

function stopImport() {
	config.import_in_progress = false;
	evtSource.close();
}
