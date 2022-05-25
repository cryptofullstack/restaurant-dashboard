var ManageCupon = function() {
  var cuponManage = function() {

      var cuponsDatatable = $('#m_cupons_datatable');

      cuponsDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/cupons/getCuponDatas',
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
            field: 'cupon_name',
            title: 'Cupon name',
            width: 150,
          },  {
            field: 'percent',
            title: 'Percentage',
            width: 150,
            template: function(row) {
                return row.percent+' %';
            }
          }, {
            field: 'user_id',
            title: 'User',
            width: 150,
            template: function(row) {
                return row.user_name;
            }
          }, {
              field: "Actions",
              width: 70,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';

                  return '\
                      <a href="javascript:;" data-cupon_id="'+row.id+'" class="cupon-delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                  ';
              }
          }],
      });

      var cuponUsersDatatable = $('#m_cupon_users_datatable');

      cuponUsersDatatable.mDatatable({
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
                       <input type="checkbox" class="cupon_user_select_tag" value="'+row.id+'" name="cupon_users[]">\
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

      $("#user_cupon_add_modal").on("shown.bs.modal",function(){
          cuponUsersDatatable.reload();
      });

      var cuponeAddForm = $('#user_cupon_add_form');

      var cuponeAddFormValid = cuponeAddForm.validate({
          rules: {
              cupon_name: {
                  required: true
              },
              cupon_percent: {
                  required: true,
                  number: true
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      $('#user_cupon_add_form').on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!cuponeAddFormValid.form()) {
              swal({title: "", text: "Please add correct data.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
              return false;
          };

          var isSelectUser = false;

          form.find('input.cupon_user_select_tag').each(function() {
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
                  console.log(response)
                  if (response.result === "success") {
                      $('#user_cupon_add_modal').modal('hide');
                      cuponsDatatable.reload();
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

      $(document).on('click', '.cupon-delete_btn', function() {
          var $this = $(this);

          swal({
              title: 'Are you sure?',
              text: "Delete User !",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: ' Yes !',
              confirmButtonClass: "btn m-btn--air btn-outline-danger m-btn m-btn--wide",
              cancelButtonClass: "btn m-btn--air btn-outline-primary m-btn m-btn--wide",
          }).then(function(result) {
              if (result.value) {
                  var cuponId = $this.data('cupon_id');
                  $.ajax({
                      url: '/admin/cupons/destroy/'+cuponId,
                      type: 'get',
                      success: function(response){
                          if (response.result == "success") {
                              swal({
                                  "title": "Success",
                                  "text": "Cupon Deleted !.",
                                  "type": "success",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                              cuponsDatatable.reload();
                          } else if (response.result == "error") {
                              swal({
                                  "title": "Faild",
                                  "text": "something went wrong !.",
                                  "type": "error",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                          }
                      },
                      error: function(error){
                          console.log(error);
                      }
                  });
              }
          });
      });
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
