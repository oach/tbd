$(function() {
	function initMap() {
		var latlng = new google.maps.LatLng(establishment_latitude, establishment_longitude);
	    var settings = {
	        zoom: 10,
	        center: latlng,
	        mapTypeControl: true,
	        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
	        navigationControl: true,
	        navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
	        scaleControl: true,
	        mapTypeId: google.maps.MapTypeId.ROADMAP};
	    var map = new google.maps.Map(document.getElementById('map'), settings);
	    
	    var company_image = new google.maps.MarkerImage('/images/beer_pointer.gif',
	        new google.maps.Size(25,40),
	        new google.maps.Point(0,0),
	        new google.maps.Point(25,40)
	    );

	    var locations = establishment_locations;
	    
	    var info_window = new google.maps.InfoWindow();
	    
	    var marker, i;
	    
	    for (i = 0; i < locations.length; i++) {
	        if (i == 0) {
		        marker = new google.maps.Marker({
		            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		            map: map,
		            icon: company_image,
		            title: locations[i][4],
		            zIndex: 100
		        });
		    } else {
		    	//var pin_color = makeMarker(i);
		    	var pin_color = '228b22';
		    	var pin_image = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pin_color,
			        new google.maps.Size(21, 34),
			        new google.maps.Point(0,0),
			        new google.maps.Point(10, 34)
			    );
			    marker = new google.maps.Marker({
					position: new google.maps.LatLng(locations[i][1], locations[i][2]),
					map: map,
					//icon: pin_image,
					title: locations[i][4],
					zIndex: (locations.length - i),
					label: i.toString()
				});
			}
	        
	        google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
	                info_window.setContent(locations[i][0]);
	                info_window.open(map, marker);
	            }
	        })(marker, i));
	    }    
	}
	google.maps.event.addDomListener(window, 'load', initMap);
});

function makeMarker(count)
{
	count = count - 1;
	var colors = ['666666', 'cc0000', 'ffa500', 'ffff00', '556b2f', '228b22', '008080', '0000ff', '000080', '800080'];
	
	/*var colors = [
		'023fa5', '7d87b9', 'bec1d4', 'd6bcc0', 'bb7784', '8e063b',
		'4a6fe3', '8595e1', 'b5bbe3', 'e6afb9', 'e07b91', 'd33f6a',
		'11c638', '8dd593', 'c6dec7', 'ead3c6', 'f0b98d', 'ef9708',
		'0fcfc0', '9cded6', 'd5eae7', 'f3e1eb', 'f6c4e1', 'f79cd4'
	];*/
	if (colors.length >= count) {
		return colors[count];
	}
	return colors[0];
}