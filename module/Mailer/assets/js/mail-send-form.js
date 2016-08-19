/**
 * Created by blinov on 18.08.16.
 */
MailForm = {
    init: function() {
        var that = this;
        var form = $('#send-form');

        CKEDITOR.replace('content', {
            filebrowserUploadUrl: '/attachment/upload',
            extraPlugins: 'attach',
            autoCloseUpload: true,
            validateSize: 100,
            onAttachmentUpload: function(response) {
                var attachment = JSON.parse($(response).html());
                Attachment.addAttachment(attachment);
                return true;
            }
        });

        form.ajaxForm({
            url: form.prop('action'),
            dataType: 'json',
            beforeSend: function() {
                PleaseWait.toggle();
            },
            complete: function() {
                PleaseWait.toggle();
            },
            success: function(result) {
                $('#app-content').html(result.html);
                that.init();
                that.beautifyErrors();
                if (result.status == 0) {
                    $('#status-panel').html('Письмо успешно отправлено!');
                } else if (result.status == -1) {
                    $('#status-panel').html('Ошибка при отправке письма!');
                } else if (result.status == -2) {
                    $('#status-panel').html('Ошибка заполнения полей письма!');
                }

                $('input[data-role="tagsinput"]').tagsinput();
            }
        });

        $('#send-form-send-btn').click(function() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            form.submit();
            return false;
        });

        Attachment.initAttachments();
    },
    beautifyErrors: function() {
        $('.input-error').each(function() {
            $(this).closest('div.form-group').addClass('has-error');
        });
    }
};

Attachment = {
    lastAttachmentIndex: 0,
    getRemoveSpan: function() {
        return '<span class="glyphicon glyphicon-remove attachment-remove" id="attachment-remove-'+this.lastAttachmentIndex+'" aria-hidden="true" title="Удалить"></span>';
    },
    addAttachment: function(attachment) {
        $attachmentsFieldset = $('#send-form-attachments');
        var template = $('span', $attachmentsFieldset).data('template');
        template = template.replace(/__index__/g, this.lastAttachmentIndex);

        $(template)
            .append(attachment.name + this.getRemoveSpan())
            .appendTo($attachmentsFieldset);

        $span = $('#attachment-remove-' + this.lastAttachmentIndex);
        $span.parent().find('.send-mail-attachment-id').val(attachment.id);
        $span.parent().find('.send-mail-attachment-name').val(attachment.name);

        this.initRemoveAttachment($span);

        this.lastAttachmentIndex++;
    },
    initRemoveAttachment: function(span) {
        span.click(function() {
            $(this).closest('fieldset').remove();
        });
    },
    initAttachments: function() {
        var that = this;
        $('#send-form-attachments fieldset').each(function() {
            $(this).append($(this).find('.send-mail-attachment-name').val() + that.getRemoveSpan());
            that.initRemoveAttachment($(this).find('.attachment-remove'));
        });
    }
};

PleaseWait = {
    content: $('<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false" style="display:none"><div class="modal-dialog"><div class="modal-content"><div class="modal-body">Отправка письма...</div></div></div></div>'),
    show: function() {
        this.content.modal();
    },
    hide: function () {
        this.content.modal('hide');
    },
    toggle: function() {
        this.content.modal('toggle');
    }
};

$(document).ready(function() {
    MailForm.init();
});
