$(document).ready(function() {
        
        // Animate loginlink
        $('.loginlink').hover(function() {
                $(this).animate({left: '+=10px'}, 200);
        }, function() {
                $(this).animate({left: '-=10px'}, 100);
        });
        
        // Animate submenu items
        $('.navbar ul li ul li a').hover(function() {
                $(this).animate({paddingLeft: '+=5px', paddingRight: '-5px'}, 200);
        }, function() {
                $(this).animate({paddingLeft: '-=5px', paddingRight: '+5px'}, 100);
        });
        
        // Scroll to top icon
        
        $(window).scroll(function(){
                if ($(this).scrollTop() > 150) {
                    $('.scrolltotop').fadeIn();
                } else {
                    $('.scrolltotop').fadeOut();
                }
		});
	
		//Click event to scroll to top
		$('.scrolltotop').click(function(){
		        $('html, body').animate({scrollTop : 0},800);
		        return false;
		});
		
		// Show info fields in CForm elements on click
		$('.cf-desc').hide();
		
		$('.cf-info').click(function(){
		        $(this).parent().parent().next().toggle('fast');
		});
		
		// Range sliders
		$('.range-value').hide();
		$('input[type=range]').on('input', function() {
		        $(this).prev().prev('.range-value').show().html(this.value + 'px');
		});
		
		// Validate CForm fields on client side with Validate plugin
		
		// Ignore all on cancel
		$('#form-element-undo').click(function() {
		        $('input, textarea').addClass('ignore');
		});
		
		$('form').validate({
		       rules: {
		           acronym: {
		               required: true,
		               maxlength: 20,
		               minlength: 3
		           },
		           email: {
		               required: true,
		               email: true,
		               maxlength: 80
		           },
		           web: {
		               url: true,
		               maxlength: 200
		           },
		           password: { 
		               minlength: 8,
		               required: true
		           },
		           author: { 
		               minlength: 5,
		               maxlength: 80,
		               required: true
		           },
		           title: {
		               required: true,
		               minlength: 8,
		               maxlength: 110
		           },
		           image: {
		               required: false
		           }
		       },
		       success: function(label) {
		           label.text('✓').addClass('valid');
		       },
		       ignore: '.ignore'
		   }
		);
		
		// Show option list in profile page on click
		$('#option-list').hide();
		
		$('#option-title').click(function(){
		        $('#option-list').toggle('fast');
		});
		
		// Hide comments by default and chow on click
		$('.comments').hide();
		$('.answers').hide();
		
		$('#show-comments').click(function(){
		        $('.comments').toggle('slow');
		});
		$('#show-answers').click(function(){
		        $('.answers').toggle('slow');
		});
		
		/**
		*    Racing game init
		*/
		
		$('#instructions').click(function(){
		        $('#controls').toggle('slow');
        });
  
        $('#start-race').click(function(){
                console.log('Starting Race!');
                $(this).fadeOut('fast');
                setTimeout(function() {
                        $('#racing, #instructions, #laptime, #game-container').fadeIn('slow');
                }, 2000);
                setTimeout(function() {
                        Racing.init('racing');
                        Racing.gameLoop();
                }, 3000);
                
                window.addEventListener("keydown", function(e) {
                // space and arrow keys
                if([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
                    e.preventDefault();
                }
                }, false);
                $('#footer').hide();
          
        });
        
});