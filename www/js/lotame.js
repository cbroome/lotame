(function ($) {


Audience = Backbone.Model.extend({
   
   name:        '',
   page_views:  0,
   uniques:     0,
   target_code: '',
        
});



Audiences = Backbone.Collection.extend({
        //This is our Friends collection and holds our Friend models
        initialize: function (models, options) {
         

         
         
        }
});


AppView = Backbone.View.extend({
	el: $("body"),

	/**
	 * @var string	last column to trigger a retrieval
	 */
	last_column: '',

        initialize: function() {
            var $self = this; 
            this.audiences = new Audiences( null, { view: this });
           // Load available audiences
	   this.pollService('uniques', 'DESC');
	   // Allow for sorting:
	   $("#target-table th").click( function(){ $self.resort(this, $self); } );

          
        },
        events: {
		"click #export-csv": "exportToCSV"
        },


	/**
	 * pollService
	 *
	 * get fresh results from the web service
	 * 
	 * @param string sort_column
	 * @param string direction 
	 */
	pollService: function( sort_column, direction ) {
	
	  var $self = this; 
	  this.last_column = sort_column; 
	  $self.audiences.reset(); 

          $.get('http://dev.christopherbroome.com/?p=service', function(data){
              if (data instanceof Array) {
                 for (var i=0, obj = null; (obj=data[i]); i++) {
                    var aud_model = new Audience({
                       name:          obj.audienceName,
                       page_views:    obj.pageViews,
                       target_code:   obj.audienceTargetingCode ? obj.audienceTargetingCode : "&nbsp;",
                       uniques:       obj.uniques,
                    });
                    $self.audiences.add( aud_model );

                 }
                 $self.getSorted( sort_column, direction );
              }
           }, "json");


	},
        
        
        /**
         * getSorted - change the sort order...
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

	    // console.log(this.last_column); 
	    if(this.last_column != sort_column) {
		// This should be smarter...
		this.pollService(sort_column, direction);
	    }
	    else {
		// Just changing sort order, don't poll

            this.audiences.each( function(aud) {
               arr.push( aud );
            });
            
            if(direction == 'ASC') {
            	arr.sort( function(a, b) {
		   var a_val = isNaN(a.get(sort_column)) ? a.get(sort_column).toLowerCase() : parseInt(a.get(sort_column)),
			b_val = isNaN(b.get(sort_column)) ? b.get(sort_column).toLowerCase() : parseInt(b.get(sort_column));

            	   if( a_val== b_val ){
            	     return 0;
            	   }
             
            	   return a_val > b_val ? 1 : -1;               
            	});
	    }
	    else {
            	arr.sort( function(a, b) {
                   var a_val = isNaN(a.get(sort_column)) ? a.get(sort_column).toLowerCase() : parseInt(a.get(sort_column)),
                        b_val = isNaN(b.get(sort_column)) ? b.get(sort_column).toLowerCase() : parseInt(b.get(sort_column));


           	    if(b_val == a_val){
           	      return 0;
           	    }

           	    return b_val > a_val ? 1 : -1;
            	});
	    }
            
            // Only render the first 20
            for( var i = 0, obj = null; (obj = arr[i]) && (i < 20); i++ ) {

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
          }
        },

	/**
	 * Resort the table based on the currently cached results...
	 *
	 * @param  html		 $this	the <th> element the user clicked on
	 * @param  Backbone.View $view
	 */
	resort: function( $this, $view ) {
		var classname = $($this).attr('class'),
			sort_column = classname.match(/s_([a-z_]+)/)[1], 
			direction = classname.match(/ASC/) ? 'DESC' : 'ASC';
		
		if(sort_column) {
			$view.getSorted( sort_column, direction );
		}
	},

	/**
	 * Create a CSV from the current table
	 *
	 */
	exportToCSV: function() {

		var lines = ''; 
		$("#target-table tbody tr").each(function() { 
			var line = '';
			$(this).find('td').each(function() { 
				if(line) {
					line += ',';
				}
				line += '"' + $(this).text().trim() + '"';
			}) 
	
		
			lines += line + "\n"; 
	
		});

		var form = $('<form method="post" action="/index.php?p=service&a=csv" />'),
			csv_field = $('<input type="hidden" name="csv" />');
		form.append(csv_field);
		$(csv_field).val(lines); 

		$("body").append(form);
		form.submit(); 
		return false;
	}
});





var appview = new AppView;

})(jQuery);

