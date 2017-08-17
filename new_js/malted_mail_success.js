$(function()
{
	var counter = 0;
	var start = 5;
	$('#timer').text(start);
	var interval = setInterval(function() {
		counter++;
		$('#timer').text((start - counter));
		if (counter == start) {
			clearInterval(interval);
			window.location.replace(window.location.origin + '/pms/view');
		}
	}, 1000);
});