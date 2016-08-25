CKEDITOR.plugins.add('maxfiles',
{
	init : function(editor)
	{
		// Add the link and unlink buttons.
		CKEDITOR.dialog.addIframe('maxfiles_dialog', 'Files', SITE_URL + ADMIN_URL+'/wysiwyg/files_wysiwyg',700,400,function(){}, {
			onLoad: function(){
				var id = '#'+this.parts.contents.getId();
				$('.cke_dialog_page_contents', id).css({height:'100%'});
			}
		});
		editor.addCommand('maxfiles', {exec:maxfiles_onclick} );
		editor.ui.addButton('maxfiles',{ label:'Upload or insert files from library.', command:'maxfiles', icon:this.path+'images/icon.png' });

		// Register selection change handler for the unlink button.
		editor.on( 'selectionChange', function( evt )
		{
			/*
			 * Despite our initial hope, files.queryCommandEnabled() does not work
			 * for this in Firefox. So we must detect the state by element paths.
			 */
			var command = editor.getCommand( 'maxfiles' ),
				element = evt.data.path.lastElement.getAscendant( 'a', true );

			// If nothing or a valid files
			if ( ! element || (element.getName() == 'a' && ! element.hasClass('max-files')))
			{
				command.setState(CKEDITOR.TRISTATE_OFF);
			}

			else
			{
				command.setState(CKEDITOR.TRISTATE_DISABLED);
			}
		});

	}
} );

function maxfiles_onclick(e)
{
	update_instance();
    // run when max button is clicked]
    CKEDITOR.currentInstance.openDialog('maxfiles_dialog')
}