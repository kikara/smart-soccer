require('jquery-confirm');

export function CreateTournament() {
    const This = this;

    $('#tournamentCreateForm').click(e => {
        const dialog = $.dialog({
            title: 'Создание турнира',
            columnClass: 'small',
            content: function () {
                const self = this;
                self.showLoading(true);
                $.get(
                    '/tournaments/add',
                ).done(html => {
                    self.setContent(html);
                    This.create(dialog);
                    self.hideLoading(false);
                }).fail(() => {
                    console.log('error');
                })
            }
        })
    })

    this.create = (dialog) => {
        $('#tournamentCreateBtn').click(e => {
            e.preventDefault();
            const form = dialog.$content.find('#tournamentForm');
            const data = new FormData($(form)[0]);
            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: json => {
                    location.reload();
                },
                error: (res) => {
                    This.showErr(res?.responseJSON.err ?? 'Произошла неизвестная ошибка');
                },
            })
        })
    }

    this.showErr = (text) => {
        $('#tournamentAddErrors')
            .removeClass('d-none')
            .html(text);
    }
}
