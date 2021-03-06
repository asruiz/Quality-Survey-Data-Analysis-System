
var randomScalingFactor = function() 
{
    return Math.round(Math.random() * 100);
};

var randomColorFactor = function() 
{
    return Math.round(Math.random() * 255);
};

var randomColor = function(opacity) 
{
    return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
};


//-------------------------------------------------------------------------------------------------
// Process bar chart
var xmlhttp1 = new XMLHttpRequest();
var url1 = "http://localhost/afpcgsc_lib/core/subclasses/dashboard_chart.php?q=latest";

xmlhttp1.onreadystatechange = function() 
{
    if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200)
    {
        try
        {
            var result_data = xmlhttp1.responseText;
            var result_arr = result_data.split("|");            

            var obj = JSON.parse(result_data);                 

            var config1 = 
                {
                    type: 'bar',
                    data: 
                    {
                        datasets: obj,
                        labels: 
                        [
                            'Overdue',
                            'New',
                            'Extended',
                            'Return'
                        ]
                    },
                    options: 
                    {
                        responsive: true,
                        legend: 
                        {
                            position: 'top',
                        },
                        title: 
                        {
                            display: true,
                            text: 'Weekly Transactions'
                        },
                        animation: 
                        {
                            animateScale: true,
                            animateRotate: true
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                }; 

            if (document.getElementById("chart_canvas1") != null)
            {
                var ctx = document.getElementById("chart_canvas1").getContext("2d");
                window.myDoughnut = new Chart(ctx, config1);
            }            
        }
        catch (e)
        {
            alert(e.message);
        }          
    }
}    
xmlhttp1.open("GET", url1, true);
xmlhttp1.send();


// Process doughnut chart
var xmlhttp2 = new XMLHttpRequest();
var url2 = "http://localhost/afpcgsc_lib/core/subclasses/dashboard_chart.php?q=summary";

xmlhttp2.onreadystatechange = function()
{
    if (xmlhttp2.readyState == 4 && xmlhttp2.status == 200)
    {
        try
        {
            var result_data = xmlhttp2.responseText;
            var result_arr = result_data.split("|");

            var config2 = 
                {
                    type: 'doughnut',
                    data: 
                    {
                        datasets: 
                        [{
                            data: 
                            [
                                result_arr[0],
                                result_arr[1],
                                result_arr[2],
                                result_arr[3]
                            ],
                            backgroundColor: 
                            [
                                '#F7464A',
                                '#46BFBD',
                                '#FDB45C',
                                '#949FB1'
                            ],
                            label: 'Summary'
                        }],
                        labels: 
                        [
                            'Loans',
                            'Extensions',
                            'Returns',
                            'Overdues'
                        ]
                    },
                    options: 
                    {
                        responsive: true,
                        legend: 
                        {
                            position: 'top',
                        },
                        title: 
                        {
                            display: true,
                            text: 'Summary'
                        },
                        animation: 
                        {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                };                            

            if (document.getElementById("chart_canvas2") != null)
            {
                var ctx = document.getElementById("chart_canvas2").getContext("2d");
                window.myDoughnut = new Chart(ctx, config2);
            }            
        }
        catch (e)
        {
            alert(e.message);
        }        
    }    
}
xmlhttp2.open("GET", url2, true);
xmlhttp2.send();
//-------------------------------------------------------------------------------------------------    





// $('#randomizeData').click(function() 
// {
//     $.each(config.data.datasets, function(i, dataset) 
//     {
//         dataset.data = dataset.data.map(function() 
//         {
//             return randomScalingFactor();
//         });

//         dataset.backgroundColor = dataset.backgroundColor.map(function() 
//         {
//             return randomColor(0.7);
//         });
//     });

//     window.myDoughnut.update();
// });

// $('#addDataset').click(function() 
// {
//     var newDataset = 
//     {
//         backgroundColor: [],
//         data: [],
//         label: 'New dataset ' + config.data.datasets.length,
//     };

//     for (var index = 0; index < config.data.labels.length; ++index) 
//     {
//         newDataset.data.push(randomScalingFactor());
//         newDataset.backgroundColor.push(randomColor(0.7));
//     }

//     config.data.datasets.push(newDataset);
//     window.myDoughnut.update();
// });

// $('#addData').click(function() 
// {
//     if (config.data.datasets.length > 0) 
//     {
//         config.data.labels.push('data #' + config.data.labels.length);

//         $.each(config.data.datasets, function(index, dataset) 
//         {
//             dataset.data.push(randomScalingFactor());
//             dataset.backgroundColor.push(randomColor(0.7));
//         });

//         window.myDoughnut.update();
//     }
// });

// $('#removeDataset').click(function() 
// {
//     config.data.datasets.splice(0, 1);
//     window.myDoughnut.update();
// });

// $('#removeData').click(function() 
// {
//     config.data.labels.splice(-1, 1); // remove the label first

//     config.data.datasets.forEach(function(dataset, datasetIndex) 
//     {
//         dataset.data.pop();
//         dataset.backgroundColor.pop();
//     });

//     window.myDoughnut.update();
// });


//-------------------------------------------------------------------
