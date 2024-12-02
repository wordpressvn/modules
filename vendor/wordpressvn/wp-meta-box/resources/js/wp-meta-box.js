function wmb_dispatch_event(name, detail) {
    var event = new CustomEvent(name, { detail: detail });
    document.dispatchEvent(event);
}
jQuery(function($) {
    new ClipboardJS('.clipboard').on('success', function(event) {
        var $trigger = $(event.trigger),
            originalTooltip = $trigger.attr('tooltip'),
            copiedText = event.text;
            
        $trigger.attr('tooltip', wp.i18n.__('Copied: ') + copiedText);
        setTimeout(function() {
            $trigger.attr('tooltip', originalTooltip);
        }, 3000);
    });
});