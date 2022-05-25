var ManageCupon = function() {
  var cuponManage = function() {

      var pushDatatable = $('#m_push_datatable');

      pushDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/push/getAllData',
                  method: 'GET',
  			},
  		},
          pageSize: 5,
        },

        // column sorting
        sortable: true,

        pagination: true,

        toolbar: {
          // toolbar items
          items: {
            // pagination
            pagination: {
              // page size select
              pageSizeSelect: [5, 10, 20, 30, 50, 100],
            },
          },
        },

        // columns definition
        columns: [
           {
            field: 'name',
            title: 'User',
            width: 150,
          },  {
            field: 'push_title',
            title: 'Title',
            width: 150,
          }, {
            field: 'push_body',
            title: 'Text',
            width: 150,
          }, {
              field: "Actions",
              width: 70,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  return '\
                      <a href="javascript:;" data-cupon_id="'+row.id+'" class="cupon-delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                  ';
              }
          }],
      });

      var pushUsersDatatable = $('#m_push_users_datatable');

      pushUsersDatatable.mDatatable({
          data: {
            type: 'remote',
            source: {
               read: {
                   url: '/admin/cupons/getCuponeUsers',
                    method: 'GET',
               },
           },
            pageSize: 5,
          },

          // column sorting
          sortable: true,

          pagination: true,

          toolbar: {
            // toolbar items
            items: {
              // pagination
              pagination: {
                // page size select
                pageSizeSelect: [5, 10, 20, 30, 50, 100],
              },
            },
          },

          search: {
            input: $('#generalSearch'),
          },

          // columns definition
          columns: [
             {
              field: "id",
              title: "#",
              width: 30,
              sortable: false,
              textAlign: 'center',
              overflow: "visible",
              template: function(row, index, datatable) {
                  return '\
                        <label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand">\
                        <input type="checkbox" class="push_user_select_tag" value="'+row.id+'" name="push_users[]">\
                        <span></span>\
                        </label>\
                        ';
              },
            }, {
              field: 'name',
              title: 'User name',
              width: 150,
            },  {
              field: 'email',
              title: 'Email',
              width: 150,
            }],
      });

      $("#user_push_send_modal").on("shown.bs.modal",function(){
          pushUsersDatatable.reload();
      });

      var pushSendForm = $('#user_push_send_form');

      var pushSendFormValid = pushSendForm.validate({
          rules: {
              push_title: {
                  required: true
              },
              push_body: {
                  required: true
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      $('#user_push_send_form').on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!pushSendFormValid.form()) {
              swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
              return false;
          };

          var isSelectUser = false;

          form.find('input.push_user_select_tag').each(function() {
              if($(this).is(':checked')) {
                  isSelectUser = true;
              }
          });

          if (!isSelectUser) {
              swal({title: "", text: "Please Choose one user at least.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
              return false;
          }

          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          var url = form.attr('action');

          var formData = new FormData(form[0]);
          var submit_btn = form.find('.form-submit-btn');
          submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

          $.ajax({
              url: url,
              type: 'POST',
              data: formData,
              success: function(response) {
                  submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                  if (response.result === "success") {
                      $('#user_push_send_modal').modal('hide');
                      pushDatatable.reload();
                      form[0].reset();
                  } else {
                      swal({
                          title: "Error",
                          text: 'something went wrong',
                          type: "error",
                          confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                      });
                  }
              },
              processData: false,
              contentType: false,
              error: function(error) {
                  console.log(error);
              }
          });
      })
  };

  return {
    // public functions
    init: function() {
      cuponManage();
    },
  };
}();

jQuery(document).ready(function() {
  ManageCupon.init();
});
