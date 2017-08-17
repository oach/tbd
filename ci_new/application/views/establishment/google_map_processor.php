<?php
if (!empty($establishment_id) && !empty($address)) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    </head>
    <body>
        <div id="container"></div>

        <script>
        var lat = false;
        var lng = false;
        
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json',
            cache: false,
            type: 'GET',
            async: true,
            data: {
                address: '<?php echo $address; ?>',
                key: '<?php echo GOOGLEAPI_NEW; ?>',
                sensor: false
            },
            success: function(data) {
                if (data.status == 'OK') {
                    lat = data.results[0].geometry.location.lat;
                    lng = data.results[0].geometry.location.lng;
                    setLatAndLng(lat, lng);
                }
            }
        }); 

        function setLatAndLng(lat, lng) {
            console.log(lat);
            console.log(lng);
            $.ajax({
                url: '<?php echo base_url(); ?>establishment/ajax_google_map_lat_lng',
                cache: false,
                type: 'POST',
                data: {
                    id: <?php echo $establishment_id; ?>, 
                    latitude: lat,
                    longitude: lng
                }
            });
        }
        </script>
    </body>
</html>
<?php
}
?>