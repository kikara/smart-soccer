export function Participation() {
    const This = this;

    $('#tournamentsTable').on('click', '.acceptInvitation', e => {
        This.processingBtn(e);
    }).on('click', '.cancelInvitation', e => {
        This.processingBtn(e);
    })

    this.processingBtn = e => {
        e.preventDefault();

        const info = This.getInfo(e.target);
        $.ajax({
            type: 'post',
            url: info.link,
            data: info.data,
            headers: info.header,
            dataType: 'json',
            success: json => {
                $(e.target).closest('tr').find('.playersCount').html(json.players);
                $(e.target).replaceWith(json.html);
            }
        })
    }

    this.getInfo = btn => {
        return {
            link: $(btn).attr('href'),
            data: {
                id: $(btn).data('id'),
            },
            header: {
                'X-CSRF-TOKEN': This.getToken(),
            },
        };
    }

    this.getToken = () => {
        return $('meta[name=csrf-token]').attr('content');
    }
}
