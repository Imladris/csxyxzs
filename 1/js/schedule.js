function load_weeks(id){
		var output="";
		for(i=1;i<=20;i++){
				output += '<li><a href="?id='+id+'&&week='+i+'">第' + i + '周</a></li>';
		}
		document.getElementById("weekslist").innerHTML = output;
}
