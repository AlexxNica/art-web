/*
* Your custom javascript stuff goes here	
*/ 
function interrogate(what) {
	var output = '';
	for (var i in what)
	output += i+ '\n';
	alert(output);
}


var Login = {
	
	options : {},
	
	start : function(url) {
		this.options.url = url;
		if ($$('mini_login')) Login.loadEvents();
	},

	loadEvents : function(){
		$$('#mini_login .submitter').addEvents({
			'click': function(event){
				//event = new Event(event).stop();
				login = $('mini_login');
				login.submit();
			}
		
		});
	}
}

function init_AGO(url){
	Login.start(url);
}
	
