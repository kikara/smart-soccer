export default function SoundSetting() {
    let This = this;

    this.init = function () {
        This.$container = $('.js-container');
        This.buttons();
    }

    this.buttons = function () {
        This.onGoalSoundButton();
        This.onDeleteButton();
        This.onAddEventButton();
        This.onEventDeleteButton();
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

    this.onAddEventButton = function () {
        This.$container.on('click', '.js-add-event', function () {
            $.confirm({
                title: 'Добавить звук на событие',
                content: function () {
                    let self = this;
                    $.post(
                        '/api/popup/addEventPopup',
                        {},
                        function (response) {
                            if (response && response['result']) {
                                self.setContent(response['content']);
                                This.setAddParamButton(self.$content);
                                This.setDeleteParamButton(self.$content);
                                This.setSelectListener(self);
                            }
                        }, 'json');
                },
                onContentReady: function () {
                },
                buttons: {
                    accept: {
                        text: 'Создать',
                        btnClass: 'btn btn-green',
                        action: function () {
                            let self = this;
                            let form = this.$content.find('#event-add');
                            let formData = new FormData(form[0]);
                            formData.append('_token', $('meta[name=csrf-token]').attr('content'));
                            formData.append('params', This.prepareParam(this.$content));

                            $.ajax({
                                url: '/profile/save_event',
                                data: formData,
                                processData: false,
                                contentType: false,
                                type: 'POST',
                                dataType: 'JSON',
                                success: function (data) {
                                    if (data && data['result']) {
                                        This.$container.find('.js-event-container').append(data['content']);
                                        self.close();
                                    } else if (data['error']) {
                                        $.alert({
                                            title: 'Ошибка',
                                            content: data['error'],
                                            type: 'red',
                                        });
                                    }
                                }
                            });
                            return false;
                        }
                    },
                    close: {
                        text: 'Отмена'
                    }
                }
            })
        });
    }

    this.onEventDeleteButton = function () {
        This.$container.on('click', '.js-delete-event', function () {
            let $self = $(this);
            let $container = $self.parents('.js-event');
            $container.hide();
            let id = $container.data('id');
            $.post(
                '/profile/event_delete',
                {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    'id': id,
                },
                function (response) {
                    if (response && response['result']) {
                        $container.remove();
                    } else {
                        $container.show();
                    }
                }, 'json');
        });
    }

    this.setAddParamButton = function ($content) {
        $content.find('.js-add-param').on('click', function () {
            let $paramContainer = $content.find('.js-param-container').clone();
            let html = '<div><button class="btn btn-close js-delete-param"></button></div>';
            $paramContainer.append(html);
            $content.find('.js-param').append($paramContainer.prop('outerHTML'));
        });
    }

    this.setDeleteParamButton = function ($content) {
        $content.on('click', '.js-delete-param', function () {
            $(this).parents('.js-param-container').remove();
        });
    }


    this.prepareParam = function ($content) {
        let $containers = $content.find('.js-param-container');
        let data = {};
        $containers.each(function (k, v) {
            let $self = $(this);
            let inputValue = $self.find('input[name=param_value]').val();
            let value = inputValue ? inputValue : 1;
            let selected = $self.find('select[name=param]').val();
            if (value && selected !== '0') {
                let param_name = $self.find('select[name=param]').val();
                data[param_name] = value;
            }
        });
        return JSON.stringify(data);
    };

    this.setSelectListener = (context) => {
        context.$content.on('change', 'select[name=param]', function () {
            let value = this.value;
            if (value === 'winner') {
                $(this).parents('.js-param-container').find('input[name=param_value]').val(1).hide();
            }
        });
    }
}
