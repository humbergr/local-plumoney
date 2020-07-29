// TRANSACTIONS CHARTS
// ---- random numbers function for testing data
var random = function random() {
  return Math.round(Math.random() * 100);
};

var pieChart = new Chart($('#out-transactions'), {
  type: 'pie',
  data: {
    labels: ['1 - 500', '500 - 1000', '1000 - 1500', '1500 - 2000'],
    datasets: [{
      data: [300, 50, 100, 250],
      backgroundColor: ['#f06455', '#0055d9', '#1ca60c', '#f8a113'],
      hoverBackgroundColor: ['#f06455', '#0055d9', '#1ca60c', '#f8a113']
    }]
  },
  options: {
    responsive: true,
    legend: {
        labels: {
            fontSize: 10,
            fontColor: '#a0abb4',
            fontStyle: 'bold',
            boxWidth: 25,
            padding: 8
        }
    }
  }
});

var barChart = new Chart($('#cur-transactions'), {
  type: 'bar',
  data: {
    labels: ['DOLLARS', 'VES', 'PESOS', 'EUROS'],
    datasets: [
    {
        backgroundColor: [
            '#f06455',
            '#0055d9',
            '#1ca60c',
            '#f8a113'
        ],
        // label: ['DOLLARS ', 'VES ', 'PESOS ', 'EUROS'],
        // borderColor: 'rgba(151, 187, 205, 0.8)',
        // highlightFill: 'rgba(151, 187, 205, 0.75)',
        // highlightStroke: 'rgba(151, 187, 205, 1)',
        data: [random(), random(), random(), random()],
    },
]
  },
  options: {
    responsive: true,
    legend: {
        display: false,
    },
    scales: {
      xAxes: [{
        ticks: {
          maxRotation: 90,
          minRotation: 80,
          display: false,
        },
        gridLines: {
            display: false,
        },
            barThickness: 60,
            minBarLength: 10,
      }],
      yAxes: [{
        ticks: {
          display: false
      },
        gridLines: {
            display: false,
        }
      }]
    },
    title: {
        display: true,
        text: 'DOLLARS - VES - PESOS - EUROS'
    }
  }
});

var cardChart2 = new Chart($('#earn-transactions'), {
  type: 'line',
  data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [{
      backgroundColor: 'rgba(255,255,255,.1)',
      borderColor: '#0055d9',
      borderWidth: 2,
      pointRadius: 0,
      data: [random(), random(), random(), random(), random(), random(), random(), ]
    },
    {
        backgroundColor: '#f06455',
        borderColor: 'rgba(255,255,255,1)',
        pointRadius: 0,
        data: [random(), random(), random(), random(), random(), random(), random(), ]
    }]
  },
  options: {
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
          display: false,
          gridLines: {
          display: false,
          color: 'transparent',
          zeroLineColor: 'transparent'
        },
        ticks: {
            display: false,
            fontSize: 2,
            fontColor: 'transparent'
        }
      }],
      yAxes: [{
        display: false,
        ticks: {
            display: false,
            // min: -4,
            // max: 39
        }
      }]
    },
    elements: {
      line: {
        tension: 0.00001,
        borderWidth: 1
      },
      point: {
        radius: 4,
        hitRadius: 10,
        hoverRadius: 4
      }
    }
  }
});


// BOOTSTRAP 4 DATEPICKER
$('.analytic-datepicker').datepicker({
    format: "dd/mm/yyyy",
    weekStart: 1,
    maxViewMode: 2,
    todayBtn: "linked",
    language: "es",
    daysOfWeekHighlighted: "0,6",
    todayHighlight: true
});

// ENABLE BOOTSTRAP TOOLTIPS
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
})


// CHAT
$(document).ready(function() {

    // collapse chat tab
    $(".chat__header").on("click", function() {
        if ($(this).closest(".chat__tab").hasClass("collapsed")) {
            $(this).closest(".chat__tab").removeClass("collapsed");
        } else {
            $(this).closest(".chat__tab").addClass("collapsed");
        }
    });

    // close chat tab
    $(".chat__tab .close").on("click", function() {
        $(this).closest(".chat__tab").remove();
    });

});

// scroll chat to bottom -not working for multiple tabs-
// $('.chat__body').scrollTop($('.chat__body').prop('scrollHeight'));


// FLAG SELECT
/* $('select.flag-selector').change(function() {
    var flag = $('option:selected',this).data('flag');
    $(this).css('background-image', 'url(' + flag + ')');
}).change(); */

// CREATION DATE FILTER
// $(function() {
//     $('#creation-date-filter').daterangepicker({
//         opens: 'center'
//     }, function(start, end, label) {
//         console.log("A new date selection was made: " + start.format('DD-MM-YYY') + ' to ' + end.format('DD-MM-YYYY'));
//     });
// });


$(function() {
    var start = moment().subtract(6, 'days');
    var end = moment();

    function cb(start, end) {
        $('#creation-date-filter').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#creation-date-filter').daterangepicker({
        opens: 'center',
        startDate: start,
        endDate: end,
        ranges: {
           'Hoy': [moment(), moment()],
           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
           'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
           'Este mes': [moment().startOf('month'), moment().endOf('month')],
           'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});
