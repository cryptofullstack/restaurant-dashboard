var ManageCategory = function() {
  var categoryManage = function() {

      var pageCategoryId = $('#current_category_id').val();

      var categoryProductsDatatable = $('#m_category_products_datatable');

      categoryProductsDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/category/products/getAllDatas/'+pageCategoryId,
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
            field: 'image',
            title: 'Image',
            width: 100,
            template: function(row, index, datatable) {
                return '<img style="width: 100%;" src="/uploads/products/'+row.pro_image+'" />'
            }
          }, {
            field: 'pro_name',
            title: 'Title',
            width: 150,
          }, {
            field: 'pro_price',
            title: 'Price',
            width: 100
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
                  return '\
                      <a href="javascript:;" data-category_product_id="'+row.id+'" class="category_product_delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                  ';
              }
          }],
      });

      $('#category_product_add_modal_open_btn').on('click', function(e) {
          $.ajax({
              url: '/admin/category/unProducts/getAllDatas/'+pageCategoryId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      $('#category_unassign_products').html(response.html);
                      $('#new_category_product_add_modal').modal('show');
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

      $('#new_category_product_add_form').on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          var submitable = false;

          form.find('input.category_products_select_tag').each(function() {
              if($(this).is(':checked')) {
                  submitable = true;
              }
          });

          if (!submitable) {
              swal({title: "", text: "Please Choose one Product at least.", type: "error", confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"})
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
                      $('#new_category_product_add_modal').modal('hide');
                      categoryProductsDatatable.reload();
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

      $(document).on('click', '.category_product_delete_btn', function(){
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
                  var catProId = $this.data('category_product_id');
                  $.ajax({
                      url: '/admin/category/product/destroy/'+catProId,
                      type: 'get',
                      success: function(response){
                          if (response.result == "success") {
                              swal({
                                  "title": "Success",
                                  "text": "Product Removed !",
                                  "type": "success",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                              categoryProductsDatatable.reload();
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

      var local_category_products_sortable;

      var byId = function (id) {
          return document.getElementById(id);
      };

      $('#category_product_sort_model_open_btn').on('click', function(e) {
          $.ajax({
              url: '/admin/category/products/getAllDatas/'+pageCategoryId,
              type: 'get',
              success: function(response){
                  if (response.length > 0) {
                      $('#category_product_sort_ul').html("");
                      response.forEach(function(catPro) {
                          var categoryProductHtml = '<li data-id="'+catPro.id+'">'+catPro.pro_name+'</li>';
                          $('#category_product_sort_ul').append(categoryProductHtml);
                      });

                      localStorage.setItem(local_category_products_sortable, '');

                      Sortable.create(byId('category_product_sort_ul'), {
                          group: "words",
                          animation: 150,
                          store: {
                              get: function (sortable) {
                                  var order = localStorage.getItem(local_category_products_sortable);
                                  var splited_order = order ? order.split('|') : [];
                                  return splited_order;
                              },
                              set: function (sortable) {
                                  var order = sortable.toArray();
                                  localStorage.setItem(local_category_products_sortable, order.join('|'));
                              }
                          },
                      });

                      $('#category_product_sort_modal').modal('show');
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

      $('#category_product_sort_form').on('submit', function(e) {
          e.preventDefault();
          var form = $(this);

          var order = localStorage.getItem(local_category_products_sortable);
          var splited_order = order ? order.split('|') : [];
          var order_url = "/admin/category/products/setOrder";
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
                          $('#category_product_sort_modal').modal('hide');
                          categoryProductsDatatable.reload();
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
        categoryManage();
    },
  };
}();

jQuery(document).ready(function() {
  ManageCategory.init();
});
