$(function() {
	var slider_aroma = $('#aroma').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 0,
		max: 10,
		step: 1,
		id: 'aroma-slider', 
	});
	
	$('#aroma').on('slideStop', function() {
		$(this).val(slider_aroma.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_taste = $('#taste').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'taste-slider'
	});
	
	$('#taste').on('slideStop', function() {
		$(this).val(slider_taste.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_look = $('#look').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'look-slider'
	});
	
	$('#look').on('slideStop', function() {
		$(this).val(slider_look.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_mouthfeel = $('#mouthfeel').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'mouthfeel-slider'
	});
	
	$('#mouthfeel').on('slideStop', function() {
		$(this).val(slider_mouthfeel.bootstrapSlider('getValue'));
		calcVal();
	});

	var slider_overall = $('#overall').bootstrapSlider({
		ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		ticks_labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		min: 1,
		max: 10,
		step: 1,
		id: 'overall-slider'
	});
	
	$('#overall').on('slideStop', function() {
		$(this).val(slider_overall.bootstrapSlider('getValue'));
		calcVal();
	});

	calcVal();
	
	$('body').on('click', '#submit', function() {
        $('#submit').attr('disabled', 'disabled').val('Processing...');
    });
});

function calcVal() {
	var tot = ($('#aroma').val() * percent_aroma)
		+ ($('#taste').val() * percent_taste)
		+ ($('#look').val() * percent_look)
		+ ($('#mouthfeel').val() * percent_mouthfeel)
		+ ($('#overall').val() * percent_overall);
	
	var avg = roundNumber(tot, 1).toFixed(1);
	$('#overallRating').html(avg);
}

function roundNumber(num, dec) {
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}

$('#dateTasted').datepicker({
	format: 'yyyy-mm-dd'
});