CKEDITOR.plugins.add( 'shortcodes',
{   
   requires : ['richcombo'], //, 'styles' ],
   init : function( editor )
   {
      var config = editor.config,
         lang = editor.lang.format;

      // Gets the list of tags from the settings.
	  var tags = []; //new Array();
      //this.add('value', 'drop_text', 'drop_label');
	  
	  var b_sURL = bkfolder + "admin/onlyadminajax.php";
	  $.post(b_sURL,
		{
			az:24,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);
			var kk = 0;
			for (var this_data in data){
				tags[kk]=[data[this_data]["scode"], data[this_data]["name"], data[this_data]["name"]];				
				kk++;
			}
			
		});
      
      // Create style objects for all defined styles.

      editor.ui.addRichCombo( 'shortcodes',
         {
            label : "Insert Shortcodes",
            title :"Insert Shortcodes",
            voiceLabel : "Insert Shortcodes",
            className : 'cke_format',
            multiSelect : false,

            panel :
            {
               css : [ config.contentsCss, CKEDITOR.getUrl( editor.skinPath + 'editor.css' ) ],
               voiceLabel : lang.panelVoiceLabel
            },

            init : function()
            {
               this.startGroup( "Insert Shortcodes" );
               //this.add('value', 'drop_text', 'drop_label');
               for (var this_tag in tags){
                  this.add(tags[this_tag][0], tags[this_tag][1], tags[this_tag][2]);
               }
            },

            onClick : function( value )
            {         
               editor.focus();
               editor.fire( 'saveSnapshot' );
               editor.insertHtml('<div>' + value + '</div>');
               editor.fire( 'saveSnapshot' );
            }
         });
   }
});