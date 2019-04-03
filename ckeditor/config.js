/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.skin = 'kama';
	config.resize_enabled = false;
	config.contentsCss = siteurlfull + '/css/style.css';
	config.bodyClass = 'adminbodyclass text_area';
	config.extraPlugins = 'shortcodes,wpmore,opencontent';
		
	config.toolbar_adminsmall =
	[
		['Source', 'Cut','Copy','Paste','PasteText','PasteFromWord','SpellChecker'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],		
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],	
		['NumberedList','BulletedList','-','Outdent','Indent','CreateDiv','-','WPMore','OpenContent','-','Templates'],		
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','HorizontalRule','Smiley'],
		['Link','Unlink','Anchor','-','Image','Flash','Table','SpecialChar','PageBreak','Iframe'],
		['TextColor','BGColor'],
		['Styles','Format','Font','FontSize'],
		['shortcodes']
	];
	
	config.toolbar_frontendbasic =
	[
		['Source','Bold','Italic','Underline','NumberedList','BulletedList'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','SpellChecker'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat']
	];
	
	config.toolbar_frontendbasic2 =
	[
		['Bold','Italic','Underline','NumberedList','BulletedList']
	];
	
	config.toolbar_Basic =
	[
		['Bold','Italic','-','NumberedList','BulletedList','-','Link','Unlink']
	];
};

CKEDITOR.on('instanceReady', function (ev) {
ev.editor.dataProcessor.htmlFilter.addRules(
    {
        elements:
        {
            $: function (element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;

                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];

                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];

                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }

                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                    }
                }



                if (!element.attributes.style)
                    delete element.attributes.style;

                return element;
            }
        }
    });
});