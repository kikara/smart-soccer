export default function SoundSetting () {
    let This = this;

    this.init = function () {
        This.$container = $('.js-container');
        This.buttons();
    }

    this.buttons = function () {
        This.onGoalSoundButton();
        This.onDeleteButton();
    }

    this.onGoalSoundButton = function () {
        This.$container.on('click', '.js-sound-upload', function () {
            let $self = $(this);
            $self.attr('disabled', true);
            let form = This.$container.find('#audio-single-goal')[0];
            let formData = new FormData(form);
            $.ajax({
                url: '/profile/save_single_sound',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    $self.attr('disabled', false);
                    if (data && data['result']) {
                        This.$container.find('.js-single-audio-container').append(data['template']);
                        This.$container.find('input[name=single-sound]').val('');
                    }

                }
            });
        });
    }

    this.onDeleteButton = function () {
        This.$container.on('click', '.js-delete-audio', function () {
            let $self = $(this);
            let $audioContainer = $self.parents('.js-audio-container');
            $audioContainer.removeClass('d-flex').hide();
            let id = $audioContainer.data('id');
            $.post(
                '/profile/delete_single_audio',
                {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    'id': id,
                },
                function (response) {
                    if (response && response['result']) {
                        $audioContainer.remove();
                    } else {
                        $audioContainer.show();
                    }
                }, 'json');
        });
    }
}
