//get cookie (purejs)
function getCookie(e){var t=e+"=";var n=document.cookie.split(";");for(var r=0;r<n.length;r++){var i=n[r];while(i.charAt(0)==" ")i=i.substring(1,i.length);if(i.indexOf(t)==0)return i.substring(t.length,i.length)}return null};

// some function that depends on jquery
head.ready(function () {
//cookie policy
if ( $( ".cookie_bar" ).length ) {
	if (getCookie("argos_cookie_policy") == 1) {
    $('.cookie_bar').hide();
	} else {
    $('.cookie_bar').show();
	}	
$('.cookie_accept').click(function(){
document.cookie ='argos_cookie_policy=1;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
$('.cookie_bar').hide();
});
}

//download counter
$('.down_file').click(function(){
var file_id = $(this).attr("data-file");
var file_url = $(this).attr('href');
$.ajax({
type: "GET",
cache: false,
url: 'includes/ajax_served/down_counter.php',
data: "file_id=" + file_id,
success: function(data){
$(location).attr('href',file_url);
}
});
return false;
});
//end

//magnific popup for files
if ( $( ".see_with_magnific").length ) {
	$('.see_with_magnific').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          mainClass: 'mfp-img-mobile',
          image: {
            verticalFit: true
          }
        });
}
//end

//magnific popup for videos
if ( $( ".see_with_magnific_v").length ) {
        $('.see_with_magnific_v').magnificPopup({
          disableOn: 700,
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,
          fixedContentPos: false
        });
}
//end

//dropzone
if ( $( ".dropzone").length ) {
	
	$(".dropzone").dropzone({
	acceptedFiles: "image/*",
	addRemoveLinks: true, 
	clickable: true,
	success: function(file, response){
    $('.dz-filename').append('<br/><a href="'+response+'" class="down_pic">download</a>');
      }
	});
}
//end

//greyfish
if ( $( "#greyfish2" ).length ) {
$("#greyfish2").load( "greyfish/zone.php", function( response, status, xhr ) {if(status == "complete") { $(".greyfish-preload2").remove();}});
}
if ( $( "#greyfish" ).length ) {
$("#greyfish").load( "greyfish/list.php", function( response, status, xhr ) {if(status == "complete") { $(".greyfish-preload").remove();}});
}
//end greyfis

//ajax chat
if ( $( ".main_chat" ).length ) {
updateChat();

document.cookie ='chat_load_argos=1;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'

$('.chat_submit').click(function(){

$.ajax({
type: "POST",
cache: false,
url: 'includes/ajax_served/chat_post.php',
data: "usernamec=" + $('.usernamec').val() + "&text1=" + $('.chat_msg').val() + "&ava=" + $('.user_ava').html(),
success: function(data){
$('.chat_msg').val('');
}
});

for(i=0;i<3;i++) {
 $('.main_chat').animate({scrollTop:$(document).height()}, 'slow');

}

return false;
});
}
//end ajax chat

	
//opacity to sidr button	
for(i=0;i<3;i++) {
$('#open-sidr').fadeTo('slow', 0.5).fadeTo('slow', 1.0);
}
//end

//responsive pagination
    $('.toggle-pagination').click(function(f) {
        $(this).next('.pagination-responsive').slideToggle();
        $(this).toggleClass('active');
        f.preventDefault()
    });
//end resp pagination

//tooltip
$('.tipso').tipso({ 
        background:'#3498db',
		width:'150px',
    });
//end tooltip

//sidr
  $('#open-sidr').sidr({
   name: 'sidr-existing-content',
   source: '#sidr-panel-open',
   side: 'right',
       onOpen: function(name) {
		 $('.tipso').tipso('hide');
         $('.tipso').tipso('destroy');
    },
	onClose: function(name) {
        $('.tipso').tipso( {
		background:'#3498db',
		width:'150px',
		 
		});
    },
  });

//close sidr
$('#sidr-id-close-sidr').click(function() {
 $.sidr('close', 'sidr-existing-content');
});

//style sw
$("link.colour-switcher").attr("href", getCookie("colour-choice")); 
$("a.sidr-class-switch").click(function() {
var style_sw=0; 
style_sw = $("link.colour-switcher").attr("href", $(this).attr("data-rel"));
document.cookie ='colour-choice='+ $(this).attr("data-rel") +';expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/';	
location.reload();
});
//end style sw

//sidr lang buttons 
$('.sidr-class-change_lang').click(function(){
var choosed_lang = $(this).attr("data-language");

if(choosed_lang == "en"){
document.cookie ='argos_en=1;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
document.cookie ='argos_bg=0;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
document.cookie ='argos_ru=0;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
alert('You choosed English!');
location.reload();
}
if(choosed_lang == "bg"){
document.cookie ='argos_en=0;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
document.cookie ='argos_ru=0;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
document.cookie ='argos_bg=1;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
alert('Ти избра Български!');
location.reload();
}
if(choosed_lang == "ru"){
document.cookie ='argos_en=0;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
document.cookie ='argos_bg=0;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
document.cookie ='argos_ru=1;expires=Wed, 31 Oct 3099 08:50:17 GMT;path=/'
alert('Вы выбрали Русский язык!');
location.reload();
}
return false;
})
//end sidr

//slider
$('.pgwSlider').pgwSlider({
    maxHeight : 300,
    intervalDuration : 4000,
	displayControls: true,
	listPosition: 'left',
  }
);
//slider

//back to top
var settings = {
			min: 200,
			scrollSpeed: 400
		},
		toTop = $('.scroll-btn'),
		toTopHidden = true;

	$(window).scroll(function() {
		var pos = $(this).scrollTop();
		if (pos > settings.min && toTopHidden) {
			toTop.stop().fadeIn();
			toTopHidden = false;
		} else if(pos <= settings.min && !toTopHidden) {
			toTop.stop().fadeOut();
			toTopHidden = true;
		}
	});

	toTop.bind('click touchstart', function() {
		$('html, body').animate({
			scrollTop: 0
		}, settings.scrollSpeed);
	});
//end back to top
 
//carousel
$('#mycarousel').jcarousel({
    scroll: 1, //the number of items to scroll by
    auto: 2, //the interval of scrolling
    wrap: 'last', //wrap at last item and jump back to the start
    visible:2
    });
//end

//votes for news
$(".vote-btn").click(function() 
{
var id = $(this).attr("data-my");
var name = $(this).attr("data-vote");
var dataString = 'id='+ id ;
var parent = $('#bid-'+id);

if(name=='upvote')
{

$(this).fadeIn(200).html('');
$.ajax({
   type: "POST",
   url: "includes/ajax_served/up_vote_news.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
   parent.html(html);
  
   }  
 });
 
}
else
{
$(this).fadeIn(200).html('');
$.ajax({
   type: "POST",
   url: "includes/ajax_served/down_vote_news.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
       parent.html(html);
   }
 });
}
  
return false;
});
//end votes for news

//votes for comments
$(".vote-btn2").click(function() 
{
var id = $(this).attr("data-my");
var name = $(this).attr("data-vote");
var dataString = 'id='+ id ;
var parent = $('#bid-'+id);

if(name=='upvote')
{

$(this).fadeIn(200).html('');
$.ajax({
   type: "POST",
   url: "includes/ajax_served/up_vote_comments.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
   parent.html(html);
  
   }  
 });
 
}
else
{
$(this).fadeIn(200).html('');
$.ajax({
   type: "POST",
   url: "includes/ajax_served/down_vote_comments.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
       parent.html(html);
   }
 });
}
  
return false;
});
//end votes for comments

//multipurpose menu

var isLateralNavAnimating = false;
$(".hidden-button").click(function() 
{
		event.preventDefault();
		//stop if nav animation is running 
		 
		if( !isLateralNavAnimating ) {
			if($(this).parents('.csstransitions').length > 0 ) isLateralNavAnimating = true; 
			
			 
			$('body').toggleClass('navigation-is-open');
			$('.cd-navigation-wrapper').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
				//animation is over
				isLateralNavAnimating = false;
			});
			
		}  
		
    var icon = $(this).find("i");
    if(icon.hasClass("fa-arrow-right")){
	   $('.hidden-button i').addClass('fa-arrow-left');
	   $('.hidden-button i').removeClass('fa-arrow-right');
    } else {
       $('.hidden-button i').addClass('fa-arrow-right');
       $('.hidden-button i').removeClass('fa-arrow-left');
    }

});
//end multipurpose menu

//remove chat msg
$( ".container" ).on( "click", ".remove_msg", function() {
var id = $(this).attr("data-my");
	if(confirm('Are you sure?!')) {
$.ajax({
   type: "GET",
   url: "includes/ajax_served/chat_remove.php?id=" + id,
  success: function(html)
  {
   $('#message-' + id).remove();
   alert(html);
  }  
});
  
 
return false;
	} else {
		return false;
	}
});

}); //end head_ready

//popup for emoticons. This is function and the right place is here, not in head ready
function open_pop(){
window.open('assets/emoticons.html','mywin','right=20,top=20,width=500,height=300,toolbar=1,resizable=0');
}

//for updating the chat
setInterval( updateChat, 2000);
var chat_idz;
function updateChat() {
$.get("includes/ajax_served/chat_data.php", {chat_id:chat_idz }, function(data) {
$(".main_chat").html(data['chat']);

if(data['chat_id'] != chat_idz) {
$(".main_chat").scrollTop($(".main_chat")[0].scrollHeight)
}
chat_idz = data['chat_id'];
},"json");

}

/*select banners*/
function hl_text(field) { field.focus(); field.select(); }