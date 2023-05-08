/* auto close confirm dialog in seconds*/
const START_TIMER = 20;
var timer;

$(document).ready(function () {
    const MODAL = new bootstrap.Modal('#delete-confirmation', {backdrop: 'static', keyboard: false});
    const createModal = new bootstrap.Modal('#create-node-modal');

    $(document).on('hidden.bs.modal', '#delete-confirmation', function () {
        clearInterval(timer);
    });


    $(document).on('hidden.bs.modal', '#create-node-modal', function () {
        if (typeof $('#node-parent_id') !== 'undefined') {
            $('#node-parent_id').remove();
        }
        if (typeof $('#node-id') !== 'undefined') {
            $('#node-id').remove();
        }
        $('#node-text').val('');
    });


    $(document).on('click', '#js-show-create-modal', function () {
        createModal.show();
    });


    $(document).on('click', '.js-add-node', function () {
        let form = $('#create-node');
        form.append('<input type="hidden" id="node-parent_id" name="parent_id" value="' + $(this).data('parent_id') + '"/>')
        $('#node-text').val('');
        createModal.show();
    });


    $(document).on('click', '.js-edit-node', function () {
        let form = $('#create-node');
        $('#node-text').val($(this).data('text'));
        form.append('<input type="hidden" id="node-id" name="id" value="' + $(this).data('id') + '"/>')
        createModal.show();
    });


    $(document).on('submit', '#create-node', function (e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    if ($('#tree').find('ul').length === 0) {
                        $('#js-show-create-modal').parent().addClass('visually-hidden');
                        $('#tree').append('<ul class="list-group"><li class="list-group-item">' + renderNodes(data) + '</li></ul>');
                    }
                    else {
                        if ($('#node-' + data.id).length > 0) {
                            $('#node-' + data.id).text(data.text);
                            $('#node-' + data.id).parent().find('.js-edit-node').first().data('text',data.text);
                        } else {
                            if ($('#node-' + data.parent_id).parent().find('ul').length === 0) {
                                $('#node-' + data.parent_id).parent().append('<ul></ul>');
                                $('#node-' + data.parent_id).prepend(' <i data-id="' + data.parent_id + '" class="js-show-child bi bi-caret-down-fill"></i>');
                            }
                            $('#node-' + data.parent_id).parent().find('ul').first().append('<li>' + renderNodes(data) + '</li>');

                        }
                    }
                    $('#node-text').val('');
                    createModal.hide();
                }

            },
            error: function (request, status, err) {

            }
        });
    });


    $(document).on('click', '.js-show-child', function () {
        let element = $(this);
        if (element.hasClass('bi-caret-right-fill')) {
            element.removeClass('bi-caret-right-fill').addClass('bi-caret-down-fill');
            $('#node-' + element.data('id')).parent().find('ul').remove();
            $.ajax({
                url: 'tree/load-child',
                type: 'POST',
                data: {
                    id: element.data('id')
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status === 'success') {
                        let text = '<ul>';
                        $.each(data.childs, function (key, value) {
                            text += '<li>' + renderNodes(this) + '</li>';
                        })
                        text += '</ul>';
                        $('#node-' + element.data('id')).parent().append(text);
                    }
                },
                error: function (request, status, err) {

                }
            });
        } else {
            element.removeClass('bi-caret-down-fill').addClass('bi-caret-right-fill');
            element.parent().find('ul').remove();
        }
    });


    function renderNodes(data) {
        let text = '';
        if (data.isChilds) {
            text += ' <i data-id="' + data.id + '" class="js-show-child bi bi-caret-right-fill"></i>';
        }
        text += '<span id="node-' + data.id + '">' + data.text + '</span>';
        text += ' <i data-parent_id="' + data.id + '" class="js-add-node bi bi-file-plus"></i>';
        text += ' <i data-id="' + data.id + '" class="js-remove-node bi bi-file-minus"></i>';
        text += ' <i data-id="' + data.id + '" data-text="' + data.text + '" class="js-edit-node bi-pencil"></i>';
        return text;
    }


    $(document).on('click', '.js-remove-node', function () {
        let timerContainer = $('#timer-confirm');
        $('#js-delete-item').data('id', $(this).data('id'))
        timerContainer.text(START_TIMER);

        MODAL.show();
        timer = setInterval(function () {
            let currentTime = timerContainer.text();
            if (currentTime > 0) {
                timerContainer.text(currentTime - 1);
            } else {
                MODAL.hide();
                clearInterval(timer);
            }
        }, 1000);
    });

    $(document).on('click', '#js-delete-item', function () {
        let element = $(this);
        $.ajax({
            url: 'tree/delete',
            type: 'POST',
            data: {
                id: element.data('id')
            },
            dataType: 'json',
            success: function (data) {

                if ($('#tree').find('ul li').length === 0) {
                    $('#tree').find('ul').remove();
                }
                else {
                    let parentLi = $('#node-' + element.data('id')).parent();
                    let parentUl = parentLi.parent();
                    parentLi.remove();
                    if (parentUl.find('li').length === 0) {
                        parentUl.parent().find('.js-show-child').remove();
                        parentUl.remove();
                    }
                }

                if($('#tree .js-add-node').length === 0) {
                    $('#js-show-create-modal').parent().removeClass('visually-hidden');
                }

                MODAL.hide();
                clearInterval(timer);
            }
        });
    })

});