var ManageProductGroup = function() {
  var productGroupManage = function() {
      var productGroupTable = $('#m_product_group_datatable');

      var pageProductId = $('#current_product_id').val();

      productGroupTable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/product/group/getAllData/'+pageProductId,
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
            field: 'group_name',
            title: 'Group Title',
            width: 150,
          }, {
            field: 'type',
            title: 'Type',
            width: 100
          }, {
            field: 'extra',
            title: 'Extras',
            width: 100,
            template: function(row, index, datatable) {
                return '<a href="javascript:void(0)" data-product_group_id="'+row.id+'" class="groupExtraViewBtn">View Extras</a>';
            }
          }, {
            field: 'position',
            title: 'Position',
            width: 100
          }, {
              field: "Actions",
              width: 120,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';

                  return '\
                      <a href="javascript:;" data-product_group_id="'+row.id+'" class="product_group_delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                      <a href="javascript:;" data-product_group_id="'+row.id+'" class="product_group_edit_btn m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-edit"></i>\
                      </a>\
                      <a href="javascript:;" data-product_group_id="'+row.id+'" class="product_group_sort_btn m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-sort"></i>\
                      </a>\
                  ';
              }
          }],
      });

      $("#new_extra_group_add_modal").on("shown.bs.modal",function(){
          $(".m_selectpicker").selectpicker();
      });

      var extraGroupAddForm = $('#new_extra_group_add_form');

      var extraGroupAddFormValid = extraGroupAddForm.validate({
          rules: {
              group_name: {
                  required: true
              },
              group_type: {
                  required: true
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      extraGroupAddForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!extraGroupAddFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

          var isSelectExtra = false;

          form.find('input.product_extra_select_tag').each(function() {
              if($(this).is(':checked')) {
                  isSelectExtra = true;
              }
          });

          if (!isSelectExtra) {
              swal({title: "", text: "Please Choose one Extra at least.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
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
                      $('#new_extra_group_add_modal').modal('hide');
                      productGroupTable.reload();
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
      });

      $(document).on('click', '.groupExtraViewBtn', function(e) {
          var productGroupId = $(this).data('product_group_id');

          $.ajax({
              url: '/admin/product/group/getSingleGroup/'+productGroupId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      var viewGroupExtraModal = $('#group_extras_view_modal');
                      viewGroupExtraModal.find('#groupname').html(response.groupname);
                      $('#group_extras_view_tbody').html("");

                      response.extras.forEach(function(extra) {
                          var extraTr = '<tr><td>'+extra.extra_name+'</td><td>'+extra.extra_price+' €</td><td>'+extra.position+'</td></tr>';
                          $('#group_extras_view_tbody').append(extraTr);
                      })

                      viewGroupExtraModal.modal('show');
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

      $(document).on('click', '.product_group_delete_btn', function(e) {
          var $this = $(this);
         swal({
             title: 'Are you sure?',
             text: "Delete Extra Group !",
             type: 'warning',
             showCancelButton: true,
             confirmButtonText: ' Yes !',
             confirmButtonClass: "btn m-btn--air btn-outline-danger m-btn m-btn--wide",
             cancelButtonClass: "btn m-btn--air btn-outline-primary m-btn m-btn--wide",
         }).then(function(result) {
             if (result.value) {
                 var productGroupId = $this.data('product_group_id');

                 $.ajax({
                     url: '/admin/product/group/destroy/'+productGroupId,
                     type: 'get',
                     success: function(response){
                         if (response.result == "success") {
                             productGroupTable.reload();
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

      $(document).on('click', '.product_group_edit_btn', function(e) {
          var productGroupId = $(this).data('product_group_id');

          $.ajax({
              url: '/admin/product/group/getSingleProductGroup/'+productGroupId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      var productGroupEditForm = $('#exist_extra_group_edit_form');
                      productGroupEditForm.find('div.modal-body').html(response.html);
                      $('#exist_extra_group_edit_modal').modal('show');
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

      $("#exist_extra_group_edit_modal").on("shown.bs.modal",function(){
          $(".m_selectpicker").selectpicker();
      });

      var extraGroupEditForm = $('#exist_extra_group_edit_form');

      var extraGroupEditFormValid = extraGroupEditForm.validate({
          rules: {
              _group_name: {
                  required: true
              },
              _group_type: {
                  required: true
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      extraGroupEditForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!extraGroupEditFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

          var isSelectExtra = false;

          form.find('input.product_extra_select_tag').each(function() {
              if($(this).is(':checked')) {
                  isSelectExtra = true;
              }
          });

          if (!isSelectExtra) {
              swal({title: "", text: "Please Choose one Extra at least.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
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
                      $('#exist_extra_group_edit_modal').modal('hide');
                      productGroupTable.reload();
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
      });

      var local_product_group_sortable,
      local_product_single_group_sortable;

      var byId = function (id) {
          return document.getElementById(id);
      };

      $('#product_group_sort_model_open_btn').on('click', function(e) {
          $.ajax({
              url: '/admin/product/group/getAllData/'+pageProductId,
              type: 'get',
              success: function(response){
                  if (response.length > 0) {
                      console.log(response);
                      $('#group_extras_sort_ul').html("");
                      response.forEach(function(productGroup) {
                          var productGroupHtml = '<li data-id="'+productGroup.id+'">'+productGroup.group_name+'</li>';
                          $('#group_extras_sort_ul').append(productGroupHtml);
                      });

                      localStorage.setItem(local_product_group_sortable, '');

                      Sortable.create(byId('group_extras_sort_ul'), {
                          group: "words",
                          animation: 150,
                          store: {
                              get: function (sortable) {
                                  var order = localStorage.getItem(local_product_group_sortable);
                                  var splited_order = order ? order.split('|') : [];
                                  return splited_order;
                              },
                              set: function (sortable) {
                                  var order = sortable.toArray();
                                  localStorage.setItem(local_product_group_sortable, order.join('|'));
                              }
                          },
                      });

                      $('#group_extras_sort_modal').modal('show');
                  } else {
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

      $('#group_extras_sort_form').on('submit', function(e) {
          e.preventDefault();
          var form = $(this);
          var order = localStorage.getItem(local_product_group_sortable);
          var splited_order = order ? order.split('|') : [];
          var order_url = "/admin/product/group/setOrder/"+pageProductId;
          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          if (splited_order.length > 0) {
              var submit_btn = form.find('.form-submit-btn');
              submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

              $.post(
                  order_url,
                  {'sort_list': splited_order},
                  function(data, status){
                      submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                      if (status == "success") {
                          $('#group_extras_sort_modal').modal('hide');
                          productGroupTable.reload();
                      }
                  }
              );
          } else {
              swal({
                  "title": "Alert",
                  "text": "Please Update Order.",
                  "type": "warning",
                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
              });
          }
      });

      $(document).on('click', '.product_group_sort_btn', function() {
          var productGroupId = $(this).data('product_group_id');

          $.ajax({
              url: '/admin/product/group/getSingleGroup/'+productGroupId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      var sortGroupExtraModal = $('#single_group_extras_sort_modal');
                      sortGroupExtraModal.find('#sortgroupname').html(response.groupname);

                      $('#single_group_extras_sort_ul').html("");

                      response.extras.forEach(function(singleExtra) {
                          var liHtml = '<li data-id="'+singleExtra.id+'">'+singleExtra.extra_name+'</li>';
                          $('#single_group_extras_sort_ul').append(liHtml);
                      });

                      localStorage.setItem(local_product_single_group_sortable, '');

                      Sortable.create(byId('single_group_extras_sort_ul'), {
                          group: "words",
                          animation: 150,
                          store: {
                              get: function (sortable) {
                                  var order = localStorage.getItem(local_product_single_group_sortable);
                                  var splited_order = order ? order.split('|') : [];
                                  return splited_order;
                              },
                              set: function (sortable) {
                                  var order = sortable.toArray();
                                  localStorage.setItem(local_product_single_group_sortable, order.join('|'));
                              }
                          },
                      });

                      $('#single_group_extras_sort_modal').modal('show');

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

      $('#single_group_extras_sort_form').on('submit', function(e) {
          e.preventDefault();
          var form = $(this);
          var order = localStorage.getItem(local_product_single_group_sortable);
          var splited_order = order ? order.split('|') : [];

          var order_url = "/admin/product/group/single/setOrder";
          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          if (splited_order.length > 0) {
              var submit_btn = form.find('.form-submit-btn');
              submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

              $.post(
                  order_url,
                  {'sort_list': splited_order},
                  function(data, status){
                      submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                      if (status == "success") {
                          $('#single_group_extras_sort_modal').modal('hide');
                      }
                  }
              );
          } else {
              swal({
                  "title": "Alert",
                  "text": "Please Update Order.",
                  "type": "warning",
                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
              });
          }
      });
  };

  return {
    // public functions
    init: function() {
        productGroupManage();
    },
  };
}();

jQuery(document).ready(function() {
  ManageProductGroup.init();
});
