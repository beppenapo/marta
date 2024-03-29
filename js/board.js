google.charts.load('current', {'packages':['gantt'], 'language': 'it'});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Task ID');   //nome dell'attività
  data.addColumn('string', 'Task Name'); //nome dell'attività
  data.addColumn('string', 'Resource'); //nome della fase
  data.addColumn('date', 'Start Date');
  data.addColumn('date', 'End Date');
  data.addColumn('number', 'Durata'); // calcolata automaticamente, lascia null
  data.addColumn('number', 'Percent Complete'); // calcola il numero dei giorni
  data.addColumn('string', 'Dependencies'); //da lasciare null per non creare le frecce
  data.addRows([
    ['1', 'Spring 2014', 'spring', new Date(2014, 2, 22), new Date(2014, 5, 20), null, 100, null],
    ['2', 'Summer 2014', 'summer', new Date(2014, 5, 21), new Date(2014, 8, 20), null, 100, null],
    ['3', 'Autumn 2014', 'autumn', new Date(2014, 8, 21), new Date(2014, 11, 20), null, 100, null],
    ['4', 'Winter 2014', 'winter', new Date(2014, 11, 21), new Date(2015, 2, 21), null, 100, null],
    ['5', 'Spring 2015', 'spring', new Date(2015, 2, 22), new Date(2015, 5, 20), null, 50, null],
    ['2015Summer', 'Summer 2015', 'summer', new Date(2015, 5, 21), new Date(2015, 8, 20), null, 0, null],
    ['2015Autumn', 'Autumn 2015', 'autumn', new Date(2015, 8, 21), new Date(2015, 11, 20), null, 0, null],
    ['2015Winter', 'Winter 2015', 'winter', new Date(2015, 11, 21), new Date(2016, 2, 21), null, 0, null],
    ['Football', 'Football Season', 'sports', new Date(2014, 8, 4), new Date(2015, 1, 1), null, 100, null],
    ['Baseball', 'Baseball Season', 'sports', new Date(2015, 2, 31), new Date(2015, 9, 20), null, 14, null],
    ['Basketball', 'Basketball Season', 'sports', new Date(2014, 9, 28), new Date(2015, 5, 20), null, 86, null],
    ['Hockey', 'Hockey Season', 'sports', new Date(2014, 9, 8), new Date(2015, 5, 21), null, 89, null]
  ]);
  var options = {
    height: 400,
    gantt: {
      trackHeight: 30
    }
  };

  var chart = new google.visualization.Gantt(document.getElementById('chart_div'));
  chart.draw(data, options);
}
