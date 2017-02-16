jQuery(document).ready(function($) {
    $('textarea').each(function () {
        this.setAttribute('style', 'width:100%;height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    }).trigger('input');
});