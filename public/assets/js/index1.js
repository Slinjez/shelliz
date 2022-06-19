$(function(e){
  
	/*jvectormap*/
	
	
	/*sparkline*/
    var randomizeArray = function (arg) {
		var array = arg.slice();
		var currentIndex = array.length,
		temporaryValue, randomIndex;
		while (0 !== currentIndex) {
			randomIndex = Math.floor(Math.random() * currentIndex);
			currentIndex -= 1;

			temporaryValue = array[currentIndex];
			array[currentIndex] = array[randomIndex];
			array[randomIndex] = temporaryValue;
		}
		return array;
    }
	
	var sparklineData = [0, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];
	//Spark1
    var spark1 = {
      chart: {
        type: 'area',
        height: 60,
		width: 160,
        sparkline: {
          enabled: true
        },
		dropShadow: {
			enabled: true,
			blur: 3,
			opacity: 0.2,
		}
		},
		stroke: {
			show: true,
			curve: 'smooth',
			lineCap: 'butt',
			colors: undefined,
			width: 2,
			dashArray: 0,      
		},
      fill: {
        gradient: {
          enabled: false
        }
      },
      series: [{
		name: 'Total Revenue',
        data: randomizeArray(sparklineData)
      }],
      yaxis: {
        min: 0
      },
      colors: ['#4454c3'],

    }
	var spark1 = new ApexCharts(document.querySelector("#spark1"), spark1);
    //spark1.render();
  
	var sparklineData2 = [0, 45, 93, 53, 61, 27, 54, 43, 19, 46, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, ];
	//Spark2
    var spark2 = {
      chart: {
        type: 'area',
        height: 60,
		width: 160,
        sparkline: {
          enabled: true
        },
		dropShadow: {
			enabled: true,
			blur: 3,
			opacity: 0.2,
		}
		},
		stroke: {
			show: true,
			curve: 'smooth',
			lineCap: 'butt',
			colors: undefined,
			width: 2,
			dashArray: 0,      
		},
		fill: {
        gradient: {
          enabled: false
        }
      },
      series: [{
		name: 'Unique Visitors',
        data: randomizeArray(sparklineData2)
      }],
      yaxis: {
        min: 0
      },
      colors: ['#2dce89'],

    }
	var spark2 = new ApexCharts(document.querySelector("#spark2"), spark2);
    //spark2.render();
	
	var sparklineData3 = [0, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46,45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51];
	//Spark3
    var spark3 = {
      chart: {
        type: 'area',
        height: 60,
		width: 160,
        sparkline: {
          enabled: true
        },
		dropShadow: {
			enabled: true,
			blur: 3,
			opacity: 0.2,
		}
		},
		stroke: {
			show: true,
			curve: 'smooth',
			lineCap: 'butt',
			colors: undefined,
			width: 2,
			dashArray: 0,      
		},
		fill: {
        gradient: {
          enabled: false
        }
      },
      series: [{
		name: 'Expenses',
        data: randomizeArray(sparklineData3)
      }],
      yaxis: {
        min: 0
      },
      colors: ['#ff5b51'],

    }
	//var spark3 = new ApexCharts(document.querySelector("#spark3"), spark3);
    //spark3.render();
	
	/*----P-scrolling JS ----*/
	// const ps31 = new PerfectScrollbar('.countryscroll', {
	//   useBothWheelAxes:true,
	//   suppressScrollX:true,
	// });
	/*-----P-scrolling JS -----*/

	/*----P-scrolling JS ----*/
	// const ps32 = new PerfectScrollbar('#scrollbar', {
	//   useBothWheelAxes:true,
	//   suppressScrollX:true,
	// });
	/*-----P-scrolling JS -----*/

	/*----P-scrolling JS ----*/
	// const ps33 = new PerfectScrollbar('#scrollbar2', {
	//   useBothWheelAxes:true,
	//   suppressScrollX:true,
	// });
	/*-----P-scrolling JS -----*/

	/*----P-scrolling JS ----*/
	// const ps34 = new PerfectScrollbar('#scrollbar3', {
	//   useBothWheelAxes:true,
	//   suppressScrollX:true,
	// });
	/*-----P-scrolling JS -----*/
	
 });