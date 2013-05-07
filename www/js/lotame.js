(function ($) {








/*
Friend = Backbone.Model.extend({
        //Create a model to hold friend atribute
        name: null
});

Friends = Backbone.Collection.extend({
        //This is our Friends collection and holds our Friend models
        initialize: function (models, options) {
                this.bind("add", options.view.addFriendLi);
                //Listen for new additions to the collection and call a view function if so
        }
});

AppView = Backbone.View.extend({
        el: $("body"),
        initialize: function () {
                this.friends = new Friends( null, { view: this });
                //Create a friends collection when the view is initialized.
                //Pass it a reference to this view to create a connection between the two
        },
        events: {
                "click #add-friend":  "showPrompt",
        },
        showPrompt: function () {
                var friend_name = prompt("Who is your friend?");
                var friend_model = new Friend({ name: friend_name });
                //Add a new friend model to our friend collection
                this.friends.add( friend_model );
        },
        addFriendLi: function (model) {
                //The parameter passed is a reference to the model that was added
                $("#friends-list").append("<li>" + model.get('name') + "</li>");
                //Use .get to receive attributes of the model
        }
});
*/

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
            this.audiences = new Audiences( null, { view: this });
            
           // Load available audiences
           $.get('http://dev.christopherbroome.com/?p=service', function(data){
              if (data instanceof Array) {
                 for (var i=0, obj = null; obj=data[i]; i++) {
                    var aud_model = new Audience({
                       name:          obj.name,
                       page_views:    obj.pageViews,
                       // target_code:   obj.audienceTargetingCode,
                       uniques:       obj.uniques,
                    });
                    this.audiences.add( aud_model )                     
                    
                 }
              }
           }, "json");
             
        },
        events: {
                    
        },

        renderRow: function(){
                
        }
});





var appview = new AppView;

})(jQuery);

