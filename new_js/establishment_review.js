$(function()
{
	$('#submit-disabled').hide();

	var slider_drink = $('#drink').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 0,
		max: 10,
		step: 1,
		id: 'drink-slider', 
	});
	
	$('#drink').on('slideStop', function() {
		$(this).val(slider_drink.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_service = $('#service').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'service-slider'
	});
	
	$('#service').on('slideStop', function() {
		$(this).val(slider_service.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_atmosphere = $('#atmosphere').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'atmosphere-slider'
	});
	
	$('#atmosphere').on('slideStop', function() {
		$(this).val(slider_atmosphere.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_pricing = $('#pricing').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'pricing-slider'
	});
	
	$('#pricing').on('slideStop', function() {
		$(this).val(slider_pricing.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_accessibility = $('#accessibility').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'accessibility-slider'
	});
	
	$('#accessibility').on('slideStop', function() {
		$(this).val(slider_accessibility.bootstrapSlider('getValue'));
		calcVal();
	});

	calcVal();
	
	$('body').on('click', '#submit', function() {
        $(this).hide();
        $('#submit-disabled').show();
    });
});

function calcVal() {
	var tot = ($('#drink').val() * percent_drink)
		+ ($('#service').val() * percent_service)
		+ ($('#atmosphere').val() * percent_atmoshpere)
		+ ($('#pricing').val() * percent_pricing)
		+ ($('#accessibility').val() * percent_accessibility);
	
	var avg = roundNumber(tot, 1).toFixed(1);
	$('#overallRating').html(avg);
}

function roundNumber(num, dec) {
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}

$('#dateVisited').datepicker({
	format: 'yyyy-mm-dd'
});
