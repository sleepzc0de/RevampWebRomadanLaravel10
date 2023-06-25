/*
* Embed Media Dialog based on http://www.fluidbyte.net/embed-youtube-vimeo-etc-into-ckeditor
*
* Plugin name:      mediaembed
* Menu button name: MediaEmbed
*
* Youtube Editor Icon
* http://paulrobertlloyd.com/
*
* @author Fabian Vogelsteller [frozeman.de]
* @version 0.5
*/
( function() {
   CKEDITOR.plugins.add('mediaembed', {
    icons: 'mediaembed',
    hidpi: true,
    init: function(editor) {
        var me = this;

        // Menambahkan dialog MediaEmbed
        CKEDITOR.dialog.add('MediaEmbedDialog', function(instance) {
            return {
                title: 'Embed Media',
                minWidth: 550,
                minHeight: 200,
                contents: [{
                    id: 'iframe',
                    expand: true,
                    elements: [{
                        id: 'embedArea',
                        type: 'textarea',
                        label: 'Paste Embed Code Here',
                        'autofocus': 'autofocus',
                        setup: function(element) {},
                        commit: function(element) {}
                    }]
                }],
                onOk: function() {
                    var div = instance.document.createElement('div');
                    div.setHtml(this.getContentElement('iframe', 'embedArea').getValue());
                    instance.insertElement(div);
                }
            };
        });

        // Menambahkan command dan tombol MediaEmbed
        editor.addCommand('MediaEmbed', new CKEDITOR.dialogCommand('MediaEmbedDialog', {
            allowedContent: 'iframe[*]'
        }));

        editor.ui.addButton('MediaEmbed', {
            label: 'Embed Media',
            command: 'MediaEmbed',
            toolbar: 'mediaembed'
        });

        // Pengaturan extraPlugins dan mediaembed
        editor.config.extraPlugins = 'mediaembed';
        editor.config.mediaembed = {
            extraProviders: 'youtube'
        };
    }
});

} )();
