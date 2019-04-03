/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
var baseimagesPath = CKEDITOR.getUrl(CKEDITOR.plugins.getPath('templates') + 'templates/images/');
var edcontent1 = '<div class="editordivrow clearfixmain">' + 
			  '<div class="edimgleft"><img src="'+ baseimagesPath +'sampleimage.gif" /></div>' +
			  '<div class="edcontentright"><h2>Title</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p></div>' +
			  '</div>';
			  
var edcontent2 = '<div class="editordivrow clearfixmain">' + 
			  '<div class="edimgright"><img src="'+ baseimagesPath +'sampleimage.gif" /></div>' +
			  '<div class="edcontentleft"><h2>Title</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p></div>' +
			  '</div>';
			  
var edcontent3 = '<div class="editordivrow clearfixmain">' + 
			  '<div class="edimgcenter"><img src="'+ baseimagesPath +'sampleimage.gif" /></div>' +
			  '<h2>Title</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p>' +
			  '</div>';

var edcontent4 = '<div class="editordivrow clearfixmain noborder">' + 
			  '<h2>Title</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p>' +
			  '</div>';
			  
var edcontent5 = '<div class="editordivrow noborder clearfixmain">' + 
			  '<div class="left-cell-half"><h2>Title</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p></div>' +
			  '<div class="right-cell-half"><h2>Title</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p></div>' +
			  '</div>';

CKEDITOR.addTemplates('default', {
    imagesPath: CKEDITOR.getUrl(CKEDITOR.plugins.getPath('templates') + 'templates/images/'),
    templates: [{
        title: 'Template 1',
        image: 'fctemplate1.gif',
        description: 'Image left and Content right - Single row.',
        html: edcontent1	
    },{
        title: 'Template 2',
        image: 'fctemplate2.gif',
        description: 'Content left and Image right - Single row.',
        html: edcontent2	
    },{
        title: 'Template 3',
        image: 'fctemplate1.gif',
        description: 'Image left and Content right - Five rows.',
        html: edcontent1 + edcontent1 + edcontent1 + edcontent1 + edcontent1
    },{
        title: 'Template 4',
        image: 'fctemplate2.gif',
        description: 'Content left and Image right - Five rows.',
        html: edcontent2 + edcontent2 + edcontent2 + edcontent2 + edcontent2	
    },{
        title: 'Template 5',
        image: 'fctemplate3.gif',
        description: 'Image left and Content right for First row. Content left and Image right for Second row',
        html: edcontent1 + edcontent2	
    },{
        title: 'Template 6',
        image: 'fctemplate4.gif',
        description: 'Content left and Image right for First row. Image left and Content right for Second row',
        html: edcontent2 + edcontent1	
    },{
        title: 'Template 7',
        image: 'fctemplate5.gif',
        description: 'Image top center and content below - Single row.',
        html: edcontent3	
    },{
        title: 'Template 8',
        image: 'fctemplate6.gif',
        description: 'Title and content - Single row.',
        html: edcontent4	
    },{
        title: 'Template 9',
        image: 'fctemplate7.gif',
        description: 'Two Col Title and content - Single row.',
        html: edcontent5	
    }]
});