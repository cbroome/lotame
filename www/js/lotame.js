(function ($) {


Audience = Backbone.Model.extend({
   
   name:        '',
   page_views:  0,
   target_code: '',
   uniques:     0
        
});



Audiences = Backbone.Collection.extend({
        //This is our Friends collection and holds our Friend models
        initialize: function (models, options) {
         

         
         
        }
});


AppView = Backbone.View.extend({
        el: $("#target-table"),
        initialize: function() {
            var $self = this; 
            this.audiences = new Audiences( null, { view: this });
           // Load available audiences
           $.get('http://dev.christopherbroome.com/?p=service', function(data){
              if (data instanceof Array) {
		 // Per instructions, just grab the first 20...
                 for (var i=0, obj = null; (obj=data[i]) && (i < 20); i++) {
                    var aud_model = new Audience({
                       name:          obj.audienceName,
                       page_views:    obj.pageViews,
                       target_code:   obj.audienceTargetingCode ? obj.audienceTargetingCode : "&nbsp;",
                       uniques:       obj.uniques,
                    });
                    $self.audiences.add( aud_model );
                    
                 }
                 $self.getSorted( 'name' );
              }
           }, "json");

	   // Allow for sorting:
	   $("#target-table th").click( function(){ $self.resort(this, $self); } );

             
        },
        events: {
        },
        
        
        /**
         *
         * @param string   sort  The property to sort on...
	 * @param string   direction ASC or DESC
         */
        getSorted: function(sort, direction) {
            
            var arr = [];
            var sort_column = '';
            var tbody = $("#target-table tbody");
	    if(direction == undefined || direction == null) {
              direction = 'ASC'; 
            }            

	    $("#target-table th").removeClass('ASC').removeClass('DESC').removeClass('active');
            tbody.empty();
            
            if (sort == undefined || sort == null) {
               sort_column = 'name';
            }
            else {
               sort_column = sort;
            }
            this.audiences.each( function(aud) {
               arr.push( aud );
            });
            
            if(direction == 'ASC') {
            	arr.sort( function(a, b) {
            	   if(a.get(sort_column) == b.get(sort_column)){
            	     return 0;
            	   }
             
            	   return a.get(sort_column) > b.get(sort_column) ? 1 : -1;               
            	});
	    }
	    else {
            	arr.sort( function(a, b) {
           	    if(b.get(sort_column) == a.get(sort_column)){
           	      return 0;
           	    }

           	    return b.get(sort_column) > a.get(sort_column) ? 1 : -1;
            	});
	    }
            
            
            for( var i = 0, obj = null; obj = arr[i]; i++ ) {

	       var string = '<tr>';
	       
               for(var prop in obj.attributes) {
		var row_class = 's_' + prop;
		if(sort_column == prop) {
			row_class += ' active';
		}
		if( i % 2 == 0 ) {
			row_class += ' even';
		}
		else {
			row_class += ' odd';
		}
	        string += '<td class="'+row_class+'">' + obj.get(prop) + '</td>';
	       }

	       string += '</tr>';

               tbody.append( string );
            }

	    // Change the th tag to reflect the direction
	    $("th.s_"+sort_column).addClass( direction ).addClass('active'); 
         
        },

	resort: function( $this, $view ) {
		var classname = $($this).attr('class'),
			sort_column = classname.match(/s_([a-z_]+)/)[1], 
			direction = classname.match(/ASC/) ? 'DESC' : 'ASC';
		
		if(sort_column) {
			$view.getSorted( sort_column, direction );
		}
	},


        renderRow: function(){
                
        }
});





var appview = new AppView;

})(jQuery);

