var formComplete = false;
var Setting = function() {
    var basicSetting = function() {
        var givenLat = $('#store_latitude').val();
        givenLat = Number(givenLat);
        var givenLng = $('#store_longitude').val();
        givenLng = Number(givenLng);

        var myLatLng = {
            lat: givenLat,
            lng: givenLng
        };

        var mapOptions = {
            center: myLatLng,
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.terrain,
            mapTypeControl: false,
            fullscreenControl: false,
            streetViewControl: false,
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var geocoder = new google.maps.Geocoder();

        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        marker = new google.maps.Marker({map: map, position: myLatLng});

        google.maps.event.addListener(map, "click", function(e) {

            //lat and lng is available in e object
            var latLng = e.latLng;
            geocoder.geocode({
                'latLng': latLng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        // alert(results[0].formatted_address);
                        input.value = (results[0].formatted_address);
                    }
                }
            });

            $('#store_latitude').val(latLng.lat());
            $('#store_longitude').val(latLng.lng());

            marker.setMap(null);
            marker = '';

            // Create a marker for each place.
            marker = new google.maps.Marker({map: map, position: latLng});
        });

        var markers = [];
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();

                $('#workspace_lat').val(latitude);
                $('#workspace_lng').val(longitude);

                marker.setMap(null);
                marker = '';

                // Create a marker for each place.
                marker = new google.maps.Marker({map: map, title: place.name, position: place.geometry.location});

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });

        // Monday
        $('#store_open_time_form').find('input[name=mon_opened]').on('change', function(e) {
            
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#mon_open_set').css({'display': 'flex'});
            } else {
                $('#mon_open_set').css({'display': 'none'});
            }
        });

        // Tuesday
        $('#store_open_time_form').find('input[name=tue_opened]').on('change', function(e) {
            
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#tue_open_set').css({'display': 'flex'});
            } else {
                $('#tue_open_set').css({'display': 'none'});
            }
        });

        // Wednesday
        $('#store_open_time_form').find('input[name=wed_opened]').on('change', function(e) {
            
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#wed_open_set').css({'display': 'flex'});
            } else {
                $('#wed_open_set').css({'display': 'none'});
            }
        });

        // Thurday
        $('#store_open_time_form').find('input[name=thu_opened]').on('change', function(e) {
            
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#thu_open_set').css({'display': 'flex'});
            } else {
                $('#thu_open_set').css({'display': 'none'});
            }
        });

        // Friday
        $('#store_open_time_form').find('input[name=fri_opened]').on('change', function(e) {
            
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#fri_open_set').css({'display': 'flex'});
            } else {
                $('#fri_open_set').css({'display': 'none'});
            }
        });

        // Saturday
        $('#store_open_time_form').find('input[name=sat_opened]').on('change', function(e) {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#sat_open_set').css({'display': 'flex'});
            } else {
                $('#sat_open_set').css({'display': 'none'});
            }
        });

        // Sunday
        $('#store_open_time_form').find('input[name=sun_opened]').on('change', function(e) {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#sun_open_set').css({'display': 'flex'});
            } else {
                $('#sun_open_set').css({'display': 'none'});
            }
        });

        $('#store_main_slim_image_form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
            });

            var url = form.attr('action');

            var formData = new FormData(form[0]);
            var submit_btn = form.find('.form-submit-btn');
            submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                    if (response.result === "success") {
                        $('#store_main_img_form').find('input[name=store_main_image]').val(response.image_name);
                        $('#store_main_img_form').find('#store_main_header_img').attr('src', response.image_url);
                        $('#store_main_slim_image_modal').modal('hide');
                        slimDestroy();
                        slimInit();
                    } else {
                        swal({
                            title: "Error",
                            text: 'something went wrong',
                            type: "error",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    }
                },
                processData: false,
                contentType: false,
                error: function(error) {
                    console.log(error);
                }
            });
        });

        var basicForm = $('#store_basic_detail_form');

        var basicFormValid = basicForm.validate({
            ignore: ":hidden",
            rules: {
                store_name: {
                    required: true
                },
                business_name: {
                    required: true,
                },
                phone: {
                    required: true
                },
                deliver_cost: {
                    required: true,
                    number: true
                }
            },
            messages: {},
            errorPlacement: function(error, element) {
            }
        });

        basicForm.on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            if (!basicFormValid.form()) {
                swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
            });

            var url = form.attr('action');

            var formData = new FormData(form[0]);
            var submit_btn = form.find('.submit-btn');
            submit_btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submit_btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    if (response.result === "success") {
                        swal({
                            title: "Success",
                            text: 'Basic detail updated',
                            type: "success",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: 'something went wrong',
                            type: "error",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    }
                },
                processData: false,
                contentType: false,
                error: function(error) {
                    console.log(error);
                }
            });
        });

        var locationForm = $('#store_location_form');

        var locationFormValid = locationForm.validate({
            rules: {
                store_address: {
                    required: true
                },
                latitude: {
                    required: true,
                },
                longitude: {
                    required: true
                },
            },
            messages: {},
            errorPlacement: function(error, element) {
            }
        });

        locationForm.on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            if (!locationFormValid.form()) {
                swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
            });

            var url = form.attr('action');

            var formData = new FormData(form[0]);
            var submit_btn = form.find('.submit-btn');
            submit_btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submit_btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    if (response.result === "success") {
                        swal({
                            title: "Success",
                            text: 'Location updated',
                            type: "success",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: 'something went wrong',
                            type: "error",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    }
                },
                processData: false,
                contentType: false,
                error: function(error) {
                    console.log(error);
                }
            });
        });

        var timeForm = $('#store_open_time_form');

        var timeFormValid = timeForm.validate({
            rules: {

                minimal_delivery_time:{
                    required: true
                },

                mon_open:{
                    required: true
                },
                mon_close:{
                    required: true
                },

                tue_open:{
                    required: true
                },
                tue_close:{
                    required: true
                },

                wed_open:{
                    required: true
                },
                wed_close:{
                    required: true
                },

                thu_open:{
                    required: true
                },
                thu_close:{
                    required: true
                },

                fri_open:{
                    required: true
                },
                fri_close:{
                    required: true
                },

                sat_open:{
                    required: true
                },
                sat_close:{
                    required: true
                },

                sun_open:{
                    required: true
                },
                sun_close:{
                    required: true
                }

            },
            messages: {},
            errorPlacement: function(error, element) {
            }
        });

        timeForm.on('submit', function(e) {



            e.preventDefault();

            var form = $(this);

            if (!timeFormValid.form()) {
                swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
            });

            var url = form.attr('action');

            var formData = new FormData(form[0]);

            console.log(formData);

            var submit_btn = form.find('.submit-btn');
            submit_btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            //route('admin.settings.update.time')
            //Admin\SettingController@updateTime
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submit_btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    if (response.result === "success") {
                        //nk
                        swal({    
                            title: "Success",
                            text: 'Store Open Time updated',
                            type: "success",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    } else {
                        //nk
                        swal({
                            title: "Error",
                            text: 'something went wrong',
                            type: "error",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    }
                },
                processData: false,
                contentType: false,
                error: function(error) {
                    console.log(error);
                }
            });
        });

        var imageForm = $('#store_main_img_form');

        var imageFormValid = imageForm.validate({
            rules: {
                store_main_image: {
                    required: true
                },
            },
            messages: {},
            errorPlacement: function(error, element) {
            }
        });

        imageForm.on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            if (!imageFormValid.form()) {
                swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
            });

            var url = form.attr('action');

            var formData = new FormData(form[0]);
            var submit_btn = form.find('.submit-btn');
            submit_btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submit_btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    if (response.result === "success") {
                        swal({
                            title: "Success",
                            text: 'Store Main image updated',
                            type: "success",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: 'something went wrong',
                            type: "error",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    }
                },
                processData: false,
                contentType: false,
                error: function(error) {
                    console.log(error);
                }
            });
        });

        var descriptionForm = $('#store_description_form');

        var descriptionFormValid = descriptionForm.validate({
            rules: {
                store_description: {
                    required: true
                },
            },
            messages: {},
            errorPlacement: function(error, element) {
            }
        });

        descriptionForm.on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            if (!descriptionFormValid.form()) {
                swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
                }
            });

            var url = form.attr('action');

            var formData = new FormData(form[0]);
            var submit_btn = form.find('.submit-btn');
            submit_btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submit_btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    if (response.result === "success") {
                        swal({
                            title: "Success",
                            text: 'Store Description updated',
                            type: "success",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: 'something went wrong',
                            type: "error",
                            confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                        });
                    }
                },
                processData: false,
                contentType: false,
                error: function(error) {
                    console.log(error);
                }
            });
        });
    };

    var initPlugin = function() {
        $('.m_selectpicker').selectpicker();
        $('.m-timepicker').timepicker({showMeridian:!1});
    }

    var slimInit = function() {
        storeMainPhoto = new Slim(document.getElementById('store_main_slim_image_slim'), {
            ratio: '5:3',
            minSize: {
                width: 100,
                height: 100
            },
            download: false,
            label: 'Drop your image here or Click',
            statusImageTooSmall: 'Image too small. Min Size is $0 pixel. Try again.'
        });

        storeMainPhoto.size = {
            width: 1000,
            height: 1000
        };
    }

    var slimDestroy = function() {
        storeMainPhoto.destroy();
    }

    return {
        // public functions
        init: function() {
            var storeMainPhoto;
            basicSetting();
            initPlugin();
            slimInit();
        }
    };
}();


jQuery(document).ready(function() {
    Setting.init();
});
