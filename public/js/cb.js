// ENABLE BOOTSTRAP TOOLTIPS
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
})

// CHAT
$(document).ready(function () {
    // collapse chat tab
    $(".chat__header").on("click", function () {
        if ($(this).closest(".chat__tab").hasClass("collapsed")) {
            $(this).closest(".chat__tab").removeClass("collapsed");
        } else {
            $(this).closest(".chat__tab").addClass("collapsed");
        }
    });

    // close chat tab
    $(".chat__tab .close").on("click", function () {
        $(this).closest(".chat__tab").remove();
    });

    // scroll oChat to bottom
    $('.oChat__body').scrollTop($('.oChat__body').prop('scrollHeight'));

    // FLAG SELECT
    $('select.flag-selector').change(function () {
        let flag = $('option:selected', this).data('flag');
        $(this).css('background-image', 'url(' + flag + ')');
    }).change();
});

function mainMap() {

    let oficinaPrincipal = {lat: -34.6046, lng: -58.4015};

    // map options
    let settings = {
        zoom: 15,
        center: oficinaPrincipal,
        zoomControl: false,
        fullscreenControl: false,
        mapTypeControl: false,
        streetViewControl: false,
        styles:
            [
                {
                    "featureType": "all",
                    "elementType": "all",
                    "stylers": [
                        {
                            "hue": "#008eff"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [
                        {
                            "saturation": -70
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        },
                        {
                            "saturation": -60
                        }
                    ]
                }
            ]
    };

    if (document.getElementById('main-office')) {
        let mapOficinaPrincipal = new google.maps.Map(document.getElementById('main-office'), settings);
    }
}
