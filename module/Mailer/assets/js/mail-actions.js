$(document).ready(function() {
    $('a.mail-view').each(function() {
        var that = this;
        $(this).closest('tr').click(function(event) {
            event = event || window.event;
            var t = event.target || event.srcElement;
            if (t.tagName !== 'SPAN') {
                $.ajax(
                    {
                        url: that.getAttribute('href'),
                        success: function (result) {
                            $('#mail-content').html(result);
                            $('#mail-panel').removeClass('hidden');
                        }
                    }
                );
            }
            return false;
        });
    });

    $('a.mail-remove').each(function() {
        var that = this;
        $('span', $(this)).click(function() {
            $.ajax(
                {
                    url: that.getAttribute('href'),
                    success: function(result) {
                        if (result.status == 0) {
                            location.reload();
                        }
                    }
                }
            );
            return false;
        });
    });

    $('#mail-get-btn').click(function() {
        $.ajax(
            {
                url: this.getAttribute('href'),
                beforeSend: function() {
                    $('#status-panel').html('Загрузка писем...');
                },
                success: function(result) {
                    if (result.status == 0) {
                        location.reload();
                    } else {
                        $('#status-panel').html('Ошибка загрузки писем!');
                    }
                },
                error: function() {
                    $('#status-panel').html('Ошибка загрузки писем!');
                }
            }
        );
        return false;
    });

    $('#mail-new-btn').click(function() {
        location.href = this.getAttribute('href');
        return false;
    });
});