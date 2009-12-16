function relative_time(time_value) {
	var values = time_value.split(" ");
	time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
	var parsed_date = Date.parse(time_value);
	var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
	var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
	delta = delta + (relative_to.getTimezoneOffset() * 60);
	 
	if (delta < 60) {
	return 'menos de un minuto';
	} else if(delta < 120) {
	return 'hace un minuto';
	} else if(delta < (45*60)) {
	return (parseInt(delta / 60)).toString() + ' minutos atr&aacute;s';
	} else if(delta < (90*60)) {
	return 'hace una hora';
	} else if(delta < (24*60*60)) {
	return (parseInt(delta / 3600)).toString() + ' horas atr&aacute;s';
	} else if(delta < (48*60*60)) {
	return 'ayer';
	} else {
	return (parseInt(delta / 86400)).toString() + ' d&iacute;as atr&aacute;s';
	}
}
 
function twitterCallback(obj) {
	var id = obj[0].user.id;
	document.getElementById('my_twitter_status').innerHTML = obj[0].text;
	document.getElementById('my_twitter_status_time').innerHTML = relative_time(obj[0].created_at);
}
