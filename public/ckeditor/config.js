﻿/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
config.toolbar = 'Medium';

config.toolbar_Medium =
[
    ['Source',/*'-','Save','NewPage',/*'Preview','-','Templates'*],
    ['Cut','Copy','Paste','PasteText','PasteFromWord'/*,'-','Print', ,'SpellChecker', 'Scayt'*/],
    [/*'Undo','Redo','-',*/'Find','Replace','SelectAll','RemoveFormat'],
   // ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],

    ['Bold','Italic','Underline',/*'Strike',*/'Subscript','Superscript'],
    ['NumberedList','BulletedList'],
    '/',
    ['Outdent','Indent'/*,'Blockquote'],
    [*/,'JustifyLeft','JustifyCenter','JustifyRight'/*,'JustifyBlock'*/],
    [/*'Styles',*/'Format','Font'/*,'FontSize'*/],
     '/',
     ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'/*,'PageBreak'*/],
    
    ['TextColor','BGColor'/*],
    [*/,'Maximize', 'ShowBlocks'/*,'-','About'*/],
    ['Link','Unlink'/*,'Anchor'*/]
];

};
