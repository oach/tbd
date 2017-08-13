$(function() {
	$('body').on('load change', '#seasonal', function() {
		if ($(this).val() == 1) {
			$('#seasonalPeriod').parent('div').show();
		}
		else {
			$('#seasonalPeriod').val('').parent('div').hide();
		}
	});		
});