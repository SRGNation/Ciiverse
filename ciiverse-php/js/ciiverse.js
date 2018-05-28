var offset = 2;

$(document).ready(function(){

	$(document).on('click','.empathy-button', function(){

		var post = $(this).attr('id'); 
		var yeahType = $(this).attr('data-yeah-type');
		var remove = $(this).attr('data-remove');

		$("#"+post).attr('disabled', '');

		$.post('/yeah.php', {post:post, yeahType:yeahType, remove:remove}, function(data){

		//If the yeah fails then yeah.php will just return "error"
		if(data == 'error') {
			alert('An error occured.');
		} else {
			if(remove == 0) {
			$('#'+post).find('.empathy-button-text').text('Unyeah');
			$('#'+post).closest('div').find('.empathy-count').text(Number($('#'+post).closest('div').find('.empathy-count').text()) + 1);
			$("#"+post).removeAttr('disabled');
			$("#"+post).removeAttr('data-remove');
			$("#"+post).attr('data-remove','1');
			} else {
			$('#'+post).find('.empathy-button-text').text(data);
			$('#'+post).closest('div').find('.empathy-count').text(Number($('#'+post).closest('div').find('.empathy-count').text()) - 1);
			$("#"+post).removeAttr('disabled');
			$("#"+post).removeAttr('data-remove');
			$("#"+post).attr('data-remove','0');
			}
		}
		});

	});

	$(document).on("click","#LMPIC",function(){

		var commute_id = $(this).attr('commute_id');

		$(this).text("Loading...");

		$("#community-post-list").load("/communities.php?cid=" + commute_id + "&offset=" + offset + " #community-post-list");
			new_offset = offset + 1;
			offset = new_offset;

	});

	$(document).on("click",".textarea",function(){

		$(".feeling-selector").removeAttr('style');	
		$("#url-stuff").removeAttr('style');
		$(".form-buttons").removeAttr('style');				

	});

	$(document).on("click",".follow-button", function(){	
		var UserID = $(this).attr('data-user-id');	
		$.post('/follow.php', {UserID:UserID, followType:"follow"}, function(data) {
		if(data == 'error') {	
		alert("An error occured.");
		} else {
		$('.user-sidebar').find('[data-user-id="' + UserID + '"]').addClass('unfollow-button').removeClass('follow-button');
		$('.list').find('[data-user-id="' + UserID + '"]').addClass('none').next('.follow-done-button').removeClass('none').removeAttr("disabled");;
		}
	})});

	$(document).on('click','.unfollow-button', function(){
		var UserID = $(this).attr('data-user-id');
		$.post('/follow.php', {UserID:UserID, followType:"unfollow"}, function(data) {
			if(data == 'error') {
			alert('An error occured');
    		} else {
    		$('.unfollow-button').addClass('follow-button').removeClass('unfollow-button');
    		}
	})});

	$(document).on("click","#show_full",function(){

		$(this).closest('div').find('#not-full-post').attr('style','display: none;');
		$(this).closest('div').find('#full-post').removeAttr('style');	
		$(this).attr('style','display: none;');			

	});

});