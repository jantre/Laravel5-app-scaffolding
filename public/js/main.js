$(function(){
	var page=window.location.href.split('#',2);
	if(page[1]!=undefined){
		console.log("GOT " + page[1]);
		$('#'+page[1]+'_link').removeClass('link');
	}
	$('#settings-menu li a').click(function(){
		$('#settings-menu li a').removeClass('selected');
		$(this).addClass('selected');
		$('#settings-ajax-content').load('/user/settings/ajax/forms/'+$(this).attr('name'));

	});
});