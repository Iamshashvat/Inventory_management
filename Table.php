<!DOCTYPE HTML>
<html>
<head>  
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
var dataJSON={};


function addData(data){
	//console.log(data.mA);
	dataJSON=data;
	
	drawGraph();
}
function drawGraph(){
	
	//$.getJSON("migratedata.json", addData);
	console.log(dataJSON);
	var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Inventory Transfer Graphs",
		fontFamily: "arial black",
		fontColor: "#695A42"
	},
	axisX: {
		interval: 1,
		intervalType: "year"
	},
	axisY:{
		valueFormatString:"#",
		gridColor: "#B6B1A8",
		tickColor: "#B6B1A8"
	},
	toolTip: {
		shared: true,
		content: toolTipContent
	},
	data: [{
		type: "stackedColumn",
		showInLegend: true,
		color: "#696661",
		name: "Quantity",
		dataPoints: [
			{ y: parseInt(dataJSON.qInA), label:"Bangalore"  },
			{ y: parseInt(dataJSON.qInB), label:"Delhi"  },
			{ y: parseInt(dataJSON.qInC), label:"Pune" }
		]
		},
		{        
			type: "stackedColumn",
			showInLegend: true,
			name: "Threshhold",
			color: "#EDCA93",
			dataPoints: [
				{ y: parseInt(dataJSON.mA), label:"Bangelore"  },
				{ y: parseInt(dataJSON.mB), label:"Delhi" },
				{ y: parseInt(dataJSON.mC), label:"Pune" }
			]
		}]
});
chart.render();

function toolTipContent(e) {
	var str = "";
	var total = 0;
	var str2, str3;
	for (var i = 0; i < e.entries.length; i++){
		var  str1 = "<span style= \"color:"+e.entries[i].dataSeries.color + "\"> "+e.entries[i].dataSeries.name+"</span>: <strong>"+e.entries[i].dataPoint.y+"</strong><br/>";
		total = e.entries[i].dataPoint.y + total;
		str = str.concat(str1);
	}
	//str2 = "<span style = \"color:DodgerBlue;\"><strong>"+(e.entries[0].dataPoint.x).getFullYear()+"</strong></span><br/>";
	//total = Math.round(total * 100) / 100;
	//str3 = "<span style = \"color:Tomato\">Total:</span><strong> $"+total+"</strong>bn<br/>";
	//return (str2.concat(str)).concat(str3);
	return str;
}
}


window.onload = function () {
	$.getJSON("migratedata.json", addData);
	//drawGraph();
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>