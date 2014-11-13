jQuery(function($){	
	setMailBoxes();
	setTimeout(function() {
		window.print();
	}, 1000);
});
function setMailBoxes() {
	var as=document.getElementsByTagName('a'), dmn, nm;
	for(var i=0;i<as.length;i++) {
		if(as[i].className=='e-mail') {
			dmn=as[i].href.substr(as[i].href.search('#')+1);
			nm=as[i].title;					
			as[i].href='mailto:'+nm+'@'+dmn;
			as[i].title='Написать письмо';					
			if(!as[i].innerHTML) as[i].innerHTML=nm+'@'+dmn;
		}
	}
}