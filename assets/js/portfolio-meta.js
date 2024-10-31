var wordpress_ver=bg.wpv;
var professional_portfolio_attachments=[];
(function($){
    'use strict';
    
    var upload_button;
    initAttachments()
    $(document).on("click",".upload_image_button",function(event) {
        upload_button = $(this);
        var title=$(this).data( 'uploader-title' );
        var buttonText=$(this).data( 'uploader-button-text' );
        var frame;
        event.preventDefault();
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: title,
            button: {
              text: buttonText
            },
            type: ['image'],
            multiple: 'add',  // Set to allow multiple files to be selected
            toolbar:  'select'
        });
        
        frame.on( "select", function() {
            var attachments = frame.state().get('selection').toJSON();
            $.each(attachments, function(i, item) {
                if (item && item.id && item.sizes) {
                    if (item.sizes.thumbnail) {
                        var attachment = {
                            id: item.id,
                            src: item.sizes.thumbnail.url
                        };
                    } else {
                        var attachment = {
                            id: item.id,
                            src: item.url
                        };
                    }
                    addAttachmentToGalleryList(attachment);
                } else {
                    //there was a problem adding the item! Move on to the next
                }
                
                
            });
            //calculateAttachmentIds()
        });
        frame.open();
        
    });
    
    $('.professional-portfolio-attachments-list')
        .on('click' ,'a.remove', function(e) {
            e.preventDefault();
            var $selected = $(this).parents('li:first'),
            attachment_id = $selected.data('attachment-id');
            removeAttachmentFromGalleryList(attachment_id);
        }).sortable({
            items: 'li:not(.add-attachment)',
            distance: 10,
            placeholder: 'attachment placeholder',
            stop : function() {
                calculateAttachmentIds();
            }
        });
    
    function removeAttachmentFromGalleryList(id) {
        var index = $.inArray(id, professional_portfolio_attachments);
        if (index !== -1) {
            professional_portfolio_attachments.splice(index, 1);
        }
        $('.professional-portfolio-attachments-list [data-attachment-id="' + id + '"]').remove();
        calculateAttachmentIds();
    };
    function calculateAttachmentIds() {
        var sorted = [];
        $('.professional-portfolio-attachments-list li:not(.add-attachment)').each(function() {
            sorted.push( $(this).data('attachment-id') );
        });
        $('#professional_portfolio_attachments').val( sorted.join(',') );
    }
    function initAttachments() {
        var attachments = $('#professional_portfolio_attachments').val();
        if (attachments) {
            professional_portfolio_attachments = $.map(attachments.split(','), function (value) {
                    return parseInt(value, 10);
            });
        }
    }
    function addAttachmentToGalleryList(attachment) {
        if ($.inArray(attachment.id, professional_portfolio_attachments) !== -1) return;

        var $template = $($('#professional-portfolio-attachment-template').val());

        $template.attr('data-attachment-id', attachment.id);

        $template.find('img').attr('src', attachment.src);

        $('.professional-portfolio-attachments-list .add-attachment').before($template);

        professional_portfolio_attachments.push( attachment.id );

        calculateAttachmentIds();
    }
})(jQuery);