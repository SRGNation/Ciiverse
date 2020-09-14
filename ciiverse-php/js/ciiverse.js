var offset = 1;

$(document).ready(function(){

	function getNotifs() {
	$.getJSON('/check_update.json', function(data) {
    if (data.notifs.unread_count > 0) {
    	$('.badge').show().text(data.notifs.unread_count);
    } else {
    	$('.badge').hide().text(data.notifs.unread_count);
    }
	});
	}

    setInterval(function(){ 
    	getNotifs();
    }, 30000);

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
		var date_time = $(this).attr('date_time');

		$(this).text("Loading...");
		$("#LMPIC").attr("disabled");
		$("#LMPIC").addClass("disabled");

		$.get('/communities.php?cid=' + commute_id + '&offset=' + offset + "&date_time=" + date_time, function(data) {
		    $(".post-list").append(data);
		    $("#post-load").remove();
		    if(data !== ''){
		    $(".post-list").append('<div id="post-load"><center><button id="LMPIC" class="black-button apply-button" commute_id="' + commute_id + '" date_time="' + date_time + '">Load More posts</button></center></div>');
		    }
		});

		//$("#community-post-list").load("/communities.php?cid=" + commute_id + "&offset=" + offset + " #community-post-list");
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

	$(document).on("click","#pfptype_1",function(){	

		$('.file-button-container').removeClass('none');
		$('.custom').removeClass('none');
		$('.mii').addClass('none');
		$('.default').addClass('none');

	});

	$(document).on("click","#pfptype_2",function(){	

		$('.file-button-container').addClass('none');
		$('.custom').addClass('none');
		$('.mii').removeClass('none');
		$('.default').addClass('none');

	});

	$(document).on("click","#pfptype_3",function(){	

		$('.file-button-container').addClass('none');
		$('.custom').addClass('none');
		$('.mii').addClass('none');
		$('.default').removeClass('none');

	});


	$(document).on("click","#show_full",function(){

		$(this).closest('div').find('#not-full-post').attr('style','display: none;');
		$(this).closest('div').find('#full-post').removeAttr('style');	
		$(this).attr('style','display: none;');			

	});

	//I'm sorry for stealing your Cedar code Seth please don't kill me with your Cedar sword!!!!11
	$('#post-form').off().on('submit', function(e){

		e.preventDefault();
		$(this).find('.post-button').addClass('disabled').attr('disabled', '');
		if ($(this).find('.file-button').val()) {
			var formData = new FormData(this);
		} else {
			var formData = new FormData();
			formData.append('csrf_token', $(this).find('input[name="csrf_token"]').val());
			formData.append('communityid', $(this).find('input[name="communityid"]').val());
			formData.append('makepost', $(this).find('textarea[name="makepost"]').val());
			formData.append('feeling_id', $(this).find('input[name="feeling_id"]:checked').val());
			formData.append('url', $(this).find('input[name="url"]').val());
		}
		var code;

		$.ajax({url: $(this).attr('action'), type: 'POST', data: formData,

		statusCode: {
			201: function() {
				var code = 201;
			}
		},

		success:function(data) {

			if ($('#post-form').attr('action').substr(-7) == 'replies') {
				$('.reply-list').append(data);
			} else if ($('.post-list').length) {
				$('.post-list').prepend(data);
			}

			if (code !== 201) {
				$('.no-reply-content').remove();
				$('.no-content').remove();
				$('.post').fadeIn();
				$('.feeling-button').removeClass('checked');
				$('.feeling-button-normal').addClass('checked');
				$('#post-form').each(function(){this.reset();});

				//remove the textarea and add it back because paste.js is h*tero
				$('#post-form').find('.textarea-text').removeClass('pastable').removeClass('pastable-focus');
				textarea = $('#post-form').find('.textarea-text').parent().html();
				$('#post-form').find('.textarea-text').remove();
				$('#post-form').find('.textarea-container').html(textarea);

				$('.file-button-container').replaceWith('<label class="file-button-container"><span class="input-label">File upload <span>PNG, JPG, BMP, and GIF are allowed.</span></span><input type="file" class="file-button" name="image" accept="image/*,.mp3,.ogg,.webm"></label>');
			}

			$("#post-form").find('.post-button').removeClass('disabled').removeAttr('disabled');
		}, contentType: false, processData: false});
	});

});