require('jquery-confirm');

$('#tournamentCreate').click(e => {
    $.dialog({
        title: 'Создание турнира',
        columnClass: 'small',
        content: function() {
            const self = this;
            self.showLoading(true);
            $.get(
                '/tournaments/add',
            )
            .done(html => {
                self.setContent(html);
                self.hideLoading(false);
            }).fail(() => {
                console.log('error');
            })
        }
    })
})
