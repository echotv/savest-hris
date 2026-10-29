$(function() {

    /*
    =========================================
    |                                       |
    |          Variables Definations        |
    |                                       |
    =========================================
    */

    var Container = {
        scrumboard: $('.scrumboard'),
        card: $('.scrumboard .card')
    }

    // Containers
    var scrumboard = Container.scrumboard;
    var card = Container.card;


    // Make Task Sortable

    function $_taskSortable() {
        $('[data-sortable="true"]').sortable({
            connectWith: '.connect-sorting-content',
            items: ".card",
            cursor: 'move',
            placeholder: "ui-state-highlight",
            refreshPosition: true,
            stop: function( event, ui ) {
                var parent_ui = ui.item.parent().attr('data-section');

            },
            update: function( event, ui ) {
                console.log(ui);
                console.log(ui.item);
            }
        });
    }

    function $_caseActive() {
      
    // Adding a button inside each task card to go to the next task
    $('.case-section').each(function() {
        $(this).find('.task-take-case-area').each(function() {
            var nextButtonHTML = '<button class="btn btn-primary take-task-modal w-100">Take Case</button>';
            $(this).append(nextButtonHTML); // Append the button to each .task-take-case-area inside the section
        });
    });

    // Handle "Take Case" button click event
    $('.take-task-modal').on('click', function(event) {
        event.preventDefault();

        var currentCard = $(this).closest('.card'); // Get the task card that was clicked

        // Show confirmation modal
        $('#confirmationModal').modal('show');

        // On confirmation, move the task
        $('#confirmNextTask').on('click', function() {
            var currentSection = currentCard.closest('.connect-sorting-content'); // Get the section (list) that the task belongs to

            // Get the next section (if exists)
            var nextSection = $('.active-section');

            if (nextSection.length > 0) {
                // Move the current card to the next section
                currentCard.find('.task-take-case-area').remove();
                nextSection.append(currentCard);

                // Close the modal after confirming
                $('#confirmationModal').modal('hide');
            } else {
                // If there's no next section, alert the user or handle as needed
                alert('No next section available!');
            }

            $_caseClosed(); // Call your existing function if needed
        });
    });
}

function $_caseClosed() {
    console.log('sad');
    
    // Adding a button inside the task card to go to the next task (only if it doesn't already exist)
    $('.active-section').each(function() {
        var lastTaskTakeCaseArea = $(this).find('.task-close-case-area').last(); // Get the last .task-take-case-area

        // Check if the button is already present to avoid duplication
        if (!lastTaskTakeCaseArea.find('.close-modal').length) {
            var nextButtonHTML = '<button class="btn btn-primary close-modal w-100">Close Case</button>';
            lastTaskTakeCaseArea.append(nextButtonHTML); // Append the button to the last .task-take-case-area
        }
    });

    // Handle "Close Case" button click event
    $('.close-modal').on('click', function(event) {
        event.preventDefault();

        // Show the modal when the "Close Case" button is clicked
        var currentCard = $(this).closest('.card'); // Get the task card that was clicked
        $('#taskCloseModal').modal('show');

        // Store the current card in a variable to be used later when closing the task
        $('#closeTaskButton').on('click', function() {
            var nextSection = $('.case-closed-section');

            if (nextSection.length > 0) {
                // Move the current card to the next section
                currentCard.find('.task-close-case-area').remove();
                nextSection.append(currentCard);
                $('#taskCloseModal').modal('hide'); // Hide the modal after closing the task
            } else {
                // If there's no next section, alert the user or handle as needed
                alert('No next section available!');
                $('#taskCloseModal').modal('hide');
            }
        });
    });
}


    // Click on Add Task button to open the modal and more..

    function addTask() {
        $('.addTask').on('click', function(event) {
            event.preventDefault();
            getParentElement = $(this).parents('[data-connect="sorting"]').attr('data-section');
            $('.edit-task-title').hide();
            $('.add-task-title').show();
            $('[data-btnfn="addTask"]').show();
            $('[data-btnfn="discardTask"]').show();
            $('[data-btnfn="editTask"]').hide();
            // $('.addTaskAccordion .collapse').collapse('hide');
            $('#addTaskModal').modal('show');
            $_taskAdd(getParentElement);
        });
    }

    // Get the range count value

    $('#progress-range-counter').on('input', function(event) {
        event.preventDefault();
        /* Act on the event */
        getRangeValue = $(this).val();
        $('.range-count-number').html(getRangeValue);
        $('.range-count-number').attr('data-rangeCountNumber', getRangeValue);
    });

    // Reset the input Values

    $('#addTaskModal, #addListModal').on('hidden.bs.modal', function (e) {
        $('input,textarea').val('');
        $('input[type="range"]').val(0);
        $('input[type="checkbox"]').prop('checked', false);
        $('.range-count-number').attr('data-rangecountnumber', 0);
        $('.range-count-number').html(0);
    })


    function $_taskAdd(getParent) {
    $('[data-btnfn="addTask"]').off('click').on('click', function(event) {

        getAddBtnClass = $(this).attr('class').split(' ')[1];

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth()); // January is 0!
        var yyyy = today.getFullYear();

        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        today = dd + ' ' + monthNames[mm] + ', ' + yyyy;

        var $_getParent = getParent;

        var $_caseNo = document.getElementById('s-case').value;
        var $_personName = document.getElementById('s-person').value;
        var $_caseType = document.getElementById('s-type').value;
        var $_address = document.getElementById('s-address').value;

        var $_taskProgress = $('.range-count-number').attr('data-rangeCountNumber');

        // Check if any of the fields are empty

        // HTML for the card
        var $html = '<div data-draggable="true" class="card task-text-progress" style="cursor: pointer;">' +
            '<div class="card-body">' +
            '<div class="task-body">' +
            '<div class="task-bottom">' +
            '<div class="tb-section-1">' +
            '<span data-taskDate="' + today + '">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">' +
            '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>' +
            '<line x1="16" y1="2" x2="16" y2="6"></line>' +
            '<line x1="8" y1="2" x2="8" y2="6"></line>' +
            '<line x1="3" y1="10" x2="21" y2="10"></line>' +
            '</svg> ' + today + '</span>' +
            '</div>' +
            '<div class="tb-section-2">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-case-edit edit edit-delete-calltaker">' +
            '<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>' +
            '</svg>' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-case-delete edit-delete-calltaker">' +
            '<polyline points="3 6 5 6 21 6"></polyline>' +
            '<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>' +
            '<line x1="10" y1="11" x2="10" y2="17"></line>' +
            '<line x1="14" y1="11" x2="14" y2="17"></line>' +
            '</svg>' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-case-edit view" style="cursor: pointer;">' +
            '<path d="M1 12s3-7 11-7 11 7 11 7-3 7-11 7-11-7-11-7z"></path>' +
            '<circle cx="12" cy="12" r="3"></circle>' +
            '</svg>' +
            '</div>' +
            '</div>' +
            '<div class="case-title">';
            if($_caseNo !== ""){
                $html += '<h5 class="no-padding-margin-top-bottom" data-caseNo="' + $_caseNo + '">' + $_caseNo + '</h5>';
            }
            if($_caseType !== ""){
                $html += '<p class="no-padding-margin-top-bottom" data-caseType="' + $_caseType + '" >' + $_caseType + '</p>';
            }
            $html += '</div>' +
            '</div>' +
            '<div class="task-body mb-2">' +
            '<div class="task-content">';
            if($_personName !== ""){
                $html += '<u><p class="no-padding-margin-top-bottom" data-personName="' + $_personName + '">' + $_personName + '</p></u>';
            }
            if($_address !== ""){
                $html += '<p class="no-padding-margin-top-bottom" data-address="' + $_address + '">' + $_address + '</p>';
            }
            $html += '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="take-case-dispatcher task-take-case-area"></div>' +
            '<div class="close-case-dispatcher task-close-case-area"></div>' +
            '</div>' +
            '</div>';

        $("[data-section='" + $_getParent + "'] .connect-sorting-content").append($html);

        $('#addTaskModal').modal('hide');

        const role = localStorage.getItem('role'); // Get role from localStorage

        
        $('#role').html(role);

        $_taskEdit();
        $_taskDelete();
        $_caseActive();
      
        if (role !== 'admin') {
              $('#admin-section').last().addClass('hidden');
          }
          if (role !== 'dispatcher') {
              $('#dispatcher-section').addClass('hidden');
              $('.take-case-dispatcher').last().addClass('hidden');
              $('.close-case-dispatcher').last().addClass('hidden');
          }
          if (role !== 'calltaker') {
              $('#calltaker-section').addClass('hidden');
              $('#add-new-case').last().addClass('hidden');
              $('#edit-delete-calltaker').last().addClass('hidden');
          }

    });
}


    $("#add-list").off('click').on('click', function(event) {
      event.preventDefault();

        $('.add-list').show();
        $('.edit-list').hide();
        $('.edit-list-title').hide();
        $('.add-list-title').show();
        $('#addListModal').modal('show');
    });

    $(".add-list").off('click').on('click', function(event) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = mm + '.' + dd + '.' + yyyy;

        var $_listTitle = document.getElementById('s-list-name').value;

        var $_listTitleLower = $_listTitle.toLowerCase();
        var $_listTitleRemoveWhiteSpaces = $_listTitleLower.split(' ').join('_') ;
        var $_listSectionDataAttr = $_listTitleRemoveWhiteSpaces;


        $html = '<div data-section="s-'+$_listSectionDataAttr+'" class="task-list-container  mb-4 " data-connect="sorting">'+
                    '<div class="connect-sorting">'+
                        '<div class="task-container-header">'+
                            '<h6 class="s-heading" data-listTitle="'+$_listTitle+'">'+$_listTitle+'</h6>'+
                            '<div class="dropdown">'+
                                '<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>'+
                                '</a>'+
                                '<div class="dropdown-menu dropdown-menu-right left" aria-labelledby="dropdownMenuLink-4">'+
                                    '<a class="dropdown-item list-edit" href="javascript:void(0);">Edit</a>'+
                                    '<a class="dropdown-item list-delete" href="javascript:void(0);">Delete</a>'+
                                    '<a class="dropdown-item list-clear-all" href="javascript:void(0);">Clear All</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+

                        '<div class="connect-sorting-content" data-sortable="true">'+
                            
                        '</div>'+

                        '<div class="add-s-case">'+
                            '<a class="addTask"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Add Task</a>'+
                        '</div>'+

                    '</div>'+
                '</div>';

        $(".task-list-section").append($html); 
        $('#addListModal').modal('hide');
        $('#s-list-name').val('');
        $_taskSortable();
        $_editList()
        $_deleteList();
        $_clearList();
        addTask();
        $_taskEdit();
        $_taskDelete();
        $_caseActive();
    })

    // Delete the whole list including tasks at on click

    function $_deleteList() {
        $('.list-delete').off('click').on('click', function(event) {
            event.preventDefault();
            $(this).parents('[data-connect]').remove();
        })
    }
    function $_editList() {
      
        $('.list-edit').off('click').on('click', function(event) {

            event.preventDefault();

            var $_outerThis = $(this);
           
            $('.add-list').hide();
            $('.edit-list').show();

            $('.add-list-title').hide();
            $('.edit-list-title').show();

            var $_listTitle = $_outerThis.parents('[data-connect="sorting"]').find('.s-heading').attr('data-listTitle');
            $('#s-list-name').val($_listTitle);

            $('.edit-list').off('click').on('click', function(event) {
                var $_innerThis = $(this);
                var $_getListTitle = document.getElementById('s-list-name').value;

                var $_editedListTitle = $_outerThis.parents('[data-connect="sorting"]').find('.s-heading').html($_getListTitle);
                var $_editedListTitleDataAttr = $_outerThis.parents('[data-connect="sorting"]').find('.s-heading').attr('data-listTitle', $_getListTitle);

                $('#addListModal').modal('hide');
                $('#s-list-name').val('');
            })
            $('#addListModal').modal('show');
            $('#addListModal').on('hidden.bs.modal', function (e) {
                $('#s-list-name').val('');
            })
        })
    }

    // Clear all task at on click

    function $_clearList() {
        $('.list-clear-all').off('click').on('click', function(event) {
            event.preventDefault();
            $(this).parents('[data-connect="sorting"]').find('.connect-sorting-content .card').remove();
        })
    }

    // Delete the task on click 

    function $_taskDelete() {
        $('.card .s-case-delete').off('click').on('click', function(event) {
            event.preventDefault();

            get_card_parent = $(this).parents('.card');

            $('#deleteConformation').modal('show');

            $('[data-remove="task"]').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                get_card_parent.remove();
                $('#deleteConformation').modal('hide');
            });

        })
    }

    function $_taskEdit() {
      
      $('.card .view').off('click').on('click', function(event) {

        event.preventDefault();

        var $_outerThis = $(this);

        $('.add-task-title').hide();
        $('.edit-task-title').hide();        
        $('.view-task-title').show();


        $('[data-btnfn="addTask"]').hide();
        $('[data-btnfn="editTask"]').hide();        
        $('[data-btnfn="discardTask"]').hide();


        var $_caseNo = $_outerThis.parents('.card').find('h5').attr('data-caseNo');
        var get_value_title = $('.task-text-progress #s-case').val($_caseNo);

        var $_caseType = $_outerThis.parents('.card').find('p').eq(0).attr('data-caseType');
        var get_value_title = $('.task-text-progress #s-type').val($_caseType);

        var $_personName = $_outerThis.parents('.card').find('p:not(".progress-count")').eq(1).attr('data-personName');
        var get_value_text = $('.task-text-progress #s-person').val($_personName);

        var $_address = $_outerThis.parents('.card').find('p').eq(2).attr('data-address');
        var get_value_text = $('.task-text-progress #s-address').val($_address);

        var $_taskProgress = $_outerThis.parents('.card').find('div.progress-bar').attr('data-progressState');
        var get_value_progress = $('#progress-range-counter').val($_taskProgress);
        var get_value_progressHtml = $('.range-count-number').html($_taskProgress);
        var get_value_progressDataAttr = $('.range-count-number').attr('data-rangecountnumber', $_taskProgress);

        $('#addTaskModal').modal('show');
      });

      $('.card .edit').off('click').on('click', function(event) {

        event.preventDefault();

        var $_outerThis = $(this);
       
        $('.add-task-title').hide();
        $('.edit-task-title').show();
        $('.view-task-title').hide();

        $('[data-btnfn="addTask"]').hide();
        $('[data-btnfn="editTask"]').show();
        $('[data-btnfn="discardTask"]').show();

            var $_caseNo = $_outerThis.parents('.card').find('h5').attr('data-caseNo');
            var get_value_title = $('.task-text-progress #s-case').val($_caseNo);

            var $_caseType = $_outerThis.parents('.card').find('p').eq(0).attr('data-caseType');
            var get_value_title = $('.task-text-progress #s-type').val($_caseType);

            var $_personName = $_outerThis.parents('.card').find('p:not(".progress-count")').eq(1).attr('data-personName');
            var get_value_text = $('.task-text-progress #s-person').val($_personName);

            var $_address = $_outerThis.parents('.card').find('p').eq(2).attr('data-address');
            var get_value_text = $('.task-text-progress #s-address').val($_address);

            var $_taskProgress = $_outerThis.parents('.card').find('div.progress-bar').attr('data-progressState');
            var get_value_progress = $('#progress-range-counter').val($_taskProgress);
            var get_value_progressHtml = $('.range-count-number').html($_taskProgress);
            var get_value_progressDataAttr = $('.range-count-number').attr('data-rangecountnumber', $_taskProgress);

        $('[data-btnfn="editTask"]').off('click').on('click', function(event) {
            var $_innerThis = $(this);

                var $_caseNoValue = document.getElementById('s-case').value;
                var $_personNameValue = document.getElementById('s-person').value;
                var $_caseTypeValue = document.getElementById('s-type').value;
                var $_addressValue = document.getElementById('s-address').value;
                var $_taskProgressValue = $('.range-count-number').attr('data-rangeCountNumber');

                var $_taskDataAttr = $_outerThis.parents('.card').find('h5').attr('data-caseNo' , $_caseNoValue);
                var $_caseNo = $_outerThis.parents('.card').find('h5').html($_caseNoValue);
                var $_personNameDataAttr = $_outerThis.parents('.card').find('p:not(".progress-count")').eq(1).attr('data-personName' , $_personNameValue);
                var $_personName = $_outerThis.parents('.card').find('p:not(".progress-count")').eq(1).html($_personNameValue);
                var $_caseTypeDataAttr = $_outerThis.parents('.card').find('p').eq(0).attr('data-caseType' , $_caseTypeValue);
                var $_caseType = $_outerThis.parents('.card').find('p').eq(0).html($_caseTypeValue);
                var $_caseAddressDataAttr = $_outerThis.parents('.card').find('p').eq(2).attr('data-address' , $_addressValue);
                var $_caseAddress = $_outerThis.parents('.card').find('p').eq(2).html($_addressValue);


                var $_taskProgressStyle = $_outerThis.parents('.card').find('div.progress-bar').attr('style', "width: " + $_taskProgressValue +"%");
                var $_taskProgressDataAttr = $_outerThis.parents('.card').find('div.progress-bar').attr('data-progressState', $_taskProgressValue);
                var $_taskProgressAriaAttr = $_outerThis.parents('.card').find('div.progress-bar').attr('aria-valuenow', $_taskProgressValue);
                var $_taskProgressProgressCount = $_outerThis.parents('.card').find('.progress-count').html($_taskProgressValue+"%");

            $('#addTaskModal').modal('hide');
            var setDate = $('.taskDate').html('');
            $('.taskDate').hide();
        })
        $('#addTaskModal').modal('show');
      })
    }

$_editList();
$_deleteList();
$_clearList();
addTask();
$_taskEdit();
$_taskDelete();
$_taskSortable();
$_caseActive();
});