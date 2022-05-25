var ManageExtra = function() {
  var extraManage = function() {

      var extraDatatable = $('#m_extras_datatable');

      extraDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/extra/getAllDatas',
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
            field: 'extra_name',
            title: 'Title',
            width: 150,
          }, {
            field: 'extra_price',
            title: 'Price',
            width: 100,
            template: function(row, index, datatable) {
                return row.extra_price+" €";
            }
          }, {
              field: "Actions",
              width: 100,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';

                  return '\
                      <a href="javascript:;" data-extra_id="'+row.id+'" class="extra_delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                      <a href="javascript:;" data-extra_id="'+row.id+'" class="extra_edit_btn m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-edit"></i>\
                      </a>\
                  ';
              }
          }],
      });

      var extraAddForm = $('#new_extra_add_form');

      var extraAddFormValid = extraAddForm.validate({
          rules: {
              extra_name: {
                  required: true
              },
              extra_price: {
                  required: true
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      extraAddForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!extraAddFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

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
                      $('#new_extra_add_modal').modal('hide');
                      form[0].reset();
                      extraDatatable.reload();
                  } else {
                      swal({
                          title: "Error",
                          text: response.msg,
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

      $(document).on('click', '.extra_edit_btn', function() {
          var extraId = $(this).data('extra_id');

          $.ajax({
              url: '/admin/extra/getExtraData/'+extraId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      var editForm = $('#exist_extra_edit_form');
                      editForm.find('#edit_extra_id').val(response.extra.id);
                      editForm.find('#_extra_name').val(response.extra.extra_name);
                      editForm.find('#_extra_price').val(response.extra.extra_price);

                      $('#exist_extra_edit_modal').modal('show');
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
      });

      var extraEditForm = $('#exist_extra_edit_form');

      var extraEditFormValid = extraEditForm.validate({
          rules: {
              _extra_name: {
                  required: true
              },
              _extra_price: {
                  required: true
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      extraEditForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!extraEditFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

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
                      $('#exist_extra_edit_modal').modal('hide');
                      extraDatatable.reload();
                  } else {
                      swal({
                          title: "Error",
                          text: response.msg,
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
      });

      $(document).on('click', '.extra_delete_btn', function(){
          var $this = $(this);

          swal({
              title: 'Are you sure?',
              text: "Delete extra !",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: ' Yes !',
              confirmButtonClass: "btn m-btn--air btn-outline-danger m-btn m-btn--wide",
              cancelButtonClass: "btn m-btn--air btn-outline-primary m-btn m-btn--wide",
          }).then(function(result) {
              if (result.value) {
                  var extraId = $this.data('extra_id');
                  $.ajax({
                      url: '/admin/extra/destroy/'+extraId,
                      type: 'get',
                      success: function(response){
                          if (response.result == "success") {
                              swal({
                                  "title": "Success",
                                  "text": "extra Deleted !.",
                                  "type": "success",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                              extraDatatable.reload();
                          } else if (response.result == "error") {
                              swal({
                                  "title": "Faild",
                                  "text": response.msg,
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
        extraManage();
    },
  };
}();

jQuery(document).ready(function() {
  ManageExtra.init();
});
