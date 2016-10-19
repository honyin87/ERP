//Panel -START
$(document).ready(function() {

    $('.collapse-link').on('click', function() {
        var $BOX_PANEL = $(this).closest('.panel'),
            $ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.panel-body');
        
        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function(){
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200); 
            $BOX_PANEL.css('height', 'auto');  
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.panel');

        $BOX_PANEL.remove();
    });
});
//Panel -END