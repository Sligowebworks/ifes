function stripe( table, evenColor, oddColor ) {
	var even = false;
	
	var tbody = document.getElementById( table  ).getElementsByTagName( "tbody" );
	for ( var i = 0; i < tbody.length; i++ ) {
		var trs = tbody[i].getElementsByTagName( "tr" );
		for ( var j = 0; j < trs.length; j++ ) {
			trs[j].style.backgroundColor = even ? evenColor : oddColor;
			even = !even;
		}
	}
}